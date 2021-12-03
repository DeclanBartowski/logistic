<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use App\Models\MenuType;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arTypes = [
            [
                'name' => 'Верхнее меню',
                'slug' => 'top',
                'items' => [
                    [
                        'name' => 'Главная',
                        'link' => '/'
                    ],
                    [
                        'name' => 'О сервисе',
                        'link' => '/about'
                    ],
                    [
                        'name' => 'Публикации',
                        'link' => '/blog'
                    ],
                    [
                        'name' => 'Вакансии',
                        'link' => '/vacancies'
                    ],
                    [
                        'name' => 'Рекламодателю',
                        'link' => '/commerce'
                    ],
                ]
            ],
            [
                'name' => 'Нижнее меню',
                'slug' => 'bottom',
                'groups' => [
                    [
                        'name' => 'Главная',
                    ],
                    [
                        'name' => 'О сервисе',
                    ]
                ]
            ]
        ];
        foreach ($arTypes as $arType) {
            $type = MenuType::updateOrCreate(
                ['slug' => $arType['slug']],
                ['name' => $arType['name']]);
            if ($type && isset($arType['items'])) {
                foreach ($arType['items'] as $item) {
                    MenuItem::updateOrCreate(
                        [
                            'name' => $item['name'],
                        ],
                        [
                            'link' => $item['link'],
                            'menu_type_id' => $type->id
                        ]
                    );
                }
            } elseif ($type && isset($arType['groups'])) {
                foreach ($arType['groups'] as $item) {
                    $group = MenuGroup::updateOrCreate(['name' => $item['name']]);
                    for ($i = 1; $i <= 4; $i++) {
                        MenuItem::updateOrCreate(
                            [
                                'name' => sprintf('Ссылка %s-%s', $group->id, $i),
                            ],
                            [
                                'link' => '/',
                                'menu_type_id' => $type->id,
                                'menu_group_id' => $group->id
                            ]
                        );
                    }
                }
            }
        }
    }
}
