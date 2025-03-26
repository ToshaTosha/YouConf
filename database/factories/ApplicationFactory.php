<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Application;
use App\Models\Status;
use App\Models\Section;
use App\Models\Chat;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        return [
            'title' => $this->generateRandomTitle(),
            'description' => $this->generateRandomDescription(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'status_id' => Status::inRandomOrder()->first()->id,
            'section_id' => Section::inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Application $application) {
            // Создаем чат для заявки
            Chat::create([
                'chatable_type' => Application::class,
                'chatable_id' => $application->id,
            ]);
        });
    }

    private function generateRandomTitle()
    {
        $titles = [
            'Исследование влияния света на растения',
            'Разработка нового метода очистки воды',
            'Анализ данных о климатических изменениях',
            'Создание модели солнечной системы',
            'Изучение микробов в почве',
            'Влияние физики на современные технологии',
            'Экологические проблемы и их решения',
            'Генетические исследования в медицине',
            'Космические исследования и их значение',
            'Физика и искусственный интеллект: новые горизонты'
        ];

        return $this->faker->randomElement($titles);
    }

    private function generateRandomDescription()
    {
        $descriptions = [
            'В данной заявке рассматривается влияние света на фотосинтез растений и его значение для экосистемы.',
            'Предлагается новый метод очистки воды с использованием природных фильтров и его эффективность.',
            'Анализ данных о климатических изменениях за последние 50 лет и их влияние на биосферу.',
            'Создание модели солнечной системы с использованием современных технологий и методов.',
            'Изучение микробов в почве и их роль в поддержании здоровья экосистемы.',
            'Обсуждение влияния физических законов на развитие технологий и их применение в жизни.',
            'Анализ экологических проблем, с которыми сталкивается современное общество, и возможные решения.',
            'Исследование генетических факторов, влияющих на здоровье человека и методы их анализа.',
            'Обсуждение значимости космических исследований для науки и общества в целом.',
            'Изучение взаимодействия физики и искусственного интеллекта в современных технологиях.'
        ];

        return $this->faker->randomElement($descriptions);
    }
}
