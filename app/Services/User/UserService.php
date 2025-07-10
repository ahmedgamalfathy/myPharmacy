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
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterUser()), // Add a custom search filter
                AllowedFilter::exact('isActive', 'is_active'),
                AllowedFilter::custom('role', new FilterUserRole()),
            ])
            ->whereNot('id', $auth->id)
            ->get();

        return $user;

    }

    public function createUser(array $userData): User
    {

        $path = [];
        if(isset($userData['photos'])){
            foreach ($userData['photos'] as $item) {
                $path[] =  $this->uploadService->uploadFile($item, 'credentials');
            }
        }
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'user_type'=>$userData['userType'],
            'is_active' => UserStatus::from($userData['isActive'])->value,
            'photos' => $path,
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

        $path = [];

        if(isset($userData['photos']) ){
            foreach($userData['photos'] as $item){
               $path[] =  $this->uploadService->uploadFile($item, 'credentials');
            }
        }

        $user = User::find($userId);
        if(!$user){
            throw new ModelNotFoundException();
        }
        $user->name = $userData['name'];
        $user->user_type = $userData['userType'];
        $user->email = $userData['email']??'';
        if(isset($userData['password'])){
            $user->password = $userData['password'];
        }

        $user->is_active = UserStatus::from($userData['isActive'])->value;

        if($path){
            if($user->photos && is_array($user->photos)){
                foreach($user->photos as $oldPhoto){
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
            $user->photos = $path;
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
        if($user->photos){
            foreach($user->photos as $oldPhoto){
                // Storage::disk('public')->delete($user->getRawOriginal('item'));
                Storage::disk('public')->delete($oldPhoto);
            }
        }
        $user->delete();

    }

    public function changeUserStatus(int $userId, int $isActive): void
    {

        User::where('id', $userId)->update(['is_active' => UserStatus::from($isActive)->value]);

    }


}
