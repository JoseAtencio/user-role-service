<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error as JsonApiError;
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

    public function index(Request $request) {
        try {
            $filters = $request->only(['name', 'email', 'phone', 'is_owner']);
            $query = User::filterByAttributes($filters);
            if (isset($filters['is_owner']) && $filters['is_owner']) {
                $query->owners();
            }
            $users = $query->with('enterprise')->get();
            return DataResponse::make($users);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }

    public function show($user)
    {
        try {
            $user = User::findOrFail($user->id);
            return DataResponse::make($user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $error = JsonApiError::make()
                ->setStatus(404)
                ->setDetail('User not found');
            return ErrorResponse::make($error);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());
            return DataResponse::make($user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $error = JsonApiError::make()
                ->setStatus(404)
                ->setDetail('User not found');
            return ErrorResponse::make($error);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $user = User::create($data);
            return DataResponse::make($user);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return DataResponse::make($user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $error = JsonApiError::make()
                ->setStatus(404)
                ->setDetail('User not found');
            return ErrorResponse::make($error);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }
}