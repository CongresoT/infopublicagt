<?php

namespace App\Widgets;

use App\Track;
use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\VoyagerUser;

class TrackDimmer extends AbstractWidget
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
        if (!Voyager::can('browse_tracks')) {
            return "";
        }

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "Ronda Actual",
            'text'   => "Presiona el botón para ver/ingresar la información del monitoreo actual",
            'button' => [
                'text' => 'Monitorear',
                'link' => route('voyager.tracks.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
