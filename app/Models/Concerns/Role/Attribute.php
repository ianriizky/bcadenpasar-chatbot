<?php

namespace App\Models\Concerns\Role;

/**
 * @property string $name
 * @property string $guard_name
 *
 * @see \App\Models\Role
 */
interface Attribute
{
    /**
     * List of role name.
     *
     * @var array
     */
    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_STAFF,
    ];

    /**
     * Role name for "admin".
     *
     * @var string
     */
    const ROLE_ADMIN = 'admin';

    /**
     * Role name for "staff".
     *
     * @var string
     */
    const ROLE_STAFF = 'staff';
}
