<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminLayout extends Component
{
    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('layouts.admin');
    }
}
