<?php
namespace App\Services\Select;
use App\Models\Branch\Branch;
class BranchSelectService
{
    public function getBranches()
    {
        return Branch::get(['id as value','name as label']);
    }
    public function getAreaBranches(int $areaId)
    {
        return Branch::where('area_id',$areaId)->get(['id as value','name as label']);
    }
    public function getAllBranchesUser(int $userId)  {
        return Branch::where('user_id' ,$userId)->get(['id as value','name as label']);
    }
}
