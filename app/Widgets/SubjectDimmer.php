<?php

namespace App\Widgets;

use App\Subject;
use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;


class SubjectDimmer extends AbstractWidget
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
        $count = Subject::count();
        $string = $count == 1 ? 'sujeto obligado' : 'sujetos obligados';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "Actualmente hay {$count} {$string}. Presiona el botón para administrarlos.",
            'button' => [
                'text' => 'Ver sujetos obligados',
                'link' => route('voyager.subjects.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
