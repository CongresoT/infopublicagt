<?php

namespace App\Widgets;

use App\Numeral;
use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\VoyagerUser;

class NumeralDimmer extends AbstractWidget
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
        if (!Voyager::can('browse_numerals')) {
            return "";
        }

        $count = Numeral::count();
        $string = $count == 1 ? 'numeral' : 'numerales';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "Actualmente hay {$count} {$string}. Presiona el botón para administrarlas.",
            'button' => [
                'text' => 'Ver numerales',
                'link' => route('voyager.numerals.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
