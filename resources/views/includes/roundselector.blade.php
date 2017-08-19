 <script>
	jQuery(document).ready(function() {
		jQuery('#roundLink').click(function(event){
			event.preventDefault();
			jQuery('#roundLink').hide();
			jQuery('#roundPicker').animate({width:350});
		});
		jQuery('#roundPicker').on('change', function(event) {
			//window.location.href = window.location.href+"/"+jQuery(event.currentTarget).val();
			console.log(window.location.hostname+window.location.pathname);
			@if(isset($subject))
				window.location.href = '{{ URL::to('/') }}'+'{{ Route::getCurrentRoute()->compiled->getStaticPrefix() }}'+'/'+{{ $subject->id }}+'/'+jQuery(event.currentTarget).val();
			@elseif(isset($numeral))
				window.location.href = '{{ URL::to('/') }}'+'{{ Route::getCurrentRoute()->compiled->getStaticPrefix() }}'+'/'+{{ $numeral->id }}+'/'+jQuery(event.currentTarget).val();
			@else
				window.location.href = '{{ URL::to('/') }}'+'{{ Route::getCurrentRoute()->compiled->getStaticPrefix() }}'+'/'+jQuery(event.currentTarget).val();
			@endif
		});
	});
	

 </script>

<a href="#" id="roundLink">Ver información para otras rondas</a>
<select style="width:0px;" class="selectpicker" id="roundPicker">
	@foreach($availableRounds as $roundItem)
		<option @if (isset($rounds)) @if($rounds[0]->id == $roundItem->id) selected="selected" @endif @elseif (isset($round)) @if($round->id == $roundItem->id) selected="selected" @endif @endif value="{{ $roundItem->id }}">{{ $roundItem->name }}</option>
	@endforeach
</select>