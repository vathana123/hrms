<?php

namespace App\View\Components;

use App\Models\DashboardMenu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItem extends Component
{
    public function __construct(
        public DashboardMenu $menu
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.menu-item');
    }
}