<?php

namespace App\Widgets;

use App\Round;
use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\VoyagerUser;

class RoundDimmer extends AbstractWidget
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
        if (!Voyager::can('browse_rounds')) {
            return "";
        }

        $count = Round::count();
        $string = $count == 1 ? 'ronda' : 'rondas';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "Actualmente hay {$count} {$string}. Presiona el botón para administrarlas.",
            'button' => [
                'text' => 'Ver rondas',
                'link' => route('voyager.rounds.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
