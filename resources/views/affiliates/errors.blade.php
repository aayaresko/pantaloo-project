@if (count($errors) > 0)
    <script>
            var errors = {!! json_encode($errors->all()) !!};
    </script>
@endif

@if(Session::has('msg'))
    <script>
        var success_msg = '{{Session::get('msg')}}';
    </script>
@endif