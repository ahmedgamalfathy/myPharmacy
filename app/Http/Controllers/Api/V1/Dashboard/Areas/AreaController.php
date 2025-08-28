<?php

namespace App\Http\Controllers\API\V1\Dashboard\Areas;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\Area\AreaService;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    public function __construct(public AreaService $areaService)
    {
       $this->areaService = $areaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = $this->areaService->allArea();
        return ApiResponse::success($areas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:areas,name|max:255',
            'areaId' => 'nullable|integer|exists:areas,id',
        ]);

        $area = $this->areaService->createArea($data);
        return ApiResponse::success([], 'Area created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $area = $this->areaService->editArea($id);
        return ApiResponse::success($area);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255',Rule::unique('areas')->ignore($id)],
            'areaId' => 'nullable|integer|exists:areas,id',
        ]);

        $area = $this->areaService->updateArea($id, $data);
        return ApiResponse::success([],"Area updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->areaService->deleteArea($id);
        return ApiResponse::success([], 'Area deleted successfully');
    }
}
