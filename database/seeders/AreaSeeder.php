<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $areas = [
            ['name' => 'الرياض', 'area_id' => null],
            ['name' => 'مكة المكرمة', 'area_id' => null],
            ['name' => 'المدينة المنورة', 'area_id' => null],
            ['name' => 'القصيم', 'area_id' => null],
            ['name' => 'الشرقية', 'area_id' => null],
            ['name' => 'عسير', 'area_id' => null],
            ['name' => 'تبوك', 'area_id' => null],
            ['name' => 'حائل', 'area_id' => null],
            ['name' => 'الباحة', 'area_id' => null],
            ['name' => 'الجوف', 'area_id' => null],
            ['name' => 'جازان', 'area_id' => null],
            ['name' => 'نجران', 'area_id' => null],
            ['name' => 'الحدود الشمالية', 'area_id' => null],
            ['name' => 'الخرج', 'area_id' => null],
            ['name' => 'المجمعة', 'area_id' => null],
            ['name' => 'ينبع', 'area_id' => null],
            ['name' => 'الطائف', 'area_id' => null],
            ['name' => 'الدمام', 'area_id' => null],
            ['name' => 'الأحساء', 'area_id' => null],
            ['name' => 'القنفذة', 'area_id' => null],
        ];

        foreach ($areas as $area) {
            \App\Models\Area\Area::create($area);
        }
    }
}
