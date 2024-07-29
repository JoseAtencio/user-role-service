<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Core\Responses\DataResponse;
use App\JsonApi\V1\UserCreates\UserCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        $users = User::all();
        //return DataResponse::make($users);
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        //return DataResponse::make($user);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update($request->all());
        return response()->json(
            [
                'message' => 'User updated successfully',
                'user' => $user
            ]
        );
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 
            'User deleted successfully',
            'user' => $user
        ]);
        
    }


}