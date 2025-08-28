<?php

namespace App\Http\Resources\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllBranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
 //name , location , address , status
        return [
            'branchId' => $this->id,
            'name' => $this->name,
            'areaId' => $this->area_id,
            'userId' => $this->user_id,
        ];
    }
}
