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
            'location'=>$branchData['location'],
            'address'=>$branchData['address'],
            'status' => IsActiveEnum::from($branchData['status'])->value
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
        $branch->location = $branchData['location']??null;
        $branch->address = $branchData['address'];
        $branch->status = IsActiveEnum::from($branchData['status'])->value;
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
