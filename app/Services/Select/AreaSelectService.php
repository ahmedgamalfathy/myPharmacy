<?php
namespace App\Services\Select;
use App\Models\Area\Area;
class AreaSelectService
{
    public function getAreas()
    {
        return Area::whereNull('area_id')->get(['id as value','name as label']);
    }
    public function getSubAreas(int $areaId)
    {
        return Area::where('area_id',$areaId)->get(['id as value','name as label']);
    }
    public function getAllSubAreas()  {
        return Area::whereNotNull('area_id')->get(['id as value','name as label']);
    }
}
