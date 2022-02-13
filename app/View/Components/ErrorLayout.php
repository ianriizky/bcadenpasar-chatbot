<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ErrorLayout extends Component
{
    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('layouts.error');
    }
}
