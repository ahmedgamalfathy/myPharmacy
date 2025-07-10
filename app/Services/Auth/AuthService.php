<?php

namespace App\Services\Auth;

use App\Enums\IsActiveEnum;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Enums\User\UserStatus;
use App\Helpers\ApiResponse;
use App\Http\Resources\User\LoggedInUserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\Upload\UploadService;
use App\Services\UserRolePremission\UserPermissionService;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userPermissionService;
    protected $uploadService;
    public function __construct(UploadService $uploadService,UserPermissionService $userPermissionService)
    {
        $this->userPermissionService = $userPermissionService;
        $this->uploadService = $uploadService;
    }

    public function register(array $data){
        $path =[];
        if(isset($data['photos']) ){
        foreach ($data['photos'] as $item) {
         $path[]=$this->uploadService->uploadFile($item,'credentials');
        }
     }

     $user = User::create([
        'name'=>$data['name'],
        "email"=>$data['email'],
        "user_type"=>$data['userType'],
        "photos" =>$path,
        "password"=>Hash::make($data['password'])
      ]);
      return $user;
    }
    public function login(array $data)
    {
        try {
            $user = User::where('email', $data['email'])->first();
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return ApiResponse::error(__('auth.failed'), [], HttpStatusCode::UNAUTHORIZED);
            }
            if ($user->is_active == IsActiveEnum::INACTIVE->value) {
                return ApiResponse::error(__('auth.inactive_account'), [], HttpStatusCode::UNAUTHORIZED);
            }
            // // Revoke old tokens (optional)
            // $user->tokens()->delete();

            // Generate a new token (DO NOT return it directly)
            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success([
                'profile' => new LoggedInUserResource($user),
                'role' => $user->roles->first()->name,
                'permissions' => $this->userPermissionService->getUserPermissions($user),
                'tokenDetails' => [
                    'token' => $token,
                    'expiresIn' => 1440
                ],
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout()
    {
        $user = auth()->user();

        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens
        }

        return ApiResponse::success([], __('auth.logged_out'));
    }
}
