<?php

namespace App\Services\Area;

use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Area\Area;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AreaService{


    public function allArea()
    {
        $areas = QueryBuilder::for(Area::class)
        ->get([
            'id',
            'name'
        ]);

        return $areas;

    }

    public function createArea(array $data)
    {
      //name , location , address , status
        $area = Area::create([
            'name' => $data['name'],
            'region_id'=> $data['region_id']
        ]);
        return $area;

    }

    public function editArea(int $id)
    {
       $area= Area::findOrFail($id);
        if(!$area){
           throw new ModelNotFoundException();
        }
        return $area;
    }

    public function updateArea(int $id,array $data)
    {
      //name , location , address , status
        $area = Area::find($id);
        $area->name = $data['name'];
        $area->region_id = $data['region_id'] ?? null;
        $area->save();
        return $area;

    }


    public function deleteArea(int $id)
    {
        $area = Area::find($id);
        if (!$area) {
            throw new ModelNotFoundException();
        }
        $area->delete();
    }

}
