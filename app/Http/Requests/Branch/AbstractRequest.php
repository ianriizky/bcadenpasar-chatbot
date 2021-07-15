<?php

namespace App\Http\Requests\Branch;

use App\Infrastructure\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function authorize()
    {
        return !is_null($this->user());
    }
}
