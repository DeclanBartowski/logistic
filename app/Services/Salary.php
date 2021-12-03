<?php


namespace App\Services;


use App\Models\Wage;
use App\Models\WageProperty;
use App\Models\WagePropertyOption;
use App\Models\WagePropertyValue;
use Fomvasss\Dadata\Facades\DadataSuggest;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Salary
{
    public $arCurrencies = [
        'BYN' => 'BYN',
        'EUR' => 'EUR',
        'USD' => 'USD'
    ];
    public
        $userWage,
        $defaultCurrency = 'BYN',
        $specialityPropertyId = 1,
        $excludedFilterProps = [11, 12],
        $wagePropertyId = 12;

    protected function getPercentile($arData, $coefficient)
    {
        sort($arData);
        $pos = (count($arData) - 1) * $coefficient;

        $base = floor($pos);
        $rest = $pos - $base;

        if (isset($arData[$base + 1])) {
            return $arData[$base] + $rest * ($arData[$base + 1] - $arData[$base]);
        } else {
            return $arData[$base];
        }
    }

    protected function getRates()
    {
        $httpClient = new Client();
        $arCurrencies = $this->arCurrencies;
        $arRates = Cache::remember('get_rates', 3600, function () use ($httpClient, $arCurrencies) {
            $result = $httpClient->request('GET', 'https://www.nbrb.by/api/exrates/rates?periodicity=0')->getBody();
            return array_filter(json_decode($result, true), function ($arItem) use ($arCurrencies) {
                return isset($arCurrencies[$arItem['Cur_Abbreviation']]);
            });
        });

        return array_combine(array_column($arRates, 'Cur_Abbreviation'), array_column($arRates, 'Cur_OfficialRate'));
    }

    private function getHtmlField($property, &$html, $class = '')
    {
        $name = sprintf('properties[%1$s]', $property->id);
        $oldName = sprintf('properties.%1$s', $property->id);
        if ($this->userWage) {
            $savedProperty = $this->userWage->where('wage_property_id', $property->id)->first();
            if ($savedProperty) {
                $savedValue = $savedProperty->wage_property_option_id ?: $savedProperty->value;
            }
        }

        switch ($property->type) {
            case 'currency':
                if (isset($savedValue) && $savedValue) {
                    $savedValue = json_decode($savedValue, true);
                }
                $options = [];
                if ($this->arCurrencies) {
                    foreach ($this->arCurrencies as $key => $value) {
                        $options[] = sprintf('<option value="%1$s" %3$s>%2$s</option>', $key, $value,
                            old($oldName . '.currency', $savedValue['currency'] ?? '') == $key ? 'selected' : '');
                    }
                }
                $html .= sprintf('<input type="text" placeholder="1 500" class="small" name="%1$s[value]" value="%3$s">
						<select class="sel small2" name="%1$s[currency]">
						 <option></option>
							%2$s
						</select>', $name, implode('', $options), old($oldName . '.value', $savedValue['value'] ?? ''));
                break;
            case 'location':
                $html .= sprintf('<input type="text" placeholder="" data-suggestion="%3$s" name="%1$s" value="%2$s"><ul class="tq_suggestion"></ul>',
                    $name, old($oldName, $savedValue ?? ''), route('salary.suggestion'));
                break;
            case 'list':
                $options = [];
                if ($property->options) {
                    foreach ($property->options as $value) {
                        $options[] = sprintf('<option value="%1$s" title="%3$s" %4$s>%2$s</option>', $value->id,
                            $value->value, $value->hint,
                            old($oldName, $savedValue ?? '') == $value->id ? 'selected' : '');
                    }
                }

                $html .= sprintf('
                    <select class="sel %1$s" name="%3$s">
                    <option></option>
                        %2$s
                    </select>', $class, implode('', $options), $name);
                break;
        }
    }

    private function getHtml($property, $allRequired = true)
    {
        if ($property->parent_id) {
            return '';
        }
        if ($allRequired) {
            $requiredHtml = '<span>*</span>';
        } else {
            $requiredHtml = '';
        }
        if ($property->hint) {
            $hint = sprintf('<i>(%s)</i>', $property->hint);
        } else {
            $hint = '';
        }
        $html = sprintf('<div class="field-name">
                    %s%s %s
                </div>', $requiredHtml, $property->name, $hint);
        if (isset($property->child) && $property->child) {
            $html .= '<div class="field mb">';
        } else {
            $html .= '<div class="field">';
        }
        $this->getHtmlField($property, $html, isset($property->child) && $property->child ? 'sel' : '');
        $html .= '</div>';
        if (isset($property->child) && $property->child) {
            $html .= '<div class="field flex">';
            foreach ($property->child as $key => $childProperty) {
                $html .= '<div class="column">';
                if ($childProperty->hint) {
                    $hint = sprintf('<i>(%s)</i>', $childProperty->hint);
                } else {
                    $hint = '';
                }
                //if ($key == 0) {
                $html .= sprintf('<div class="field-name">
                    %s%s %s
                </div>', $requiredHtml, $childProperty->name, $hint);
                /*} else {
                    $html .= sprintf('<div class="after"><div class="field-name">
                    %s%s %s
                </div>', $requiredHtml, $childProperty->name, $hint);
                }*/

                $this->getHtmlField($childProperty, $html, 'sel');
                $html .= '</div>';
            }
            $html .= '</div>';
        }


        return $html;
    }

    private function getWages($arFilter = [])
    {
        $dateFrom = sprintf('%s-01-01 00:00:00', date('Y') - 1);
        $wages = Wage::whereBetween('updated_at', [$dateFrom, date('Y-m-d 23:59:59')])->with('properties');

        if ($arFilter) {
            $arFilter = array_diff($arFilter, [null]);
            $propertyValues = new WagePropertyValue();
            $cnt = 0;
            foreach ($arFilter as $id => $value) {
                if ($cnt == 0) {
                    $logic = 'where';
                } else {
                    $logic = 'orWhere';
                }

                if (intval($value)) {
                    $propertyValues = $propertyValues->$logic(function ($q) use ($id, $value) {
                        $q->where([
                            'wage_property_id' => $id,
                            'wage_property_option_id' => $value
                        ]);
                    });
                } else {
                    $propertyValues = $propertyValues->$logic(function ($q) use ($id, $value) {
                        $q->where([
                            'wage_property_id' => $id,
                            'value' => $value
                        ]);
                    });
                }
                $cnt++;
            }
            $propertyValues = $propertyValues->get()->groupBy('wage_id');
            if ($propertyValues) {
                foreach ($propertyValues as $wageId => $arItems) {
                    if (count($arItems) == count($arFilter)) {
                        $arIds[] = $wageId;
                    }
                }
            }

            if (!isset($arIds) || !$arIds) {
                $arIds = [null];
            }
            $wages->whereIn('id', $arIds);
        }
        return $wages->get();
    }

    private function getBetweenCount($arItems, $min = 0, $max = 0)
    {
        $arFiltered = array_filter($arItems, function ($value) use ($min, $max) {
            return $value >= $min && $value <= $max;
        });
        return count($arFiltered);
    }

    public function getSelfStatistic()
    {
        $userWage = Auth::user()->load('wage')->getRelation('wage');
        if ($userWage) {
            $specProperty = WagePropertyValue::where([
                'wage_property_id' => $this->specialityPropertyId,
                'wage_id' => $userWage->id
            ])->select('wage_property_option_id')->first();
            if ($specProperty) {
                return $this->getStatistic([$this->specialityPropertyId => $specProperty->wage_property_option_id],
                    true);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * @param array $arFilter
     * @param false $isSelf
     * @return array
     */
    public function getStatistic($arFilter = [], $isSelf = false)
    {
        $wages = $this->getWages($arFilter);
        $arRates = $this->getRates();
        $arItems = [];
        foreach ($wages as $wage) {
            if ($salary = $wage->getRelation('properties')->where('wage_property_id', $this->wagePropertyId)->first()) {
                $arWage = json_decode($salary->value, true);
                if ($arWage) {
                    if ($arWage['currency'] && $arWage['currency'] != $this->defaultCurrency) {
                        $arItems[] = round($arWage['value'] / $arRates[$arWage['currency']], 0);
                    } else {
                        $arItems[] = $arWage['value'];
                    }
                }
            }
        }

        if (count($arItems) > 5) {
            $min = min($arItems);
            $max = max($arItems);
            $arPercentiles = [
                5 => $this->getPercentile($arItems, 0.05),
                25 => $this->getPercentile($arItems, 0.25),
                50 => $this->getPercentile($arItems, 0.50),
                75 => $this->getPercentile($arItems, 0.75),
                95 => $this->getPercentile($arItems, 0.95),
            ];
            $countPercentiles = $this->getBetweenCount($arItems, $arPercentiles[5], $arPercentiles[95]);

            $diffWage = ($max - $min) / 10;
            $pervValue = $min;
            $value = $pervValue + $diffWage;
            $arSteps = [];
            for ($i = 1; $i <= 10; $i++) {
                $percent = round($this->getBetweenCount($arItems, $pervValue, $value) / $wages->count() * 100, 1);
                $arSteps[] =
                    [
                        'name' => sprintf('%s - %s', __('wages.price', [
                            'price' => number_format(round($pervValue, 0), '0', '', ' ')
                        ]), __('wages.price', [
                            'price' => number_format(round($value, 0), '0', '', ' ')
                        ])),
                        'value' => __('wages.graph_value', ['value' => $percent]),
                        'percent' => $percent,
                        'median' => $pervValue <= $arPercentiles[50] && $arPercentiles[50] <= $value
                    ];
                $pervValue = $value;
                $value += $diffWage;
            }

            $result = [
                'steps' => $arSteps,
                'percentiles' => [
                    5 => [
                        'value' => __('wages.price', [
                            'price' => number_format(floor($arPercentiles[5]), '0', '', ' ')
                        ]),
                        'width' => $this->getBetweenCount($arItems, $arPercentiles[5],
                                $arPercentiles[25] - 1) / $countPercentiles * 100
                    ],
                    25 => [
                        'value' => __('wages.price', [
                            'price' => number_format(floor($arPercentiles[25]), '0', '', ' ')
                        ]),
                        'width' => $this->getBetweenCount($arItems, $arPercentiles[25],
                                $arPercentiles[50] - 1) / $countPercentiles * 100
                    ],
                    75 => [
                        'value' => __('wages.price', [
                            'price' => number_format(floor($arPercentiles[75]), '0', '', ' ')
                        ]),
                        'width' => $this->getBetweenCount($arItems, $arPercentiles[50],
                                $arPercentiles[75] - 1) / $countPercentiles * 100
                    ],
                    95 => [
                        'value' => __('wages.price', [
                            'price' => number_format(floor($arPercentiles[95]), '0', '', ' ')
                        ]),
                        'width' => $this->getBetweenCount($arItems, $arPercentiles[75],
                                $arPercentiles[95]) / $countPercentiles * 100
                    ],

                ],
                'median' => __('wages.median', [
                    'price' => number_format(round($arPercentiles[50]), '0', '', ' ')
                ]),
                'count' => $wages->count(),
                'min' => __('wages.price', ['price' => number_format(floor($min), '0', '', ' ')]),
                'max' => __('wages.price', ['price' => number_format(ceil($max), '0', '', ' ')]),
                'title_medians' => [
                    'rub' => number_format($arPercentiles[50], '0', ',', ' '),
                    'usd' => number_format($arPercentiles[50] / $arRates['USD'], '0', ',', ' '),
                    'eur' => number_format($arPercentiles[50] / $arRates['EUR'], '0', ',', ' '),
                ]

            ];
        } else {
            $result = [
                'error' => __('wages.error'),
                'count' => $wages->count(),
            ];
        }
        if ($arFilter) {
            if (!$isSelf) {
                if (isset($result['error']) && $result['error']) {
                    $result['title_error'] = __('wages.filter_error');
                }
            }
            $arOptions = WagePropertyOption::whereIn('id', $arFilter)->get()->keyBy('id')->toArray();
            if ($arOptions) {
                $arTitle = [];
                foreach ($arFilter as $value) {
                    if ($value) {
                        $arTitle[] = isset($arOptions[$value]['value']) && $arOptions[$value]['value'] ? $arOptions[$value]['value'] : $value;
                    }
                }
                $result['title'] = implode(' / ', $arTitle);
            }
        }


        return $result;
    }

    public function getAddForm()
    {
        $html = '';
        $properties = WageProperty::where('active', 1)->orderBy('sort', 'asc')->with('options')->get();
        if (Auth::check()) {
            $wage = Auth::user()->load('wage')->getRelation('wage');
            if ($wage) {
                $wage->load('properties');
                $this->userWage = $wage->getRelation('properties');
            }
        }

        if ($properties) {
            foreach ($properties as $property) {
                if ($property->parent_id && $parentProperty = $properties->find($property->parent_id)) {
                    $parentProperty->child = array_merge($parentProperty->child ?? [], [$property]);
                }
            }
            foreach ($properties as $property) {
                $html .= $this->getHtml($property);
            }
        }
        return $html;
    }

    public function getFilterForm()
    {
        $html = '';
        $properties = WageProperty::where('active', 1)->whereNotIn('id', $this->excludedFilterProps)->orderBy('sort',
            'asc')->with('options')->get();

        if ($properties) {
            foreach ($properties as $property) {
                $property->name = $this->mb_ucfirst(trim(str_replace(['ваша', 'ваше', 'ваш'], '',
                    mb_strtolower($property->name))));
                if ($property->parent_id && $parentProperty = $properties->find($property->parent_id)) {
                    $parentProperty->child = array_merge($parentProperty->child ?? [], [$property]);
                }
            }
            foreach ($properties as $property) {
                $html .= $this->getHtml($property, false);
            }
        }
        return $html;
    }

    protected function mb_ucfirst($str, $charset = '')
    {
        if ($charset == '') {
            $charset = mb_internal_encoding();
        }
        $letter = mb_strtoupper(mb_substr($str, 0, 1, $charset), $charset);
        $suffix = mb_substr($str, 1, mb_strlen($str, $charset) - 1, $charset);
        return $letter . $suffix;
    }

    public function getRules()
    {
        $arRules = [];
        $properties = WageProperty::where('active', 1)->get();
        if ($properties) {
            foreach ($properties as $property) {
                $name = sprintf('properties.%1$s', $property->id);
                switch ($property->type) {
                    case 'currency':
                        $arRules[sprintf('%s.value', $name)] = 'required';
                        $arRules[sprintf('%s.currency', $name)] = 'required';
                        break;
                    default:
                        $arRules[sprintf('%s', $name)] = 'required';
                        break;
                }
            }
        }
        return $arRules;
    }

    public function getAttributes()
    {
        $arMessages = [];
        $properties = WageProperty::where('active', 1)->get();
        if ($properties) {
            foreach ($properties as $property) {
                $name = sprintf('properties.%1$s', $property->id);
                switch ($property->type) {
                    case 'currency':
                        $arMessages[sprintf('%s.value', $name)] = $property->name;
                        $arMessages[sprintf('%s.currency', $name)] = sprintf('%s (%s)', $property->name,
                            __('validation.currency'));
                        break;
                    default:
                        $arMessages[sprintf('%s', $name)] = $property->name;
                        break;
                }
            }
        }
        return $arMessages;
    }

    public function getCitySuggestion($query)
    {
        $result = [];
        try {
            $result = DadataSuggest::suggest("address",
                [
                    'query' => $query,
                    'count' => 10,
                    'language' => App::getLocale() == 'ru' ? 'ru' : 'en',
                    'locations' => [
                        'country' => 'Беларусь'
                    ],
                    'from_bound' => ['value' => 'city'],
                    'to_bound' => ['value' => 'city'],
                ]);
            if (isset($result['value'])) {
                $result = [$result];
            }
        } catch (\Exception $exception) {
        }

        return $result;
    }

    public function saveWage($arData)
    {
        $user = Auth::user();
        $wage = Wage::updateOrCreate(
            ['user_id' => $user->id],
            ['name' => sprintf('%s %s', $user->phone, date('d.m.Y H:i:s'))]
        );
        if ($wage) {
            $properties = WageProperty::where('active', 1)->with('options')->get();
            $wage->load('properties');
            $arProperties = [];
            foreach ($arData['properties'] as $propertyId => $property) {
                if ($mainProperty = $properties->find($propertyId)) {
                    if (is_array($property)) {
                        $property = json_encode($property);
                    }

                    $arProperties[] = WagePropertyValue::updateOrCreate([
                        'wage_id' => $wage->id,
                        'wage_property_id' => $propertyId
                    ], [
                        'wage_property_id' => $propertyId,
                        'wage_property_option_id' => intval($property) ? intval($property) : null,
                        'value' => $mainProperty->getRelation('options')->find($property)->value ?? $property,
                        'name' => $mainProperty->name,
                    ]);
                }
            };

            if ($arProperties) {
                $wage->properties()->saveMany($arProperties);
            }
        }
        event(new \App\Events\MyEvent());
        return redirect()->route('salary.self')->with('status', __('wages.success'));
    }
}
