<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Branch;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branch\BranchService;
use App\Enums\ResponseCode\HttpStatusCode;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Resources\Branch\BranchResource;
use App\Http\Requests\Branch\CreateBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Resources\Branch\AllBranchCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class BranchController extends Controller implements HasMiddleware
{
    protected $branchService;
    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            // new Middleware('permission:all_branches', only:['index']),
            // new Middleware('permission:create_branch', only:['create']),
            // new Middleware('permission:edit_branch', only:['edit']),
            // new Middleware('permission:update_branch', only:['update']),
            // new Middleware('permission:destroy_branch', only:['destroy']),
        ];
    }



    public function index(Request $request)
    {
        $branches = $this->branchService->allBranches();
        return ApiResponse::success(new AllBranchCollection(PaginateCollection::paginate($branches, $request->pageSize?$request->pageSize:10)));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(CreateBranchRequest $createBranchRequest)
    {
        try {
            DB::beginTransaction();
                $data = $createBranchRequest->validated();
                $data['user_id'] = $createBranchRequest->user()->id; // أضف الـ user_id هنا
                $this->branchService->createBranch($data);
            DB::commit();
            return ApiResponse::success([], __('crud.created'));

        } catch (\Exception $e) {
            DB::rollBack();
           return ApiResponse::error(__('crud.server_error'),$e->getMessage(),HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function show(int $id)
    {
        try {
        $category  =  $this->branchService->editBranch($id);
        return ApiResponse::success(new BranchResource($category));
        }catch(ModelNotFoundException $e){
            return  ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            DB::rollBack();
          return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id,UpdateBranchRequest $updateBranchRequest)
    {

        try {
            DB::beginTransaction();
            $data = $updateBranchRequest->validated();
            $data['user_id'] = $updateBranchRequest->user()->id; //
            $this->branchService->updateBranch($id, $data);
            DB::commit();
            return ApiResponse::success([], __('crud.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
          return  ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        try {
            DB::beginTransaction();
            $this->branchService->deleteBranch($id);
            DB::commit();
            return ApiResponse::success([], __('crud.deleted'));
        }catch(ModelNotFoundException $e){
            return  ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            DB::rollBack();
          return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

}
