<?php

namespace App\Http\Resources\Product;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Branch\Branch;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\ProductMedia\ProductMediaResouce;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'status' => $this->status,
            'cost' => $this->cost??"",
            'qty'=>$this->quantity??"",
            'isLimitedQuantity'=>$this->is_limited_quantity,
            'description' => $this->description??"",
            "categoryId" => $this->category_id??"",
            "subCategoryId"=> $this->sub_category_id??"",
            "specifications"=>$this->specifications??"",
            "userName"=>User::find($this->user_id)->name,
            "branchName"=>Branch::find($this->branch_id)->name,
           'productMedia' =>$this->productMedia->isNotEmpty()? ProductMediaResouce::collection($this->productMedia) :url("storage/".'ProductMedia/default-product.jpg') ,

        ];
    }
}
