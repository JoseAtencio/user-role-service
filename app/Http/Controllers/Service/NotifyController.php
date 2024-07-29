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
    const ERROR_INVALID_TYPE = 'Invalid notification type';
    const ERROR_INVALID_USER = 'Invalid user ID';
    const ERROR_INVALID_ROLE = 'Invalid role ID';
    const ERROR_REQUIRED_FIELDS = 'Type and role are required';

    public function index()
    {
        $notifies = Notify::all();
        return response()->json($notifies);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|integer',
            'role_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $notifyType = NotifyTypeEnum::tryFrom($validated['type']);
        if ($notifyType === null) {
            return response()->json(['error' => self::ERROR_INVALID_TYPE], 400);
        }

        $user = User::find($validated['user_id']);
        if ($user === null) {
            return response()->json(['error' => self::ERROR_INVALID_USER], 400);
        }

        $roleModel = Role::find($validated['role_id']);
        if ($roleModel === null) {
            return response()->json(['error' => self::ERROR_INVALID_ROLE], 400);
        }

        $details = $notifyType->details();
        $details['role'] = $roleModel->name;

        $notifications = $this->handleNotification($notifyType, $user, $details);

        return response()->json($notifications);
    }

    private function handleNotification($notifyType, $user, $details)
    {
        $notifications = [];
        $userSelf = Auth::guard('sanctum')->user();

        switch ($notifyType) {
            case NotifyTypeEnum::ROLE_CHANGE_REQUEST:
                $notifications = $this->notifyAdmins($user, $details);
                break;

            case NotifyTypeEnum::ACCEPT_ROLE_CHANGE:
            case NotifyTypeEnum::DECLINED_ROLE_CHANGE:
                $notifications[] = $this->notifyUser($userSelf, $user, $details);
                break;
        }

        return $notifications;
    }

    private function notifyAdmins($user, $details)
    {
        $notifications = [];
        $admins = User::whereIn('role_id', self::ADMIN_ROLES)->get();

        foreach ($admins as $admin) {
            $admin->notify(new RoleChangeRequest($user, $details));
            $notifications[] = Notify::create([
                'title' => $details['title'],
                'from' => $user->email,
                'type' => NotifyTypeEnum::ROLE_CHANGE_REQUEST->value,
                'to' => $admin->email,
            ]);
        }

        return $notifications;
    }

    private function notifyUser($userSelf, $user, $details)
    {
        $userSelf->notify(new RoleChangeRequest($userSelf, $details));
        return Notify::create([
            'title' => $details['title'],
            'from' => $userSelf->email,
            'type' => NotifyTypeEnum::ACCEPT_ROLE_CHANGE->value,
            'to' => $user->email,
        ]);
    }
}