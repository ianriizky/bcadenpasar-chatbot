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
