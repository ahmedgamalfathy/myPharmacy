<?php

namespace App\Models\Branch;

use App\Models\Medicine\Medicine;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded =[];
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
