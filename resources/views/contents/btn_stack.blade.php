@if (isset($stack_line))
    @foreach ($stack_line as $action)
        @include('viralslaravelexcel::buttons.' . $action)
    @endforeach
@endif

@if (isset($stack_top))
    @push('stack_btn_top')
        @foreach ($stack_top as $action)
            @include('viralslaravelexcel::buttons.' . $action)
        @endforeach
    @endpush
@endif
