<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Role extends BaseRole implements Concerns\Role\Attribute
{
    //
}
