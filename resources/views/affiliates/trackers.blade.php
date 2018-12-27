@extends('layouts.agent')

@section('title')
    Trackers
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div id="custom-modal" class="modal-demo">
                                <button type="button" class="close" onclick="Custombox.close();">
                                    <span>&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="custom-modal-title">Create tracker</h4>
                                <div class="custom-modal-text">
                                    <form method="POST" action="{{route('agent.store_tracker')}}">
                                        {{csrf_field()}}
                                        <h4>Name</h4>
                                        <input type="text" class="form-control" name="name" value="">
                                        <h4>Ref</h4>
                                        <input type="text" class="form-control" name="ref" value="">
                                        <br>
                                        <input type="submit" name="save" value="Create" class="btn btn-success">
                                    </form>
                                </div>
                            </div>
                            <a href="#custom-modal" class="btn btn-primary waves-effect waves-light m-r-5 m-b-10"
                               data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200"
                               data-overlaycolor="#36404a">Create Tracker</a>
                            <table class="table table-hover">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Name
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        URL
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Edit
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Act
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trackers as $tracker)
                                    <tr>
                                        <td>{{$tracker->name}}</td>
                                        <td>
                                            {{$tracker->getLinks()->first()}} <a href="#links_{{$tracker->id}}"
                                                                                 class="btn btn-purple btn-xs"
                                                                                 data-animation="fadein"
                                                                                 data-plugin="custommodal"
                                                                                 data-overlayspeed="200"
                                                                                 data-overlaycolor="#36404a">All</a>

                                            <div id="links_{{$tracker->id}}" class="modal-demo">
                                                <button type="button" class="close" onclick="Custombox.close();">
                                                    <span>&times;</span><span class="sr-only">Close</span>
                                                </button>
                                                <h4 class="custom-modal-title">Links</h4>
                                                <div class="custom-modal-text">
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <th>Link</th>
                                                            {{--<th>Language</th>--}}
                                                            <th>Copy</th>
                                                        </tr>

                                                        @foreach(\App\Domain::all() as $key => $domain)
                                                            <tr>
                                                                @if ($key == 0)
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                               value="http://{{$domain->domain}}/?ref={{$tracker->ref}}">
                                                                    </td>
                                                                    {{--<td>{{strtoupper($domain->lang)}}</td>--}}
                                                                    <td>
                                                                        <a href="#" class="btn btn-primary clipboard">Copy</a>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#edit_{{$tracker->id}}" class="btn btn-info btn-sm btn-rounded"
                                               data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200"
                                               data-overlaycolor="#36404a">Edit</a>

                                            <div id="edit_{{$tracker->id}}" class="modal-demo">
                                                <button type="button" class="close" onclick="Custombox.close();">
                                                    <span>&times;</span><span class="sr-only">Close</span>
                                                </button>
                                                <h4 class="custom-modal-title">Edit</h4>
                                                <div class="custom-modal-text">
                                                    <form method="POST"
                                                          action="{{route('agent.updateTracker', $tracker)}}">
                                                        {{csrf_field()}}
                                                        <h4>Name</h4>
                                                        <input type="text" class="form-control" name="name"
                                                               value="{{$tracker->name}}">
                                                        <br>
                                                        <input type="submit" name="save" value="Save"
                                                               class="btn btn-info">
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{route('affiliates.marketingMaterial', $tracker->id)}}"
                                               class="btn btn-info">Marketing</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

