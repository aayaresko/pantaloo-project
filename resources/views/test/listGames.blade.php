@extends('layouts.app')

@section('title')
    Test LIST GAME
@endsection

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg.png') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                @foreach ($games as $item)
                    <p><a href="/test/game/{{$item->name}}">{{ $item->name }}</a></p>
                @endforeach
            </div>
        </div>
    </div>
@endsection
