<?php

namespace App\Http\Resources\Product\Website;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Branch\Branch;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\ProductMedia\ProductMediaResouce;
use App\Http\Resources\Product\Website\AllProductResource;

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
            'price' => number_format($this->price, 2, '.', ''),
            'status' => $this->status,
            'description' => $this->description??"",
            "categoryId" => $this->category_id??"",
            "subCategoryId"=> $this->sub_category_id??"",
            "specifications"=> $this->specifications??"",
            "userName"=>User::find($this->user_id)->name,
            "branchName"=>Branch::find($this->branch_id)->name,
            "stock"=> ($this->quantity <= 0 || $this->quantity < 10) ? ($this->quantity <= 0 ? "" : $this->quantity) : "",
           'productMedia' =>$this->productMedia->isNotEmpty()? ProductMediaResouce::collection($this->productMedia): url('storage/ProductMedia/default-product.jpg'),
           "similarProducts" => AllProductResource::collection($this->getSimilarProduct())
        ];//
    }
}
