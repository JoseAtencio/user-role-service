<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use App\Models\User;
use App\Models\Notify;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\RoleChangeRequest;
use LaravelJsonApi\Core\Responses\DataResponse;
use App\Enums\NotifyTypeEnum;

class NotifyController extends Controller
{
    use Actions\FetchMany, Actions\FetchOne, Actions\Store, Actions\Update, Actions\Destroy, Actions\FetchRelated, Actions\FetchRelationship, Actions\UpdateRelationship, Actions\AttachRelationship, Actions\DetachRelationship;

    const ADMIN_ROLES = [1, 2];

    public function index()
    {
        $notifies = Notify::all();
        //return DataResponse::make($notifies);
        return response()->json($notifies);
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        $role = $request->input('role_id');
        $user_id = $request->input('user_id');

        if (is_null($type) || is_null($role)) {
            return response()->json(['error' => 'Type and role are required'], 400);
        }

        $notifyType = NotifyTypeEnum::tryFrom($type);
        if ($notifyType === null) {
            return response()->json(['error' => 'Invalid notification type'], 400);
        }

        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['error' => 'Invalid user ID'], 400);
        }

        $roleModel = Role::find($role);
        if ($roleModel === null) {
            return response()->json(['error' => 'Invalid role ID'], 400);
        }

        $details = $notifyType->details();
        $details['role'] = $roleModel->name;

        $notifications = [];
        $userSelf = Auth::guard('sanctum')->user();

        switch ($notifyType) {
            case NotifyTypeEnum::ROLE_CHANGE_REQUEST:
                $admins = User::whereIn('role_id', self::ADMIN_ROLES)->get();
                foreach ($admins as $admin) {
                    $admin->notify(new RoleChangeRequest($user, $details));
                    $notifications[] = Notify::create([
                        'title' => $details['title'],
                        'from' => $user->email,
                        'type' => $notifyType->value,
                        'to' => $admin->email,
                    ]);
                }
                break;

            case NotifyTypeEnum::ACCEPT_ROLE_CHANGE:
            case NotifyTypeEnum::DECLINED_ROLE_CHANGE:
                $userSelf->notify(new RoleChangeRequest($userSelf, $details));
                $notifications[] = Notify::create([
                    'title' => $details['title'],
                    'from' => $userSelf->email,
                    'type' => $notifyType->value,
                    'to' => $user->email,
                ]);
                break;
        }
        return response()->json($notifications);
        //return DataResponse::make(collect($notifications));
    }
}