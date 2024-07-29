<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error as JsonApiError;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnterpriseController extends Controller
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
        try {
            $enterprises = Enterprise::all();
            return DataResponse::make($enterprises);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }

    public function show($id)
    {
        try {
            $enterprise = Enterprise::findOrFail($id);
            if (!$enterprise) {
                $error = JsonApiError::make()
                    ->setStatus(404)
                    ->setDetail('Enterprise not found');
                return ErrorResponse::make($error);
            }
            return DataResponse::make($enterprise);
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
            $enterprise = Enterprise::find($id);
            if (!$enterprise) {
                $error = JsonApiError::make()
                    ->setStatus(404)
                    ->setDetail('Enterprise not found');
                return ErrorResponse::make($error);
            }
            $enterprise->update($request->all());
            return DataResponse::make($enterprise);
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
            $user_id = Auth::guard('sanctum')->user()->id;
            $data = $request->all();
            $data['user_id'] = $user_id;
            $enterprise = Enterprise::create($data);
            return DataResponse::make($enterprise);
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
            $enterprise = Enterprise::find($id);
            if (!$enterprise) {
                $error = JsonApiError::make()
                    ->setStatus(404)
                    ->setDetail('Enterprise not found');
                return ErrorResponse::make($error);
            }
            $enterprise->delete();
            return DataResponse::make($enterprise);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }


    public function add_activities(Request $request, $id) {   
        try {
            $activities_id = $request->activities_id;
            if (!is_array($activities_id)) {
                $activities_id = explode(',', $activities_id);
            }
            $user = Auth::guard('sanctum')->user();
            if ($user->enterprise()->count() > 1) {
                return response()->json(['error' => 'User can only have one enterprise'], 400);
            }
            $enterprise = $user->enterprise;
            if (!$enterprise) {
                return response()->json(['error' => 'User does not have an associated enterprise'], 404);
            }
            $existingActivities = $enterprise->activities()->pluck('activities.id')->toArray();
            $newActivities = array_diff($activities_id, $existingActivities);
            if (empty($newActivities)) {
                return response()->json(['message' => 'All activities are already associated with the enterprise'], 200);
            }
            $enterprise->activities()->attach($newActivities);
            return response()->json(
                [
                    'message' => 'Activities added successfully',
                    'enterprise' => $enterprise
                ],
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while adding activities', 'details' => $e->getMessage()], 500);
        }
    }

    public function remove_activities(Request $request, $id) {
        try {
            $activities_id = $request->activities_id;
            if (!is_array($activities_id)) {
                $activities_id = explode(',', $activities_id);
            }
            $user = Auth::guard('sanctum')->user();
            if ($user->enterprise()->count() > 1) {
                return response()->json(['error' => 'User can only have one enterprise'], 400);
            }
            $enterprise = $user->enterprise;
            if (!$enterprise) {
                return response()->json(['error' => 'User does not have an associated enterprise'], 404);
            }
            $existingActivities = $enterprise->activities()->pluck('activities.id')->toArray();
            $activitiesToRemove = array_intersect($activities_id, $existingActivities);
            if (empty($activitiesToRemove)) {
                return response()->json(['message' => 'None of the specified activities are associated with the enterprise'], 200);
            }
            $enterprise->activities()->detach($activitiesToRemove);
            return response()->json(
                [
                    'message' => 'Activities removed successfully',
                    'enterprise' => $enterprise
                ],
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while removing activities', 'details' => $e->getMessage()], 500);
        }
    }
 
}