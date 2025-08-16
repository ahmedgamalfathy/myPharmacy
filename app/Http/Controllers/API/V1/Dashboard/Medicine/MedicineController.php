<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Medicine;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Models\Medicine\Medicine;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Services\Medicine\MedicineService;
use App\Http\Requests\Medicine\CreateMedicineRequest;
use App\Http\Requests\Medicine\UpdateMedicineRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicineController extends Controller
{
    public function __construct(public MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $medicines = $this->medicineService->allMedicine();
        return ApiResponse::success($medicines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMedicineRequest $createMedicineRequest)
    {
        try {
        $medicine = $this->medicineService->createMedicine($createMedicineRequest->validated());
        return ApiResponse::success($medicine, 'Medicine created successfully');
        } catch (\Exception $e) {
        return ApiResponse::error(__('crud.server_error'), $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $medicine = $this->medicineService->editMedicine($id);
             return ApiResponse::success($medicine);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Medicine not found', 404);
        }catch (\Exception $e) {
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicineRequest $updateMedicineRequest, int $id)
    {
        try {
            $medicine = $this->medicineService->updateMedicine($id, $updateMedicineRequest->validated());
            return ApiResponse::success($medicine, 'Medicine updated successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Medicine not found', 404);
        } catch (\Exception $e) {
            return ApiResponse::error(__('crud.server_error'), $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $medicine = $this->medicineService->deleteMedicine($id);
            return ApiResponse::success([], 'Medicine deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Medicine not found', 404);
        } catch (\Exception $e) {
            return ApiResponse::error(__('crud.server_error'), $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
      
    }
}
