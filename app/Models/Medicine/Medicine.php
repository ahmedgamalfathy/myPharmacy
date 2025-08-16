<?php

namespace App\Models\Medicine;

use App\Models\Branch\Branch;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $guarded =[];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }
}
