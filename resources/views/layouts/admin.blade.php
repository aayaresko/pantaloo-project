<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <title>@yield('title')</title>

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="/assets/plugins/morris/morris.css">

    <!-- App css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="/css/select2.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet">

    <link href="/assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet">

    <link href="/assets/plugins/fileuploads/css/dropify.css" rel="stylesheet">

    <!-- front style -->
    <link href="/adminPanel/css/front.css" rel="stylesheet">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- X-editable css -->
    <link type="text/css" href="/assets/plugins/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">

    <link type="text/css" href="/assets/plugins/switchery/switchery.min.css" rel="stylesheet">

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="/assets/js/modernizr.min.js"></script>
    <script>
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        var errors = [];
        var success_msg = false;
    </script>

    <!-- jQuery  -->
    <script src="/assets/js/jquery.min.js"></script>
    @yield('preJs')
</head>

<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <a href="{{route('dashboard')}}" class="logo"><span>Casino<span>Bit</span></span><i class="zmdi zmdi-layers"></i></a>
        </div>

        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">

                <!-- Page title -->
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <button class="button-menu-mobile open-left">
                            <i class="zmdi zmdi-menu"></i>
                        </button>
                    </li>
                    <li>
                        <h4 class="page-title">@yield('title')</h4>
                    </li>
                </ul>


            </div><!-- end container -->
        </div><!-- end navbar -->
    </div>
    <!-- Top Bar End -->


    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">


            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <ul>
                    <li class="text-muted menu-title">Navigation</li>



                    @can('accessUserAdmin')

                        <li>
                            <a href="{{route('dashboard')}}" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> </a>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-btc"></i> <span> Finance </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('admin.balance')}}">Balance</a></li>
                                <li><a href="{{route('admin.transactions')}}">Transactions</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-users"></i> <span> Users </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('users')}}">Accounts</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-user-secret"></i> <span> Agent </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('admin.agents')}}">Accounts</a></li>
                                <li><a href="{{route('admin.agentPayments')}}">Withdraws</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="ti-tablet"></i> <span> Slots </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('admin.slots')}}">List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="ti-tablet"></i> <span> Games </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('admin.integratedGames')}}">List</a></li>
                                <li><a href="{{route('admin.integratedTypes')}}">Types</a></li>
                                <li><a href="{{route('admin.integratedCategories')}}">Categories</a></li>
                                <li><a href="{{route('admin.integratedSettings')}}">Settings</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-collection-text"></i><span> Pages </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('pages')}}">List</a></li>
                                <li><a href="{{route('pages.new')}}">Create</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-bank"></i> <span> Payments </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('admin.bitcoin')}}">Bitcoin</a></li>
                                <li><a href="{{route('admin.transfers')}}">Transfers</a></li>
                                <li><a href="{{route('pending')}}">Withdraws</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-paint-brush"></i> <span> Banners </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('banners.create')}}">Create</a></li>
                                <li><a href="{{route('admin.banners')}}">List</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="ti-help-alt"></i> <span> FAQ </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('faqCreate')}}">Create</a></li>
                                <li><a href="{{route('admin.faq')}}">List</a></li>
                            </ul>
                        </li>
                    @endcan


                    <li>
                        <a href="{{route('translations')}}" class="waves-effect"><i class="zmdi zmdi-translate"></i> <span> Translations </span> </a>
                    </li>


                </ul>
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -->
            <div class="clearfix"></div>

        </div>

    </div>
    <!-- Left Sidebar End -->

@yield('content')

    <div class="side-bar right-bar">
        <a href="javascript:void(0);" class="right-bar-toggle">
            <i class="zmdi zmdi-close-circle-o"></i>
        </a>
        <h4 class="">Notifications</h4>
        <div class="notification-list nicescroll">
            <ul class="list-group list-no-border user-list">
                <li class="list-group-item">
                    <a href="#" class="user-list-item">

                        <div class="user-desc" style="width:100%;">
                            <span class="name">Michael Zenaty</span>
                            <span class="desc">There are new settings available</span>
                            <span class="time">2 hours ago</span>
                        </div>
                    </a>
                </li>

            </ul>
        </div>
    </div>
    <!-- /Right-bar -->

</div>
<!-- END wrapper -->



<script>
    var resizefunc = [];
</script>

<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/detect.js"></script>
<script src="/assets/js/fastclick.js"></script>
<script src="/assets/js/jquery.slimscroll.js"></script>
<script src="/assets/js/jquery.blockUI.js"></script>
<script src="/assets/js/waves.js"></script>
<script src="/assets/js/jquery.nicescroll.js"></script>
<script src="/assets/js/jquery.scrollTo.min.js"></script>

<!-- KNOB JS -->
<!--[if IE]>
<script type="text/javascript" src="/assets/plugins/jquery-knob/excanvas.js"></script>
<![endif]-->
<script src="/assets/plugins/jquery-knob/jquery.knob.js"></script>

{{--<!--Morris Chart-->--}}
{{--<script src="/assets/plugins/morris/morris.min.js"></script>--}}
{{--<script src="/assets/plugins/raphael/raphael-min.js"></script>--}}

{{--<!-- Dashboard init -->--}}
{{--<script src="/assets/pages/jquery.dashboard.js"></script>--}}
<script type="text/javascript" src="/assets/plugins/switchery/switchery.min.js"></script>
<!-- App js -->
<script src="/assets/js/jquery.core.js"></script>
<script src="/assets/js/jquery.app.js"></script>

<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="/assets/pages/datatables.init.js"></script>
<script src="/assets/plugins/custombox/src/js/custombox.js"></script>

<script src="/assets/plugins/toastr/toastr.min.js"></script>

<script src="/assets/plugins/fileuploads/js/dropify.min.js"></script>

<script src="/assets/plugins/bootstrap-sweetalert/sweet-alert.js"></script>
<script src="/assets/pages/jquery.sweet-alert.init.js"></script>
<script src="/assets/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

<!-- XEditable Plugin -->
<script src="/assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="/assets/plugins/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="/assets/pages/jquery.xeditable.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@include('agent.errors')

@yield('js')

<script>
    $('.dropify').dropify();

    sweetAlertInitialize();
    if(errors.length > 0) {
        msg = '';
        for(i = 0; i < errors.length; i = i + 1) {
            msg = msg + errors[i] + "\n";
            swal({title: "Errors", text: msg, html: true, type: "error"});
        }
    }

    if(success_msg)
    {
        swal({title: "Success", text: success_msg, html: true, type: "success"});
    }
</script>



</body>
</html>
