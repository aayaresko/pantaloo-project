<div>
    @if(is_array($data))
        <ul style="list-style-type:circle">
            @foreach($data as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @else
       {{ $data }}
    @endif
</div>
