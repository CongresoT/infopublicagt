<?php

namespace App\Widgets;

use App\Sector;
use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;


class SectorDimmer extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Sector::count();
        $string = $count == 1 ? 'sector' : 'sectores';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "Actualmente hay {$count} {$string}. Presiona el botón para administrarlos.",
            'button' => [
                'text' => 'Ver sectores',
                'link' => route('voyager.sectors.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
