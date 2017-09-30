<?php $selected_value = (isset($dataTypeContent->{$row->field}) && !empty(old($row->field,
                $dataTypeContent->{$row->field}))) ? old($row->field,
        $dataTypeContent->{$row->field}) : old($row->field); ?>
<?php $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : NULL; ?>
<ul class="radio">
    @if(isset($options->options))
        @if($selected_value != 'NA')
            @foreach($options->options as $key => $option)
                <li>
                    <input type="radio" id="option-{{ $key }}"
                            @if ($key == 'NA')
                                disabled="disabled"
                            @endif
                           name="{{ $row->field }}"
                           value="{{ $key }}" @if($default == $key && $selected_value === NULL){{ 'checked' }}@endif @if($selected_value == $key){{ 'checked' }}@endif>
                    <label for="option-{{ $key }}">{{ $option }}</label>
                    <div class="check"></div>
                </li>
            @endforeach
        @else
            <h6>La pregunta actual no aplica por la selección que tuvo la pregunta padre.</h6>
        @endif
    @endif
</ul>