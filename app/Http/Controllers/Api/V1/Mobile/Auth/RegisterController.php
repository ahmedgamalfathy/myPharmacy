<?php

namespace App\Http\Controllers\Api\V1\Mobile\Auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Enums\User\UserStatus;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Resources\Client\LoggedInClientResource;
use App\Services\Upload\UploadService;

class RegisterController extends Controller
{
    protected $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateUserRequest $createUserRequest)
    {
        $data = $createUserRequest->validated();
        $avatarPath = [];

        if(isset($data['avatars']) ){
            foreach($data['avatars'] as $avatar)
            {
               $avatarPath[] =  $this->uploadService->uploadFile($avatar, 'avatars');
            }
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email']??null,
            'phone' => $data['phone']??null,
            'address' => $data['address']??null,
            'password' => $data['password'],
            'is_active' => UserStatus::from($data['isActive'])->value,
            'avatars' => $avatarPath,
        ]);

        $role = Role::find($data['roleId']);
        $user->assignRole($role->id);

        $token = $user->createToken(//now()->addDays(3) , now()->addHours(12)
            'register', ['*'], now()->addDays(1)
            )->plainTextToken;

        return ApiResponse::success([
            'profile' => new LoggedInClientResource($user),
            'tokenDetails' => [
                'token' => $token,
                'expiresIn' => 24 *60
            ],
        ],__('crud.created'));

    }
}
