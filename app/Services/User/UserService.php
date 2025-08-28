<?php

namespace App\Services\User;

use App\Enums\User\UserStatus;
use App\Filters\User\FilterUser;
use App\Filters\User\FilterUserRole;
use App\Models\User;
use App\Services\Upload\UploadService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserService{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function allUsers()
    {
        $auth = auth()->user();
        /*$currentUserRole = $auth->getRoleNames()[0];*/
        $user = QueryBuilder::for(User::class)
           ->defaultSort('-created_at')
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterUser()), // Add a custom search filter
                AllowedFilter::exact('isActive', 'is_active'),
                AllowedFilter::custom('role', new FilterUserRole()),
            ])
            ->whereNot('id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $user;

    }

    public function createUser(array $userData): User
    {


        // if(isset($userData['photos'])){
        //     foreach ($userData['photos'] as $item) {
        //     $path[] =  $this->uploadService->uploadFile($item, 'credentials');
        //     }
        // }
        $avatarPath = [];

        if(isset($userData['avatars']) ){
            foreach($userData['avatars'] as $avatar)
            {
               $avatarPath[] =  $this->uploadService->uploadFile($avatar, 'avatars');
            }
        }

        $user = User::create([
            'name' => $userData['name'],
            'username' => $userData['username'],
            'email' => $userData['email']??null,
            'phone' => $userData['phone']??null,
            'address' => $userData['address']??null,
            'password' => $userData['password'],
            'is_active' => UserStatus::from($userData['isActive'])->value,
            'avatars' => $avatarPath,
        ]);

        $role = Role::find($userData['roleId']);
        $user->assignRole($role->id);

        return $user;

    }

    public function editUser(int $userId)
    {
        $user= User::with('roles')->findOrFail($userId);
        if(!$user){
          throw new ModelNotFoundException();
        }
        return $user;
    }

    public function updateUser(int $userId, array $userData)
    {
        $avatarPath = [];
        if(isset($userData['avatars']) ){
            foreach($userData['avatars'] as $avatar)
            {
               $avatarPath[] =  $this->uploadService->uploadFile($avatar, 'avatars');
            }
        }

        $user = User::find($userId);
        $user->name = $userData['name'];
        $user->username = $userData['username'];
        $user->email = $userData['email']??'';
        $user->phone = $userData['phone']??'';
        $user->address = $userData['address'??''];

        if(isset($userData['password'])){
            $user->password = $userData['password'];
        }

        $user->is_active = UserStatus::from($userData['isActive'])->value;

        if($avatarPath){
            if($user->avatars){
                Storage::disk('public')->delete($user->getRawOriginal('avatars'));
            }
            $user->avatars = $avatarPath;
        }

        $user->save();

        $role = Role::find($userData['roleId']);

        $user->syncRoles($role->id);

        return $user;

    }


    public function deleteUser(int $userId)
    {

        $user = User::find($userId);
        if(!$user){
          throw new ModelNotFoundException();
        }
        if($user->avatar){
            Storage::disk('public')->delete($user->getRawOriginal('avatar'));
        }
        $user->delete();

    }

    public function changeUserStatus(int $userId, int $isActive): void
    {

        User::where('id', $userId)->update(['is_active' => UserStatus::from($isActive)->value]);

    }


}
