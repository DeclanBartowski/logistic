<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        return [
            'name'=>$name,
            'slug'=>Str::slug($name),
            'tag'=>$this->faker->text(50),
            'active_from'=>$this->faker->dateTimeBetween('-1 year'),
            'active_to'=>$this->faker->dateTimeBetween('-1 year','+1 year'),
            'active'=>$this->faker->boolean(),
            'link'=>$this->faker->url(),
            'preview_picture'=>'img/blog.png',
            'preview_text'=>$this->faker->realText(150),
            'detail_text'=>'
					<p><span>Компания «АЙ-ТЕКО»</span> — ведущий российский системный интегратор и поставщик информационных технологий для корпоративных заказчиков. Активно действует на рынке IT России с 1997 года, входит в ТОП-400 крупнейших российских компаний, ТОП-10 крупнейших IT-компаний России. <br>В связи с активным развитием внутренних проектов в компании открыта вакансия АНАЛИТИК ДАННЫХ.</p>
					<h2>Обязанности:</h2>
					<ul>
						<li>Проведение анализа данных в части поведения клиента при использовании физической сети.</li>
						<li>Банка (отделения, банкоматы), построение гипотез, определение ключевых признаков в данных, грамотное формирование выводов и визуализация результатов.</li>
						<li>Поиск, сбор, группировка и обработка данных из внешних и внутренних источников для выполнения поставленных задач.</li>
						<li>Определение зависимостей доходности клиентов от размера, доступности и функциональности физической сети банка.</li>
						<li>Автоматизация аналитической отчетности, разработка алгоритмических и математических решений для анализа крупных наборов данных.</li>
						<li>Документирование процессов обработки данных.</li>
						<li>Взаимодействие с заказчиками, внутренними подразделениями.</li>
					</ul>
					<h2>Требования:</h2>
					<ul>
						<li>Высшее техническое образование (желательно прикладная математика, анализ данных).</li>
						<li>Уверенное владение SQL: сложные запросы, аналитические функции, понимание физической реализации join’ов, оптимизация производительности запросов, хранимые процедуры и функции.</li>
						<li>Знание Python, опыт работы с библиотеками для анализа данных.</li>
						<li>Jira, Confluence.</li>
						<li>Понимание принципов анализа данных, проверки гипотез, поиска взаимосвязей и интерпретации результатов в бизнес-логике.</li>
						<li>Умение работать со статистическими приложениями и программами визуализации данных.</li>
						<li>Веб-аналитика: Яндекс Метрика, Google Analytics.</li>
					</ul>
					<h2>Мы предлагаем:</h2>
					<ul>
						<li>Работу в стабильной компании, "белую" заработную плату</li>
						<li>Оформление в соответствии с ТК РФ с первого дня работы</li>
						<li>Расширенный социальный пакет: ДМС (включая стоматологию), корпоративные скидки на посещение фитнес-клубов, футбольная и волейбольная секции</li>
					</ul>'
        ];
    }
}
