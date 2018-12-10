@extends('layouts.admin')

@section('title')
    {{ucfirst('Settings')}}
@endsection

@section('preJs')
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form method="POST" enctype="multipart/form-data">
                                <table class="table table-hover">
                                    @foreach($settings as $setting)
                                        <tr>
                                            <td>{{ $setting->name }}</td>
                                            <td>
                                                <select name="{{ $setting->code }}" class="form-control">
                                                    @foreach($definitionSettings as $key => $definitionSetting)
                                                        @if((int)$setting->value === (int)$key)
                                                            <option value="{{ $key }}"
                                                                    selected>{{ implode(",", $definitionSetting) }}</option>
                                                        @else
                                                            <option value="{{ $key }}">{{  implode(",", $definitionSetting) }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><input type="submit" value="Save" class="btn btn-success"></td>
                                        <td>
                                            <a class="btn btn-primary" href="/admin"
                                               role="button">Back</a>
                                        </td>
                                    </tr>
                                </table>
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
@endsection
