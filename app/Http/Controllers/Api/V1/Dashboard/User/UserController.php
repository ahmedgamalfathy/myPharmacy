<?php

namespace App\Http\Controllers\Api\V1\Dashboard\User;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Utils\PaginateCollection;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\AllUserCollection;
use App\Models\Branch\Branch;
use App\Services\Branch\BranchService;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UserController extends Controller implements HasMiddleware
{
    protected $userService;
    protected $branchService;

    public function __construct(UserService $userService,BranchService $branchService)
    {
        $this->userService = $userService;
        $this->branchService = $branchService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_users', only:['index']),
            new Middleware('permission:create_user', only:['create']),
            new Middleware('permission:edit_user', only:['edit']),
            new Middleware('permission:update_user', only:['update']),
            new Middleware('permission:destroy_user', only:['destroy']),
        ];
    }


    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get list of users",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=false,
     *         description="Number of users per page",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="users", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="userId", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="John Doe"),
     *                         @OA\Property(property="username", type="string", example="johndoe"),
     *                         @OA\Property(property="status", type="string", example="active"),
     *                         @OA\Property(property="avatar", type="string", example="https://example.com/avatar.jpg"),
     *                         @OA\Property(property="roleName", type="string", example="Admin"),
     *                         @OA\Property(property="charityName", type="string", example="Charity Org")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="count", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total_pages", type="integer", example=10)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */


    public function index(Request $request)
    {
        $users = $this->userService->allUsers();
        return ApiResponse::success(new AllUserCollection(PaginateCollection::paginate($users, $request->pageSize?$request->pageSize:10)));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(CreateUserRequest $createUserRequest)
    {
        try {
            DB::beginTransaction();

            $this->userService->createUser($createUserRequest->validated());
            DB::commit();

            return ApiResponse::success([], __('crud.created'));


        } catch (\Exception $e) {
            DB::rollBack();
           return  ApiResponse::error(__('crud.server_error'),$e->getMessage(),HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function show(int $id)
    {
        try {
            $user  =  $this->userService->editUser($id);
            return ApiResponse::success(new UserResource($user));
        }catch(ModelNotFoundException $e){
            return  ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
          return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id,UpdateUserRequest $updateUserRequest)
    {
        try {
            DB::beginTransaction();
            $this->userService->updateUser($id, $updateUserRequest->validated());
            DB::commit();
            return ApiResponse::success([], __('crud.updated'));

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $userId)
    {

        try {
            DB::beginTransaction();
            $this->userService->deleteUser($userId);
            DB::commit();
            return ApiResponse::success([], __('crud.deleted'));
        } catch(ModelNotFoundException $e){
            return  ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            DB::rollBack();
          return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function changeStatus(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->userService->changeUserStatus($request->userId, $request->status);
            DB::commit();

            return response()->json([
                'message' => __('messages.success.updated')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

}
