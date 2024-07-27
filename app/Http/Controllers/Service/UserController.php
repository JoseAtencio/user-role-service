<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Core\Responses\DataResponse;
use App\JsonApi\V1\UserCreates\UserCreateRequest;
use App\Models\User;

class UserController extends Controller
{

    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\Store;
    use Actions\Update;
    use Actions\Destroy;
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
    use Actions\UpdateRelationship;
    use Actions\AttachRelationship;
    use Actions\DetachRelationship;

    public function index(){
        $users = User::all();
        return DataResponse::make($users);
    }
    /*
        public function store(UserCreateRequest $request)
    {
        $data = $request->only(['name', 'email', 'dni', 'password', 'phone', 'address']);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return DataResponse::make($user)->withStatus(201);
    }
    */
    

}
