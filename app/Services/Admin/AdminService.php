<?php


namespace App\Services\Admin;


use App\Models\Language;
use App\Models\MenuType;
use App\Models\WageProperty;
use App\Models\WagePropertyOption;
use App\Services\Salary;
use App\Models\Seo;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class AdminService
{
    private $model,
        $langModel,
        $additionalTabs,
        $prefix;

    public function __construct($model, $langModel = '', $prefix = '', $additionalTabs = [])
    {
        $this->model = $model;
        if ($langModel) {
            $this->langModel = $langModel;
        }
        if ($prefix) {
            $this->prefix = $prefix;
        }
        if ($additionalTabs) {
            $this->additionalTabs = $additionalTabs;
        }
    }

    public function getList()
    {
        $arLinkFields = array_filter($this->model::$listFields, function ($arItem) {
            return isset($arItem['link']) && $arItem['link'] == 'Y';
        });

        $items = $this->model::orderBy('id', 'asc')->get();
        $result = datatables()->of($items)
            ->addColumn('action', function ($item) {
                return sprintf('<a href="%s" data-ajax="true" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Удалить"><i class="la la-remove"></i></a>',
                    route(sprintf('admin.%s.destroy', $this->prefix), $item->id));
            });
        if ($arLinkFields) {
            foreach ($arLinkFields as $field) {
                $result->addColumn($field['data'], function ($item) use ($field) {
                    return sprintf('<a href="%s">%s</a>', route(sprintf('admin.%s.edit', $this->prefix), $item),
                        $item->{$field['data']});
                });
            }
        };
        return $result->rawColumns(array_merge($arLinkFields ? array_column($arLinkFields, 'data') : [], ['action']))
            ->toJson();
    }

    public function getDetail($id)
    {
        if (Request::old()) {
            $oldRequest = Request::old();
        }
        $detailItem = $this->model::where('id', $id)->firstOrFail();

        if ($detailItem->isRelation('languages')) {
            $detailItem->load('languages');
        }
        if ($detailItem->isRelation('user')) {
            $detailItem->load('user');
        }
        $formService = new FormService();
        $arTabs = [
            'general' => [
                'name' => 'Общее',
                'fields' => $formService->getFormFields($this->model::$fields,
                    isset($oldRequest) ? $oldRequest : $detailItem)
            ]
        ];
        if ($this->langModel) {
            Language::all()->each(function ($item) use (&$arTabs, $formService, $detailItem) {
                if (Request::old()) {
                    $oldRequest = Request::old();
                }
                $arTabs[sprintf('lang_%s', $item->id)] = [
                    'name' => $item->name,
                    'fields' => $formService->getFormFields($this->langModel::$fields,
                        isset($oldRequest['lang'][$item->id]) ? $oldRequest['lang'][$item->id] : $detailItem->getRelation('languages')->where('lang_id',
                            $item->id)->first(), 'lang',
                        $item->id)
                ];
            });
        }
        $arAdditionalTabs = $this->getAdditionalTabs($detailItem);
        if ($arAdditionalTabs) {
            foreach ($arAdditionalTabs as $additionalTab) {
                if (isset($additionalTab['relation'])) {
                    $detailItem->load($additionalTab['relation']);
                    if(isset($oldRequest[$additionalTab['relation']])){
                        $additionalTab['value'] = $oldRequest[$additionalTab['relation']];
                    }else{
                        $additionalTab['value'] = $detailItem->getRelation($additionalTab['relation']);
                    }

                    if (isset($additionalTab['sort']) && $additionalTab['sort']) {
                        $additionalTab['value']->sortBy($additionalTab['sort']);
                    }
                    $arTabs[$additionalTab['relation']] = [
                        'name' => $additionalTab['name'],
                        'fields' => $this->getAdditionalTab($additionalTab)
                    ];
                }

                if ($additionalTab['type'] == 'seo') {
                    $arTabs['seo'] = [
                        'name' => $additionalTab['name'],
                        'fields' => $this->getAdditionalTab($additionalTab, $detailItem)
                    ];
                }


            }
        }

        return [
            'item' => $detailItem,
            'tabs' => $arTabs
        ];
    }

    private function getAdditionalTab($additionalTab, $detailItem = null)
    {
        $html = '';
        $formService = new FormService();
        switch ($additionalTab['type']) {
            case 'elementList':
                $inputHtml = '';
                $arFieldsHtml = [];
                $arHeader = [];
                $arSavedFields = [];
                if (isset($additionalTab['value']) && $additionalTab['value']) {
                    foreach ($additionalTab['value'] as $value) {
                        $arHtml = [];
                        foreach ($additionalTab['model']::$linkFields as $key => $arField) {
                            $arField['value'] = $value->{$key};
                            $arHtml[] = sprintf('<td>%s</td>', $formService->getField($arField, $key, $key));
                        }
                        $arSavedFields[] = sprintf('<tr data-repeater-item>
                                                        <input type="hidden" name="id" value="%2$s">
														 %1$s
														<td>
															<div data-repeater-delete="" class="btn-sm btn btn-danger m-btn m-btn--icon m-btn--pill">
																<span>
																	<i class="la la-trash-o"></i>
																	<span>
																		Удалить
																	</span>
																</span>
															</div>
														</td>
													</tr>', implode('', $arHtml), $value->id);
                    }
                }
                foreach ($additionalTab['model']::$linkFields as $key => $arField) {
                    $arHeader[] = sprintf('<th>%s</th>', $arField['label']);
                    $arFieldsHtml[] = sprintf('<td>%s</td>', $formService->getField($arField, $key, $key));
                }
                $arHeader[] = sprintf('<th>%s</th>', 'Действие');

                $inputHtml .= sprintf('<tr data-repeater-item>
														 %1$s
														<td>
															<div data-repeater-delete="" class="btn-sm btn btn-danger m-btn m-btn--icon m-btn--pill">
																<span>
																	<i class="la la-trash-o"></i>
																	<span>
																		Удалить
																	</span>
																</span>
															</div>
														</td>
													</tr>', implode('', $arFieldsHtml));
                $html = sprintf('<table class="repeater_block tq_relation_table" width="100%%">
                                    <thead><tr>%3$s</tr></thead>
                                    <tbody data-repeater-list="%2$s">%4$s%1$s</tbody>
                                    <tfoot><tr>
                                        <td data-repeater-create="" class="btn btn btn-sm btn-brand m-btn m-btn--icon m-btn--pill m-btn--wide">
                                            <span><i class="la la-plus"></i><span>Добавить</span></span>
                                        </td>
                                    </tr></tfoot>
										</table>', $inputHtml, $additionalTab['relation'], implode('', $arHeader),
                    implode('', $arSavedFields));


                break;
            case 'propertyList':
                $arFieldsHtml = [];
                $arHeader = [];
                $properties = WageProperty::orderBy('sort', 'asc')->with('options')->get();
                $salaryService = new Salary();
                foreach ($properties as $property) {

                    if(is_array($additionalTab['value'])){
                        $value = new $additionalTab['model']($additionalTab['value'][$property->id]);
                    }else{
                        $value = $additionalTab['value']->where('wage_property_id', $property->id)->first();
                    }
                    $field = '';
                    switch ($property->type) {
                        case 'currency':
                            if(isset($value->value) && $value->value && is_array($value->value)){
                                $arValue = $value->value;
                            }else{
                                $arValue = json_decode($value->value??'', true);
                            }

                            $key = sprintf('%s[%s][value][value]', $additionalTab['relation'], $property->id);
                            $arField = [
                                'label' => 'Значение',
                                'type' => 'number',
                                'value' => $arValue['value'] ?? ''
                            ];
                            $priceField = $formService->getField($arField, $key, $key);
                            $arList = [];
                            foreach ($salaryService->arCurrencies as $key => $currency) {
                                $arList[] = [
                                    'name' => $currency,
                                    'id' => $currency,
                                ];
                            }
                            $arField = [
                                'label' => 'Значение',
                                'type' => 'list',
                                'values' => $arList,
                                'value' => $arValue['currency'] ?? ''
                            ];
                            $key = sprintf('%s[%s][value][currency]', $additionalTab['relation'], $property->id);
                            $field = $formService->getField($arField, $key, $key);
                            $field = sprintf('<span class="currency">%s%s</span>', $priceField, $field);
                            break;
                        case 'location':
                            $key = sprintf('%s[%s][value]', $additionalTab['relation'], $property->id);
                            $arField = [
                                'label' => 'Значение',
                                'type' => 'text',
                                'value' => $value->value??''
                            ];
                            $field = $formService->getField($arField, $key, $key);
                            break;
                        case 'list':
                            $key = sprintf('%s[%s][wage_property_option_id]', $additionalTab['relation'],
                                $property->id);
                            $arValues = $property->getRelation('options')->each(function ($item) {
                                $item->name = $item->value;
                            })->toArray();
                            $arField = [
                                'label' => 'Значение',
                                'type' => 'list',
                                'values' => $arValues,
                                'value' => $value->wage_property_option_id??''
                            ];
                            $field = $formService->getField($arField, $key, $key);
                            break;
                    }
                    $key = sprintf('%s[%s][id]', $additionalTab['relation'], $property->id);
                    $field .= sprintf('<input type="hidden" value="%s" name="%s">', $value->id??'', $key);

                    $arFieldsHtml[] = sprintf('<tr>
                                                <td style="text-align: left">%s</td>
                                                <td>%s</td>
                                                </tr>', $property->name, $field);

                }
                $arHeaderItems = [
                    'Свойство',
                    'Значение'
                ];
                foreach ($arHeaderItems as $title) {
                    $arHeader[] = sprintf('<th>%s</th>', $title);
                }

                $html = sprintf('<table class="tq_relation_table"  width="100%%">
                                    <thead><tr>%2$s</tr></thead>
                                    <tbody>%1$s</tbody>
										</table>', implode('', $arFieldsHtml), implode('', $arHeader));


                break;
            case 'seo':
                if (!empty($detailItem) && $detailItem->slug) {
                    $seo = Seo::where('link', '=', Route($additionalTab['route'], $detailItem->slug, false))->first();
                }
                $arFieldsHtml = [
                    $formService->getField([
                        'type' => 'text',
                        'label' => 'Заголовок',
                        'value' => $seo->title ?? ''
                    ], 'seo_title', 'seo_title'),
                    $formService->getField([
                        'type' => 'textarea',
                        'label' => 'Описание',
                        'value' => $seo->description ?? ''
                    ], 'seo_description', 'seo_description'),
                    $formService->getField([
                        'type' => 'textarea',
                        'label' => 'Ключевые слова',
                        'value' => $seo->keywords ?? ''
                    ], 'seo_keywords', 'seo_keywords')
                ];

                $html = sprintf('<table class="repeater_block" width="100%%">
                                    <thead></thead>
                                    <tbody>%1$s</tbody>
                                    <tfoot
                                    ><tr></tr>
                                    </tfoot>
										</table>', implode('', $arFieldsHtml));
                break;
        }
        return [$html];
    }

    public function getCreate($seo = false, $route = null)
    {
        $formService = new FormService();
        $arTabs = [
            'general' => [
                'name' => 'Общее',
                'fields' => $formService->getFormFields($this->model::$fields, Request::old())
            ]
        ];
        if ($seo && !empty($route)) {
            $arTabs['seo'] = [
                'name' => 'SEO',
                'fields' => $this->getAdditionalTab([
                    'name' => 'SEO',
                    'type' => 'seo',
                    'route' => $route
                ])
            ];
        }
        if ($this->langModel) {
            Language::all()->each(function ($item) use (&$arTabs, $formService) {
                $arTabs[sprintf('lang_%s', $item->id)] = [
                    'name' => $item->name,
                    'fields' => $formService->getFormFields($this->langModel::$fields,
                        Request::old(sprintf('lang.%s', $item->id)), 'lang', $item->id)
                ];
            });
        }
        $detailItem = new $this->model();
        $arAdditionalTabs = $this->getAdditionalTabs($detailItem);
        if ($arAdditionalTabs) {
            foreach ($arAdditionalTabs as $additionalTab) {
                if (isset($additionalTab['relation'])) {
                    $detailItem->load($additionalTab['relation']);
                    if(isset($oldRequest[$additionalTab['relation']])){
                        $additionalTab['value'] = $oldRequest[$additionalTab['relation']];
                    }else{
                        $additionalTab['value'] = $detailItem->getRelation($additionalTab['relation']);
                    }

                    if (isset($additionalTab['sort']) && $additionalTab['sort']) {
                        $additionalTab['value']->sortBy($additionalTab['sort']);
                    }
                    $arTabs[$additionalTab['relation']] = [
                        'name' => $additionalTab['name'],
                        'fields' => $this->getAdditionalTab($additionalTab)
                    ];
                }

                if ($additionalTab['type'] == 'seo') {
                    $arTabs['seo'] = [
                        'name' => $additionalTab['name'],
                        'fields' => $this->getAdditionalTab($additionalTab, $detailItem)
                    ];
                }


            }
        }

        return $arTabs;
    }

    public function addElement($arData, $route = null, $seoData = [])
    {
        $this->prepareData($arData, $this->model);
        $item = $this->model::create($arData);
        if (isset($arData['lang']) && $arData['lang'] && $item && $this->langModel) {
            foreach ($arData['lang'] as $langId => $data) {
                if (array_diff(array_values($data), [null])) {
                    $this->prepareData($data, $this->langModel, $langId);
                    $item->languages()->save(new $this->langModel($data));
                }
            }
        }
        $arAdditionalTabs = $this->getAdditionalTabs($item);
        if ($arAdditionalTabs) {
            foreach ($arAdditionalTabs as $additionalTab) {
                if (isset($additionalTab['relation'])) {
                    if ($item->isRelation($additionalTab['relation'])) {
                        $item->load($additionalTab['relation']);
                        $arIds = [];
                        $relationItems = $item->getRelation($additionalTab['relation']);
                        if (isset($arData[$additionalTab['relation']]) && $arData[$additionalTab['relation']]) {
                            $arIds = array_column($arData[$additionalTab['relation']], 'id');

                            foreach ($arData[$additionalTab['relation']] as $propertyId => $data) {
                                if (isset($data['value']) && is_array($data['value'])) {
                                    $data['value'] = json_encode($data['value']);
                                }
                                if (isset($data['wage_property_option_id']) && $data['wage_property_option_id']) {
                                    $data['value'] = WagePropertyOption::find($data['wage_property_option_id'])->value;
                                }
                                if (array_diff(array_values($data), [null])) {

                                    if (isset($data['id']) && $data['id']) {
                                        $relationItem = $relationItems->find($data['id']);
                                        $relationItem->update($data);
                                        $item->{$additionalTab['relation']}()->save($relationItem);
                                    } else {
                                        $data['wage_property_id'] = $propertyId;
                                        $propItem = $item->{$additionalTab['relation']}()->save(new $additionalTab['model'] ($data));
                                        $arIds[] = $propItem->id;
                                    }
                                }
                            }
                        }
                        if ($relationItems) {
                            $arIds = array_diff($arIds,[null]);
                            $deleteItems = $relationItems->whereNotIn('id', $arIds);
                            if ($deleteItems) {
                                $deleteItems->each(function ($item) {
                                    $item->delete();
                                });

                            }
                        }
                    }
                }
            }
        }
        if (!empty($route) && !empty($seoData)) {
            $link = Route($route, $item->slug, false);

            $this->createSeoElement($link, $seoData['seo_title'], $seoData['seo_description'],
                $seoData['seo_keywords']);
        }
    }

    public function updateElement($id, $arData, $route = null, $seoData = [])
    {
        $item = $this->model::find($id);
        if ($item) {
            $this->prepareData($arData, $this->model);
            $item->update($arData);
            if (isset($arData['lang']) && $arData['lang'] && $this->langModel) {
                $item->load('languages');
                foreach ($arData['lang'] as $langId => $data) {
                    if (array_diff(array_values($data), [null])) {
                        $this->prepareData($data, $this->langModel, $langId);
                        if ($langItem = $item->getRelation('languages')->where('lang_id', $langId)->first()) {
                            $langItem->update($data);
                        } else {
                            $langItem = new $this->langModel($data);
                        }
                        $item->languages()->save($langItem);
                    }
                }
            }
            $arAdditionalTabs = $this->getAdditionalTabs($item);
            if ($arAdditionalTabs) {
                foreach ($arAdditionalTabs as $additionalTab) {
                    if (isset($additionalTab['relation'])) {
                        if ($item->isRelation($additionalTab['relation'])) {
                            $item->load($additionalTab['relation']);
                            $arIds = [];
                            $relationItems = $item->getRelation($additionalTab['relation']);
                            if (isset($arData[$additionalTab['relation']]) && $arData[$additionalTab['relation']]) {
                                $arIds = array_column($arData[$additionalTab['relation']], 'id');

                                foreach ($arData[$additionalTab['relation']] as $propertyId => $data) {
                                    if (isset($data['value']) && is_array($data['value'])) {
                                        $data['value'] = json_encode($data['value']);
                                    }
                                    if (isset($data['wage_property_option_id']) && $data['wage_property_option_id']) {
                                        $data['value'] = WagePropertyOption::find($data['wage_property_option_id'])->value;
                                    }
                                    if (array_diff(array_values($data), [null])) {

                                        if (isset($data['id']) && $data['id']) {
                                            $relationItem = $relationItems->find($data['id']);
                                            $relationItem->update($data);
                                            $item->{$additionalTab['relation']}()->save($relationItem);
                                        } else {
                                            $data['wage_property_id'] = $propertyId;
                                            $propItem = $item->{$additionalTab['relation']}()->save(new $additionalTab['model'] ($data));
                                            $arIds[] = $propItem->id;
                                        }
                                    }
                                }
                            }
                            if ($relationItems) {
                                $arIds = array_diff($arIds,[null]);
                                $deleteItems = $relationItems->whereNotIn('id', $arIds);
                                if ($deleteItems) {
                                    $deleteItems->each(function ($item) {
                                        $item->delete();
                                    });

                                }
                            }
                        }
                    }
                }
            }

            if (!empty($route) && !empty($seoData)) {
                $link = Route($route, $item->slug, false);
                $this->createSeoElement($link, $seoData['seo_title']??'', $seoData['seo_description']??'',
                    $seoData['seo_keywords']??'');
            }
        }
    }

    private function getAdditionalTabs($item)
    {
        $arTabs = [];
        if ($this->additionalTabs) {
            foreach ($this->additionalTabs as $arTab) {
                if (isset($arTab['conditions']) && $arTab['conditions']) {
                    $passed = 0;
                    foreach ($arTab['conditions'] as $key => $condition) {
                        if (in_array($item->{$key}, $condition)) {
                            $passed++;
                        }
                    }
                    if ($passed == count($arTab['conditions'])) {
                        $arTabs[] = $arTab;
                    }
                } else {
                    $arTabs[] = $arTab;
                }
            }

        }

        return $arTabs;
    }

    private function createSeoElement($link, $title, $description, $keywords)
    {
        if($link && $title)
        Seo::updateOrCreate(
            [
                'link' => $link
            ],
            [
                'name' => $title,
                'title' => $title,
                'description' => $description,
                'keywords' => $keywords
            ]
        );
    }

    private function prepareData(&$arData, $model, $langId = 0)
    {
        if ($langId) {
            $arData['lang_id'] = $langId;
        }
        $arData['sort'] = $arData['sort'] ?? 500;

        foreach ($model::$fields as $key => $arField) {
            switch ($arField['type']) {
                case 'file':
                    if (isset($arData[$key]) && $arData[$key] && is_file($arData[$key])) {
                        $path = sprintf('images/%s_%s', time(), $arData[$key]->getClientOriginalName());
                        if (Storage::disk('public')->put($path, $arData[$key]->getContent())) {
                            $arData[$key] = Storage::url($path);
                        }
                    } elseif (!isset($arData[sprintf('%s_old', $key)]) || !$arData[sprintf('%s_old', $key)]) {
                        $arData[$key] = null;
                    }
                    break;
                case 'boolean':
                    if (isset($arData[$key]) && $arData[$key]) {
                        $arData[$key] = 1;
                    } else {
                        $arData[$key] = 0;
                    }
                    break;
            }
        }

        unset($data);
    }

    public function delete($id,$route = null)
    {
        $item = $this->model::find($id);
        if ($item) {
            if($route){
                $link = Route($route, $item->slug, false);
                $seo = Seo::where('link',$link);
                if($seo){
                    $seo->delete();
                }
            }
            $item->delete();
        }
    }
}
