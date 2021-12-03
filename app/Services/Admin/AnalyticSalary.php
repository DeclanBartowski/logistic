<?php


namespace App\Services\Admin;

use App\Models\Wage;
use App\Models\WageProperty;
use App\Models\WagePropertyValue;
use App\Services\Salary;
use Carbon\Carbon;

class AnalyticSalary extends Salary
{
    public function getWagesProperties()
    {
        return WageProperty::whereIn('id', $this->excludedFilterProps)->get();
    }

    public function getAnalyticForm($request = [])
    {
        return WageProperty::where('active', 1)->whereNotIn('id', $this->excludedFilterProps)->orderBy('sort',
            'asc')->with('options', 'values')->get()->each(function ($item) use ($request) {
            $options = null;
            $values = $item->getRelation('values');
            $options = $item->getRelation('options');

            $key = $item->name;
            $item->options = $options->merge($values->whereNull('wage_property_option_id')->unique('value'))->each(function (
                $option
            ) use ($request, $key) {
                if (isset($request['properties'][$key]) && $request['properties'][$key] == $option->value) {
                    $option->selected = true;
                }
            });
        });
    }

    public function getCompareAnalytic($request, $wageId = 0)
    {
        if (isset($request['first_filter']) && $request['first_filter']) {
            $request['first_filter']['properties'] = array_diff($request['first_filter']['properties'], [null]);
            if ($request['first_filter']['properties']) {
                $arFilter = [
                    'properties' => $request['first_filter']['properties'],
                    'date_from' => $request['first_filter']['date_from'] ?? null,
                    'date_to' => $request['first_filter']['date_to'] ?? null,
                ];
                $arFirstAnalytic = $this->getAnalytic($arFilter, $wageId, false);
            } else {
                return ['error' => 'Не заполнены параметры первого фильтра'];
            }
        } else {
            return ['error' => 'Не заполнены параметры первого фильтра'];
        }

        if (isset($request['second_filter']) && $request['second_filter']) {
            $request['second_filter']['properties'] = array_diff($request['second_filter']['properties'], [null]);
            if ($request['second_filter']['properties']) {
                $arFilter = [
                    'properties' => $request['second_filter']['properties'],
                    'date_from' => $request['second_filter']['date_from'] ?? null,
                    'date_to' => $request['second_filter']['date_to'] ?? null,
                ];
                $arSecondAnalytic = $this->getAnalytic($arFilter, $wageId, false);
            } else {
                return ['error' => 'Не заполнены параметры второго фильтра'];
            }
        } else {
            return ['error' => 'Не заполнены параметры второго фильтра'];
        }
        if($arFirstAnalytic && $arSecondAnalytic){
            $arStatistic = [$arFirstAnalytic, $arSecondAnalytic];

            if ($arStatistic) {
                uasort($arStatistic, function ($a, $b) {
                    return $a['median'] < $b['median'];
                });
                $first = reset($arStatistic);
                $last = end($arStatistic);
                if ($first['median'] != $last['median']) {
                    $result = sprintf('Медиана "%s" > "%s" на %s р.', $first['name'], $last['name'],
                        number_format($first['median'] - $last['median'], '0', ',', ' '));
                } else {
                    $result = 'Медианы равны';
                }
            }else{
                $result = 'Результаты не найдены';
            }
        }else{
            $result = 'Результаты не найдены';
        }


        return [
            'result' => $result,
            'statistic' => $arStatistic??[]
        ];
    }

    public function getAnalytic($arFilter = [], $wageId = 0, $needItems = true)
    {
        $arRates = $this->getRates();
        if (!$wageId) {
            $wageId = $this->wagePropertyId;
        }

        $wageModel = new Wage();
        $allCount = $wageModel->count();
        $wages = $wageModel->with('properties', 'properties.property');
        if (isset($arFilter['date_from']) && $arFilter['date_from']) {
            $wages = $wages->where('updated_at', '>=',
                Carbon::parse(strtotime($arFilter['date_from']))->format('Y-m-d 00:00:00'));
        }
        if (isset($arFilter['date_to']) && $arFilter['date_to']) {
            $wages = $wages->where('updated_at', '<=',
                Carbon::parse(strtotime($arFilter['date_to']))->format('Y-m-d 23:59:59'));
        }


        if (isset($arFilter['properties']) && $arFilter['properties']) {
            unset($arFilter['_token']);
            $arFilter['properties'] = array_diff($arFilter['properties'], [null]);
            if ($arFilter['properties']) {
                $propertyValues = new WagePropertyValue();
                $cnt = 0;
                foreach ($arFilter['properties'] as $name => $value) {
                    if ($cnt == 0) {
                        $logic = 'where';
                    } else {
                        $logic = 'orWhere';
                    }
                    $propertyValues = $propertyValues->$logic(function ($q) use ($name, $value) {
                        $q->where([
                            'name' => str_replace('_', ' ', $name),
                            'value' => $value
                        ]);
                    });

                    $cnt++;
                }
                $propertyValues = $propertyValues->get()->groupBy('wage_id');
                if ($propertyValues) {

                    foreach ($propertyValues as $propertyWageId => $arItems) {
                        if (count($arItems) == count($arFilter['properties'])) {
                            $arIds[] = $propertyWageId;
                        }
                    }
                }

                if (!isset($arIds) || !$arIds) {
                    $arIds = [null];
                }
                $wages->whereIn('id', $arIds);
            }
        }
        $wages = $wages->get()->each(function ($item) use ($arFilter, $wageId, $arRates) {
            $properties = $item->getRelation('properties');
            $properties->each(function ($property, $key) use ($properties) {
                $mainProperty = $property->getRelation('property');
                if ($mainProperty->parent_id) {
                    $parentProperty = $properties->where('wage_property_id', $mainProperty->parent_id)->first();
                    if ($parentProperty) {
                        $parentProperty->value = sprintf('%s/%s', $parentProperty->value, $property->value);
                    }
                    $properties->forget($key);
                }
            });
            if (isset($arFilter['properties']) && $arFilter['properties']) {
                $item->speciality = implode('/', $arFilter['properties']);
            } else {
                $speciality = $properties->where('wage_property_id', $this->specialityPropertyId)->first();
                if ($speciality) {
                    $item->speciality = $speciality->value;
                }
            }


            $wage = $properties->where('wage_property_id', $wageId)->first();

            if ($wage) {
                $arWage = json_decode($wage->value, true);
                if ($arWage['currency'] != $this->defaultCurrency) {
                    $item->wage = round($arWage['value'] / $arRates[$arWage['currency']], 0);
                } else {
                    $item->wage = round($arWage['value'], 0);
                }
            }
        });
        $groupedWages = $wages->groupBy('speciality');
        $arStatistic = [];
        $arStaticItems = [];
        foreach ($groupedWages as $key => $group) {
            $groupProperties = [];
            foreach ($group as $wage) {
                $properties = $wage->getRelation('properties');
                foreach ($properties as $property) {
                    if (!in_array($property->wage_property_id, $this->excludedFilterProps)
                        && $key != $property->value
                        && (!isset($arFilter['properties'][$property->name]) || !$arFilter['properties'][$property->name])
                    ) {
                        if (!isset($groupProperties[$property->name])) {
                            $groupProperties[$property->name] = [];
                        }
                        if (!isset($groupProperties[$property->name][$property->value])) {
                            $groupProperties[$property->name][$property->value] = 0;
                        }
                        $groupProperties[$property->name][$property->value] += 1;
                    }
                }
            }
            if ($needItems) {
                $arItems = [];

                foreach ($groupProperties as $name => $property) {
                    $arValues = [];
                    foreach ($property as $valueKey => $value) {
                        $arValues[] = [$valueKey, $value];
                    }
                    $arItems[] = [
                        'name' => $name,
                        'items' => $arValues
                    ];
                }
                $arStatistic[] = [
                    'name' => $key,
                    'median' => $this->getPercentile($group->pluck('wage')->toArray(), 0.5),
                    'count' => $group->count(),
                    'percents' => round($group->count() / $allCount * 100, 2),
                    'items' => $arItems
                ];
            } else {
                $arStatistic[] = [
                    'name' => $key,
                    'median' => $this->getPercentile($group->pluck('wage')->toArray(), 0.5),
                    'count' => $group->count(),
                    'percents' => round($group->count() / $allCount * 100, 2),
                ];
            }
        }
        if ($arStatistic) {
            uasort($arStatistic, function ($a, $b) {
                return $a['median'] < $b['median'];
            });
        }
        if ($needItems) {
            foreach ($arStatistic as $key => $statistic) {
                foreach ($statistic['items'] as $itemKey => $item) {
                    $arStaticItems[sprintf('chart_div_%s_%s', $key, $itemKey)] = $item;
                }
            }
            return ['statistic' => $arStatistic, 'items' => $arStaticItems];
        } else {
            return reset($arStatistic);
        }
    }

}
