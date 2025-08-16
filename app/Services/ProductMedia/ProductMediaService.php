<?php
namespace App\Services\ProductMedia;

use App\Helpers\ApiResponse;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductMedia\ProductMedia;
use App\Enums\ResponseCode\HttpStatusCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProductMediaService{
    public  $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService =$uploadService;
    }
    public function allProductMedia(int $medicineId){
        return ProductMedia::where('medicine_id',$medicineId)->get();
    }
    public function createProductMedia(array $data){
    $path=null;
    if(isset($data['path'])){
    $path = $this->uploadService->uploadFile($data['path'], 'media');
    }
    return ProductMedia::create([
        'path'=>$path,
        'is_main'=>$data['isMain'],
        'medicine_id'=>$data['medicineId'],
        ]);
    }
    public function editProductMedia(int $id){
        return ProductMedia::findOrFail($id);
    }

    public function updateProductMedia(int $id,array $data){
        $productMedia=ProductMedia::findOrFail($id);
        if(!$productMedia){
           return ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        }
        $path = null;
        if(isset($data['path'])){
            Storage::disk('public')->delete($productMedia->path);
            $path = $this->uploadService->uploadFile($data['path'], 'media');
        }
        $productMedia->update([
            'path'=>$path,
            'is_main'=>$data['isMain'],
            'medicine_id'=>$data['medicineId'],
        ]);
        return $productMedia;
    }

    public function deleteProductMedia(int $id){
        $productMedia=ProductMedia::find($id);
        if(!$productMedia){
            throw new ModelNotFoundException();
        }
        Storage::disk('public')->delete($productMedia->getRawOriginal('path'));
        $productMedia->delete();
    }
    public function changeStatusProductMedia($id ,$statusMain){
         $productMedia = ProductMedia::findOrFail($id);
         if($productMedia && $statusMain == 1 ){
            ProductMedia::where('medicine_id', $productMedia->medicine_id)->update([
                'is_main'=>0,
            ]);
            $productMedia->is_main = $statusMain;
            $productMedia->save();
         }elseif ($productMedia && $statusMain == 0) {
            $productMedia->is_main = $statusMain;
            $productMedia->save();
         }
         return $productMedia;
    }
}

