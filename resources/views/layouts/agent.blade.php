<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

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


    <link href="/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet">

    <link href="/assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet">
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- front style -->
    <link href="/partnerPanel/css/front.css" rel="stylesheet">
    <!-- <script src="/partnerPanel/js/front.js"></script> -->

    <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.13/clipboard.min.js"></script>

    <script src="/assets/js/modernizr.min.js"></script>
    <script>
        var errors = [];
        var success_msg = false;
    </script>
</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <a href="{{route('agent.dashboard')}}" class="logo"><span>Casino<span>Bit</span></span><i class="zmdi zmdi-layers"></i></a>
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

                <!-- Right(Notification and Searchbox -->
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <!-- Notification -->
                        <div class="notification-box">
                            <ul class="list-inline m-b-0">
                                <li>
                                    <a href="javascript:void(0);" class="right-bar-toggle">
                                        <i class="zmdi zmdi-notifications-none"></i>
                                    </a>
                                    <div class="noti-dot">
                                        <span class="dot"></span>
                                        <span class="pulse"></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- End Notification bar -->
                    </li>
                    <li class="hidden-xs">
                        <form role="search" class="app-search">
                            <input type="text" placeholder="Search..."
                                   class="form-control">
                            <a href=""><i class="fa fa-search"></i></a>
                        </form>
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

                    <li>
                        <a href="{{route('agent.dashboard')}}" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> </a>
                    </li>
                    <li>
                        <a href="{{route('agent.transactions')}}" class="waves-effect"><i class="ti-package"></i> <span> Transactions </span> </a>
                    </li>
                    <li>
                        <a href="{{route('agent.trackers')}}" class="waves-effect"><i class="ti-target"></i> <span> Links </span> </a>
                    </li>
                    <li>
                        <a href="{{route('agent.banners')}}" class="waves-effect"><i class="ti-palette"></i> <span> Banners </span> </a>
                    </li>
                    <li>
                        <a href="{{route('agent.withdraw')}}" class="waves-effect"><i class="ti-money"></i> <span> Withdraw </span> </a>
                    </li>
                    <li>
                        <a href="{{route('agent.faq')}}" class="waves-effect"><i class="ti-help-alt"></i> <span> FAQ </span> </a>
                    </li>
                    <li>
                        <a href="{{route('affiliates.logoutMain')}}" class="waves-effect"><i class="ti-close"></i><span> Logout </span></a>
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

<!-- jQuery  -->
<script src="/assets/js/jquery.min.js"></script>
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

<script src="/assets/plugins/bootstrap-sweetalert/sweet-alert.js"></script>
<script src="/assets/pages/jquery.sweet-alert.init.js"></script>

@include('agent.errors')

@yield('js')
<script>
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

    //new Clipboard('.clipboard');

    new Clipboard('.clipboard', {
        target: function(trigger) {
            return $(trigger).parent().parent().find('input')[0];
        }
    });

</script>

</body>
</html>
