<?php


namespace App\View\Components\Admin;


use App\Services\SiteSettings;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class AdminMenu extends Component
{
    public $arMenu;

    /**
     * AdminMenu constructor.
     * @param SiteSettings $siteSettings
     */
    public function __construct(SiteSettings $siteSettings)
    {
        $settings = $siteSettings->getSiteSettings();
        $prefix = Route::current()->compiled->getStaticPrefix();
        $expPrefix = array_diff(explode('/', $prefix), array(''));;


        $arTabs = [
            'site-settings' =>
                [
                    'name' => 'Настройки сайта',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.site-settings.edit' => [
                                'name' => 'Настройки сайта',
                                'item' => $settings
                            ],
                        ]
                ],
            'socials' =>
                [
                    'name' => 'Социальные сети',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.socials.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.socials.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'menu-types' =>
                [
                    'name' => 'Типы меню',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.menu-types.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.menu-types.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'menu-groups' =>
                [
                    'name' => 'Группы меню',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.menu-groups.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.menu-groups.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'menu-items' =>
                [
                    'name' => 'Элементы меню',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.menu-items.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.menu-items.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'publications' =>
                [
                    'name' => 'Публикации',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.publications.index' => [
                                'name' => 'Список публикаций'
                            ],
                            'admin.publications.create' => [
                                'name' => 'Добавить публикацию'
                            ],

                        ]
                ],
            'vacancies' =>
                [
                    'name' => 'Вакансии',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.vacancies.index' => [
                                'name' => 'Список вакансий'
                            ],
                            'admin.vacancies.create' => [
                                'name' => 'Добавить вакансию'
                            ],

                        ]
                ],
            'pages' =>
                [
                    'name' => 'Текст в сайдбаре',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.pages.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.pages.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'commerces' =>
                [
                    'name' => 'Реклама',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.commerces.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.commerces.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'sponsors' =>
                [
                    'name' => 'Спонсоры',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.sponsors.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.sponsors.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'header-banners' =>
                [
                    'name' => 'Баннер в шапке',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.header-banners.index' => [
                                'name' => 'Список элементов'
                            ],
                            'admin.header-banners.create' => [
                                'name' => 'Добавить элемент'
                            ],

                        ]
                ],
            'text-pages' =>
                [
                    'name' => 'Текстовые страницы',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.text-pages.index' => [
                                'name' => 'Список страниц'
                            ],
                            'admin.text-pages.create' => [
                                'name' => 'Добавить страницу'
                            ],

                        ]
                ],
            'wage-properties' =>
                [
                    'name' => 'Свойства зарплат',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.wage-properties.index' => [
                                'name' => 'Список свойств'
                            ],
                            'admin.wage-properties.create' => [
                                'name' => 'Добавить свойство'
                            ],

                        ]
                ],
            'wages' =>
                [
                    'name' => 'Зарплаты',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.wages.index' => [
                                'name' => 'Список зарплат'
                            ],
                            'admin.wages.create' => [
                                'name' => 'Добавить зарплату'
                            ],

                        ]
                ],
            'analytic' =>
                [
                    'name' => 'Аналитика',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.analytic' => [
                                'name' => 'Перейти к фильтру'
                            ],
                            'admin.analytic-compare' => [
                                'name' => 'Перейти к сравнению'
                            ],


                        ]
                ],
            'seo' =>
                [
                    'name' => 'SEO',
                    'icon' => 'flaticon-settings-1',
                    'items' =>
                        [
                            'admin.seo.index' => [
                                'name' => 'Редактирование SEO'
                            ],
                            'admin.seo.create' => [
                                'name' => 'Добавить настройки SEO'
                            ],

                        ]
                ],

        ];

        foreach ($arTabs as &$arTab) {
            foreach ($arTab['items'] as $key => &$arItem) {
                if (Route::has($key)) {
                    if (isset($arItem['item']) && $arItem['item']) {
                        $arItem['url'] = route($key, $arItem['item']);
                    } else {
                        $arItem['url'] = route($key);
                    }

                    if (strpos($arItem['url'], $prefix) && count($expPrefix) > 1 || Route::is($key)) {
                        $arItem['current'] = 'Y';
                        $arTab['current'] = 'Y';
                    }
                } else {
                    unset($arTab['items'][$key]);
                }
            }
            unset($arItem);
        }
        unset($arTab);
        $this->arMenu = $arTabs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.admin.menu');
    }
}
