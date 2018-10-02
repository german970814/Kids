<textarea
    name="{{ $name }}"
    value="{{ old( $name, isset($value) ? $value : '' ) }}"
    class="form-control border-color-1"
    placeholder="{{ isset($label) ? $label : '' }}">
</textarea>
@if($errors->has($name))
@foreach ($errors->get($name) as $error)
    <div>{{ $error }}</div>
@endforeach
@endif
