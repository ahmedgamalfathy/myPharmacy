<?php
namespace App\Enums\Medicine;
enum DosageForm :int{
    case TABLETS = 1;//اقراص
    case CAPSULES = 2;//كبسولات
    case SYRUP = 3;//شراب
    case OINTMENT = 4;//مرهم
    case INJECTION = 5;//حقن
    case POWDER = 6;//مسحوق
    case CREAM = 7;//كريم
    case SOLUTION = 8;//محلول
    case SUSPENSION = 9;//معلق
    case DROPS = 10;//قطرات
    case OTHER = 11;//اخرى
    public static function values(): array
{
    return array_column(self::cases(), 'value');
}
}


