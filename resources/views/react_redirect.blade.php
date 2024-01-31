@extends('layouts.app')

@section('content')
<div id="react-root"></div>

<script>
    var status = "{{ $status }}";
    var orderId = "{{ $orderId }}";
</script>
<script src="{{ asset('js/react_app.js') }}"></script>
@endsection
