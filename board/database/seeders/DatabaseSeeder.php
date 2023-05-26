<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
      \App\Models\Boards::factory(20)->create(); // 데이터 개수 20개 더미 데이터 만든다는 뜻
    }
}
