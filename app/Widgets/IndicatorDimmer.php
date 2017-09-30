<?php

namespace App\Widgets;

use App\Indicator;
use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;


class IndicatorDimmer extends AbstractWidget
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
        $count = Indicator::count();
        $string = $count == 1 ? 'base de pregunta' : 'bases de preguntas';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "Actualmente hay {$count} {$string}. Presiona el botón para administrarlas.",
            'button' => [
                'text' => 'Ver bases de preguntas',
                'link' => route('voyager.indicators.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
