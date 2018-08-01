@if($data['url'])
    <iframe width="100%" height="100%" allowfullscreen="true" allowtransparency="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowFullScreen="true" frameborder="0" scrolling="no"  src="{{$data['url']}}"></iframe>
@else
    <div style="height:600px;">
    {!! $data['object'] !!}
    </div>
@endif