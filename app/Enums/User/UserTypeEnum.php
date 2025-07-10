<?php
namespace App\Enums\User;

enum UserTypeEnum:int {
    case STORE = 1;
    case PHARMACY = 2;
    case ADMIN = 3;
    public static function values(){
         return array_column(self::cases(), 'value');
    }
}
