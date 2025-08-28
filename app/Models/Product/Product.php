<?php

namespace App\Models\Product;

use App\Models\User;
use App\Enums\IsMain;
use App\Models\Branch\Branch;
use App\Models\Slider\Slider;
use App\Traits\CreatedUpdatedBy;
use App\Enums\Product\ProductStatus;
use App\Models\Product\ProductMedia;
use App\Enums\Product\LimitedQuantity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use CreatedUpdatedBy, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cost',
        'price',
        'status',
        'is_limited_quantity',
        'quantity',
        'category_id',
        'sub_category_id',
        'specifications',
        'expired_at',
        'branch_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'avatars' => 'json',
            'status' => ProductStatus::class,
            'is_limited_quantity' => LimitedQuantity::class,
            'specifications' => 'json'
        ];
    }
    public function productMedia()
    {
        return $this->hasMany(ProductMedia::class);
    }
    public function firstProductMedia()
    {
        return $this->hasOne(ProductMedia::class)->where('is_main', IsMain::PRIMARY);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
    public function sliders(){
        return $this->belongsToMany(Slider::class ,'slider_items');
    }

    public function getSimilarProduct() {
        return Product::where('id', '!=', $this->id)
                      ->where(function($query) {
                          $query->where('category_id', $this->category_id)
                                ->orWhere('sub_category_id', $this->category_id);
                      })
                      ->get();
    }
   public function branches()
   {
       return $this->belongsToMany(Branch::class, 'branches');
   }
   public function user()
   {
       return $this->hasOne(User::class);
   }
}
