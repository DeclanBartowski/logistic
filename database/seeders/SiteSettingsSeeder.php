<?php

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteSettings::updateOrCreate(['logo' => 'img/logo.svg'], [
            'logo_auth'=>'img/logo2.svg',
            'texts'=>[
                'main_form_title'=>'Фактическая зарплата в логистике',
                'main_form_top_text'=>'По всем должностям',
                'main_form_salary'=>'Медианная зарплата на основании {form_quantity}, за 2021 год',
                'main_form_salary_error'=>'Невозможно посчитать зарплату на основании {form_quantity}, за 2021 год',
                'main_form_salary_bottom'=>'Мы только запустились, идет добавление данных и формирование статистики...',
                'filter_hint'=>'<ul>
                        <li>Вы можете узнать зарплаты по нескольким или одному параметру.</li>
                        <li>Выдача результатов за год, но так как мы только запустились, то выдача результатов будет с момента введения первой зарплаты.</li>
                        <li>Если хотите узнать зарплаты любых специалистов, например, со знанием китайского без выбора специализации, тогда просто выбирайте в поле иностранный язык «китайский язык».</li>
                        <li>Хотим напомнить, что проект только запустился, поэтому данных пока может быть недостаточно.</li>
                    </ul>'
                ]
        ]);
    }
}
