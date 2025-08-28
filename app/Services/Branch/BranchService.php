<?php

namespace App\Services\Branch;

use App\Enums\IsActiveEnum;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\Category\FilterCategory;
use App\Models\Branch\Branch;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchService{


    public function allBranches()
    {
        $branches = QueryBuilder::for(Branch::class)
        ->allowedFilters([
            AllowedFilter::custom('search', new FilterCategory()),
            AllowedFilter::exact('isActive', 'is_active')
        ])
        ->get();

        return $branches;

    }

    public function createBranch(array $branchData): Branch
    {
      //name , location , address , status
        $branch = Branch::create([
            'name' => $branchData['name'],
            'area_id' => $branchData['areaId'],
            'user_id' => $branchData['user_id'], // استخدم الـ user_id من المصفوفة
        ]);
        return $branch;

    }

    public function editBranch(int $branchId)
    {
       $branch= Branch::findOrFail($branchId);
        if(!$branch){
           throw new ModelNotFoundException();
        }
        return $branch;
    }

    public function updateBranch(int $id,array $branchData)
    {
      //name , location , address , status
        $branch = Branch::find($id);
        $branch->name = $branchData['name'];
        $branch->area_id = $branchData['areaId'];
        $branch->user_id = $branchData['user_id'];
        $branch->save();

        return $branch;

    }


    public function deleteBranch(int $branchId)
    {
        $branch = Branch::find($branchId);
        if (!$branch) {
            throw new ModelNotFoundException();
        }
        $branch->delete();
    }

}
