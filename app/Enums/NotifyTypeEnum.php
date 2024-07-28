<?php

namespace App\Enums;

enum NotifyTypeEnum: string
{
    case ROLE_CHANGE_REQUEST = 'request-role-change';
    case ACCEPT_ROLE_CHANGE = 'accept-role-change';
    case DECLINED_ROLE_CHANGE = 'declined-role-change';

    public function details(): array
    {
        return match($this) {
            self::ROLE_CHANGE_REQUEST => [
                'title' => 'Role change request sent to administrators.',
                'subject' => 'Role Change Request',
                'line' => 'User has requested a role change.',
                'greeting' => 'Hello Admin,',
                'end' => 'Thank you for using our application!',
            ],
            self::ACCEPT_ROLE_CHANGE => [
                'title' => 'Role change accepted.',
                'subject' => 'Role Change Accepted',
                'line' => 'Your role change request has been accepted.',
                'greeting' => 'Hello User,',
                'end' => 'Thank you for using our application!',
            ],
            self::DECLINED_ROLE_CHANGE => [
                'title' => 'Role change declined.',
                'subject' => 'Role Change Declined',
                'line' => 'Your role change request has been declined.',
                'greeting' => 'Hello User,',
                'end' => 'Thank you for using our application!',
            ],
        };
    }

    public function label(): string
    {
        return match($this) {
            self::ROLE_CHANGE_REQUEST => 'ROLE_CHANGE_REQUEST',
            self::ACCEPT_ROLE_CHANGE => 'ACCEPT_ROLE_CHANGE',
            self::DECLINED_ROLE_CHANGE => 'DECLINED_ROLE_CHANGE',
        };
    }
}