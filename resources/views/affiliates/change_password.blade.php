@extends('layouts.agent')

@section('title')
    Settings
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="container">
                            <form action="changepassword">
                                <label for="">Enter your current password</label>
                                <input class="form-control" type="text" name="current_password" value="" placeholder="Enter your current password">
                                <label for="">Your new password</label>
                                <input class="form-control" type="text" name="new_password" placeholder="Enter new password">
                                <label for="">Confirm new password</label>
                                <input class="form-control" type="text" name="password_confirmation" placeholder="Enter new password">
                                <br/>
                                <input class="form-control btn btn-sm btn-success" type="submit" name="changepassword" value="Save">
                                {{csrf_field()}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $('.datatable').dataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 100
        });
        $(function() {
            $('body').on('click', '.modal_href',function( e ) {
                Custombox.open({
                    target: '#modal_' + $(this).data('modal_id'),
                    effect: 'fadein'
                });
                e.preventDefault();
            });
        });
    </script>
@endsection