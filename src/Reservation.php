<?php

namespace Ejoy\Reservation;

use Encore\Admin\Extension;

class Reservation extends Extension
{
    public $name = 'reservation';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Reservation',
        'path'  => 'reservation',
        'icon'  => 'fa-gears',
    ];
}