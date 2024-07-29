<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_USERS = 'view_users';
    case CREATE_USERS = 'create_users';
    case EDIT_USERS = 'edit_users';
    case DELETE_USERS = 'delete_users';

    case VIEW_NOTIFIES = 'view_notifies';
    case CREATE_NOTIFIES = 'create_notifies';
    case EDIT_NOTIFIES = 'edit_notifies';
    case DELETE_NOTIFIES = 'delete_notifies';

    case VIEW_ENTERPRISE = 'view_enterprises';
    case CREATE_ENTERPRISE = 'create_enterprises';
    case EDIT_ENTERPRISE = 'edit_enterprises';
    case DELETE_ENTERPRISE = 'delete_enterprises';

    case VIEW_ROLES = 'view_roles';
    case CREATE_ROLES = 'create_roles';
    case EDIT_ROLES = 'edit_roles';
    case DELETE_ROLES = 'delete_roles';

    case VIEW_ABILITIES = 'view_abilities';
    case CREATE_ABILITIES = 'create_abilities';
    case EDIT_ABILITIES = 'edit_abilities';
    case DELETE_ABILITIES = 'delete_abilities';

    case SEND_EMAIL = 'send_email';
}