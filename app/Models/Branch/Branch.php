<?php

namespace App\Models\Branch;

use App\Models\User;
use App\Models\Area\Area;
use App\Models\Medicine\Medicine;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded =[];
    protected $table = 'branches';

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }

}
