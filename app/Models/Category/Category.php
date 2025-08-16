<?php

namespace App\Models\Category;

use App\Enums\IsActiveEnum;
use Faker\Provider\Medical;
use App\Models\Medicine\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    protected $guarded = [];
    public function casts()
    {
        return [
            'status' => IsActiveEnum::class,
        ];
    }
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : "",
        );
    }

}
