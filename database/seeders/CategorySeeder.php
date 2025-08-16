<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
            'name' => 'أدوية',
            'description' => 'جميع أنواع الأدوية الطبية',
            'status' => 1,
            'image' => 'medicine.jpg',
            ],
            [
            'name' => 'مستلزمات طبية',
            'description' => 'معدات وأدوات طبية متنوعة',
            'status' => 1,
            'image' => 'medical_supplies.jpg',
            ],
            [
            'name' => 'مكملات غذائية',
            'description' => 'مكملات وفيتامينات غذائية',
            'status' => 1,
            'image' => 'supplements.jpg',
            ],
            [
            'name' => 'العناية بالشعر',
            'description' => 'منتجات العناية بالشعر',
            'status' => 1,
            'image' => 'hair_care.jpg',
            ],
            [
            'name' => 'أجهزة قياس',
            'description' => 'أجهزة قياس الضغط والسكر وغيرها',
            'status' => 1,
            'image' => 'devices.jpg',
            ],
            [
            'name' => 'منتجات الأطفال',
            'description' => 'منتجات العناية بالأطفال والرضع',
            'status' => 1,
            'image' => 'baby_products.jpg',
            ],
            [
            'name' => 'العناية بالفم والأسنان',
            'description' => 'منتجات العناية بالفم والأسنان',
            'status' => 1,
            'image' => 'oral_care.jpg',
            ],
            [
            'name' => 'العناية بالجسم',
            'description' => 'منتجات العناية بالجسم',
            'status' => 1,
            'image' => 'body_care.jpg',
            ],
            [
            'name' => 'منتجات النظافة الشخصية',
            'description' => 'منتجات النظافة الشخصية',
            'status' => 1,
            'image' => 'personal_hygiene.jpg',
            ],
        ]);
    }
}
