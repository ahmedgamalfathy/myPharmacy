<?php

namespace App\Services\Category;

use App\Enums\IsActiveEnum;
use App\Models\Category\Category;
use App\Enums\Product\CategoryStatus;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use App\Filters\Category\FilterCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService{

    protected $uploadService;

    public function __construct(UploadService $uploadService, )
    {
        $this->uploadService = $uploadService;
    }

    public function allCategories()
    {
        $categories = QueryBuilder::for(Category::class)
        ->allowedFilters([
            AllowedFilter::custom('search', new FilterCategory()),
            AllowedFilter::exact('isActive', 'is_active')
        ])
        ->get();

        return $categories;

    }

    public function createCategory(array $categoryData): Category
    {
//name , description, status,image
        $path = isset($categoryData['path'])? $this->uploadService->uploadFile($categoryData['path'], 'categories'):null;

        $category = Category::create([
            'name' => $categoryData['name'],
            'description'=>$categoryData['description'],
            'status' => IsActiveEnum::from($categoryData['status'])->value,
            'image' => $path,
        ]);

        return $category;

    }

    public function editCategory(int $categoryId)
    {
       $category= Category::findOrFail($categoryId);
        if(!$category){
           throw new ModelNotFoundException();
        }
        return $category;
    }

    public function updateCategory(int $id,array $categoryData)
    {

        $path = null;

        $category = Category::find($id);
        if(isset($categoryData['path'])){
            $path = $this->uploadService->uploadFile($categoryData['path'], 'categories');
            if($category->image){
                Storage::disk('public')->delete($category->getRawOriginal('image'));
            }
            $category->image = $path;
        }
        $category->description = $categoryData['description']??null;
        $category->name = $categoryData['name'];
        $category->status = IsActiveEnum::from($categoryData['status'])->value;
        $category->save();

        return $category;

    }


    public function deleteCategory(int $categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            throw new ModelNotFoundException();
        }
        $category->delete();
    }

}
