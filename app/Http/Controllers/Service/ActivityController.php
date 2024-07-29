<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error as JsonApiError;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
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
            $activities = Activity::all();
            return DataResponse::make($activities);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }

    public function show($activities)
    
    {
        try {
            $activity = Activity::findOrFail($activities->id);
            if (!$activity) {
                $error = JsonApiError::make()
                    ->setStatus(404)
                    ->setDetail('Activity not found');
                return ErrorResponse::make($error);
            }
            return DataResponse::make($activity);
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
            $activity = Activity::find($id);
            if (!$activity) {
                $error = JsonApiError::make()
                    ->setStatus(404)
                    ->setDetail('Activity not found');
                return ErrorResponse::make($error);
            }
            $activity->update($request->all());
            return DataResponse::make($activity);
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
            $activity = Activity::create($data);
            return DataResponse::make($activity);
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
            $activity = Activity::find($id);
            if (!$activity) {
                $error = JsonApiError::make()
                    ->setStatus(404)
                    ->setDetail('Activity not found');
                return ErrorResponse::make($error);
            }
            $activity->delete();
            return DataResponse::make($activity);
        } catch (\Throwable $th) {
            $error = JsonApiError::make()
                ->setStatus(500)
                ->setDetail($th->getMessage());
            return ErrorResponse::make($error);
        }
    }
}