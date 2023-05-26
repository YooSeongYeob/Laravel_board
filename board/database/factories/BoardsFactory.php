<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\boards>
 */
class BoardsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-1 years');
        return [
            'title'        => $this->faker->realText(30)
            ,'content'     => $this->faker->realText(2000)
            ,'hits'        => $this->faker->randomNumber(3)
            ,'created_at'  => $date
            ,'updated_at'  => $date
            ,'deleted_at'   => $this->faker->randomNumber(1) <=5 ? $date : null // 1자리 수 가져온다는 말 5보다 작을 때는 데이터를 넣고 아닐 때는 널 값 설정
        ];
    }
}

// 많은 양의 데이터는 시더로 넣어야 함