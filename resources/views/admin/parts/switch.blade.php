@php
    $htmlState = [
                0 => "<span class='label label-danger'>OFF</span>",
                1 => "<span class='label label-success'>ON</span>"
            ];
@endphp

{!! $htmlState[$switch] !!}