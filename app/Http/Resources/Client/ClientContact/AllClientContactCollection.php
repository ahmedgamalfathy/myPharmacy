<?php

namespace App\Http\Resources\Client\ClientContact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllClientContactCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     private $pagination;

     public function __construct($resource)
     {
         $this->pagination = [
             'total' => $resource->total(),
             'count' => $resource->count(),
             'perPage' => $resource->perPage(),
             'currentPage' => $resource->currentPage(),
             'totalPages' => $resource->lastPage()
         ];

         $resource = $resource->getCollection();

         parent::__construct($resource);
     }


    public function toArray(Request $request): array
    {

        return [
            'contacts' => AllClientContactResource::collection(($this->collection)->values()->all()),
            'pagination' => $this->pagination
        ];

    }
}
