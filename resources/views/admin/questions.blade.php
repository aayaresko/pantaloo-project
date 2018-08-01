<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @foreach($questions as $question)
                                <div class="panel panel-default bx-shadow-none">
                                    <div class="panel-heading" role="tab" id="HQ_{{$question->id}}">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#Q_{{$question->id}}" aria-expanded="false" aria-controls="Q_{{$question->id}}" class="collapsed">
                                                {{$question->question}}
                                                @if(Auth::user()->isAdmin())
                                                    <a href="{{route('faqEdit', $question)}}" class="btn btn-info btn-xs">Edit</a>
                                                    <a href="{{route('faqDelete', $question)}}" class="btn btn-danger btn-xs">Delete</a>
                                                @endif
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="Q_{{$question->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="HQ_{{$question->id}}" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                            {!! $question->getAnswer() !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>