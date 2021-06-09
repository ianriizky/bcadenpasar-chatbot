<?php

namespace App\Infrastructure\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
abstract class Model extends BaseModel
{
    //
}
