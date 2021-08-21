<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotTelegramImage implements Rule
{
    /**
     * Create a new instance class.
     *
     * @param  bool  $mustBeString
     * @param  bool  $canBeNull
     * @return void
     */
    public function __construct(
        protected bool $mustBeString = true,
        protected bool $canBeNull = false
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function passes($attribute, $value)
    {
        if (is_null($value) && $this->canBeNull) {
            return true;
        }

        return
            ($this->mustBeString ? is_string($value) : true) &&
            $value !== '%%%_IMAGE_%%%';
    }

    /**
     * {@inheritDoc}
     */
    public function message()
    {
        return trans('validation.not_telegram_image');
    }
}
