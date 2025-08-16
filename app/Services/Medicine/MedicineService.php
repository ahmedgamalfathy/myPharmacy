<?php
namespace App\Services\Medicine;

use Faker\Provider\Medical;
use App\Models\Medicine\Medicine;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use App\Enums\Medicine\LimitedQuantity;
use App\Services\ProductMedia\ProductMediaService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicineService
{
    public  $productMediaService;
    public $uploadService;
    public function __construct(ProductMediaService $productMediaService ,UploadService $uploadService)
    {
        $this->productMediaService =$productMediaService;
        $this->uploadService =$uploadService;
    }
    public function allMedicine(){
        return QueryBuilder::for(Medicine::class)
        ->defaultSort('-created_at')
        ->allowedFilters([
            // AllowedFilter::custom('search', new FilterProduct),
            AllowedFilter::exact('status'),
        ])
        ->orderBy('created_at', 'desc')
        ->get();
    }
    //name ,generic_name(nullable) ,facturer(nullable) ,dosage_form ,strength ,isLimited ,stock ,price ,expired_at ,description
    public function createMedicine(array $data){
        $medicine= Medicine::create([
            'name'=>$data['name'],
            'generic_name'=>$data['genericName']??null,
            'facturer'=>$data['facturer']??null,
            'dosage_form'=>$data['dosageForm'],
            'strength'=>$data['strength'],
            'isLimited'=>LimitedQuantity::from($data['isLimited'])->value,
            'stock'=>$data['stock']??0,
            'price'=>$data['price'],
            'expired_at'=>$data['expiredAt']??null,
            'description'=>$data['description']??null,
            'category_id'=>$data['categoryId']??null,
        ]);

        if (isset($data['productMedia']) && is_array($data['productMedia']) && count($data['productMedia']) > 0) {
            foreach($data['productMedia'] as $media){
                $media['medicineId']=$medicine->id;
                $this->productMediaService->createProductMedia($media);
            }
      }
        return $medicine;
    }
    public function editMedicine(int $id){
        $product= Medicine::find($id);
        if(!$product){
            throw new ModelNotFoundException();
        }
        return $product;
    }
    public function updateMedicine(int $id,array $data){
        $product= Medicine::find($id);
        $product->update([
            'name'=>$data['name'],
            'price'=>$data['price'],
            'status'=> $data['status'],
            'description'=>$data['description']??null,
            'category_id'=>$data['categoryId']??null,
            'sub_category_id'=>$data['subCategoryId']??null,
            'quantity'=>$data['quantity']??0,
            'cost'=>$data['cost']??0,
            "specifications"=>$data["specifications"]??null,
            'is_limited_quantity'=>LimitedQuantity::from($data['isLimitedQuantity'])->value
        ]);
        return $product;
    }
    public function deleteMedicine(int $id){
        $product=Medicine::find($id);
        if(!$product){
           throw new ModelNotFoundException();
        }
        $product->delete();
    }

}
