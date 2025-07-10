<?php
namespace App\Enums;
enum IsActiveEnum : int{
    case INACTIVE = 0;
   case  ACTIVE = 1;

   public static function values(){
    return array_column(self::cases() , 'value');
   }

}
