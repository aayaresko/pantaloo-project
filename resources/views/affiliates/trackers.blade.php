@extends('layouts.agent')

@section('title')
    Links
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
                                <h4 class="custom-modal-title">Create link</h4>
                                <div class="custom-modal-text">
                                    <form method="POST" action="{{route('agent.store_tracker')}}">
                                        {{csrf_field()}}
                                        <h4>Campaign Name</h4>
                                        <input type="text" class="form-control" name="name" value="">
                                        <h4>Campaign Link</h4>
                                        <input type="text" class="form-control" name="campaign_link" value="">
                                        {{--<h4>Ref</h4>--}}
                                        {{--<input type="text" class="form-control" name="ref" value="">--}}
                                        <br>
                                        {{--<h4>Include campaign name</h4>--}}
                                        {{--<input type="checkbox" name="include_name">--}}
                                        {{--<br>--}}
                                        {{--<hr>--}}

                                        <input type="submit" name="save" value="Create" class="btn btn-success">
                                    </form>
                                </div>
                            </div>
                            <a href="#custom-modal" class="btn btn-primary waves-effect waves-light m-r-5 m-b-10"
                               data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200"
                               data-overlaycolor="#36404a">Create Link</a>
                            <div class="table-wrap">
                                <table class="table table-hover">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                            colspan="1">
                                            Campaign Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                            colspan="1">
                                            URL
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                            colspan="1">
                                            Edit
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                            colspan="1">
                                            Act
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($trackers as $tracker)
                                        <tr>
                                            <td>{{$tracker->name}}</td>
                                            <td>
                                                {{$tracker->campaign_link}}?ref={{$tracker->ref }} <a href="#links_{{$tracker->id}}"
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
                                                        <div class="table-wrap">
                                                            <table class="table table-hover">
                                                                <tr>
                                                                    <th>Link</th>
                                                                    {{--<th>Language</th>--}}
                                                                    <th>Copy</th>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                               value="{{$tracker->campaign_link}}?ref={{$tracker->ref}}"
                                                                               readonly>
                                                                    </td>
                                                                    {{--<td>{{strtoupper($domain->lang)}}</td>--}}
                                                                    <td>
                                                                        <a href="#" class="btn btn-primary clipboard">Copy</a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#edit_{{$tracker->id}}" class="btn btn-info btn-sm btn-rounded"
                                                   data-animation="fadein" data-plugin="custommodal"
                                                   data-overlayspeed="200"
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
                                                             <h4>Campaign Link</h4>
                                                            <input type="text" class="form-control" name="campaign_link"
                                                                   value="{{$tracker->campaign_link}}">       
                                                            <br>
                                                            <input type="submit" name="save" value="Save"
                                                                   class="btn btn-info">
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route('affiliates.marketingMaterial', $tracker->id)}}"
                                                   class="btn btn-info">Marketing material</a>
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
    </div>
@endsection

