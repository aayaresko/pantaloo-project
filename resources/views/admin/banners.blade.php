@extends('layouts.admin')

@section('title')
    Banners
@endsection

@section('content')
    @include('admin.banner_view', ['banners' => $banners])
@endsection

@section('js')
    <script>
        $(".showImage").on("click", function() {
            let imageSrc = $(this).children().attr('src');
            $('#imagepreview').attr('src', imageSrc);
            $('#imagemodal').modal('show');
        });
    </script>
@endsection