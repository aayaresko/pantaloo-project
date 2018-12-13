<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CasinoBit Affiliates - @yield('title')</title>
    <meta name="description" content="@yield('description', '')">
    <meta name="keywords" content="@yield('keywords', '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons
        ================================================== -->
    <link rel="shortcut icon" href="/partner/img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/partner/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/partner/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/partner/img/apple-touch-icon-114x114.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css"  href="/partner/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/partner/fonts/font-awesome/css/font-awesome.css">

    <!-- Stylesheet
        ================================================== -->
    <link rel="stylesheet" type="text/css" href="/partner/css/style.css">
    <link rel="stylesheet" type="text/css" href="/partner/css/nivo-lightbox/nivo-lightbox.css">
    <link rel="stylesheet" type="text/css" href="/partner/css/nivo-lightbox/default.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
<!-- Navigation
    ==========================================-->
<nav id="menu" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand page-scroll" href="/" style="padding: 0 !important;"><img src="/partner/img/logo-affiliate-casinobit.png" style="width: 230px; height: auto;" /></a> </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <!-- <li><a href="#features" class="page-scroll">Features</a></li> -->
                <li><a href="#about" class="page-scroll">About</a></li>
                <li><a href="#services" class="page-scroll">Services</a></li>
                <!-- <li><a href="#portfolio" class="page-scroll">Gallery</a></li> -->
                <li><a href="#advantages" class="page-scroll">Advantages</a></li>
                <li><a href="#commissions" class="page-scroll">Commissions Structure</a></li>
                <li><a href="#contact" class="page-scroll">Contact</a></li>
                <button class="btn btn-custom btn-lg page-scroll" data-toggle="modal" data-target="#myModal" style="margin-top: 13px;padding: 5px 15px;">Login</button>
                <button class="btn btn-custom btn-lg page-scroll" data-toggle="modal" data-target="#myModal2" style="margin-top: 13px;padding: 5px 15px;background-image: linear-gradient(to right, #fbb05c 0%, #ff6963 100%);">Register</button>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
</nav>

@yield('content')

<!-- Footer Section -->
<div id="footer">
    <div class="container text-center">
        <p>&copy; 2018 CasinoBit Affiliates. Design by <a href="https://partners.casinobit.io" rel="nofollow">US</a></p>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Log to your CasinoBit Affiliate Account</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-login">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form id="login-form" action="/affiliates/login" method="post" role="form" style="display: block;">
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <input type="text" name="email"  tabindex="1" class="form-control" placeholder="Email" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" tabindex="2" class="form-control" placeholder="Password" required>
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="checkbox" tabindex="3" class="" name="remember_me" id="remember">
                                                <label for="remember"> Remember Me</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; width: 100%;" value="Log In">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="text-center">
                                                            <a href="#recover" data-toggle="modal" data-target="#myModal3" data-dismiss="modal" tabindex="5" class="forgot-password">Forgot Password?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="error-lists" style="display: none">
                                            <p>Errors:</p>
                                            <ul class="error-lists">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2 -->
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Register a CasinoBit Affiliate Account</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-login">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form id="register-form" action="/affiliates/register" method="post" role="form" style="display: block;">
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <input type="text" name="name" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="email" name="email"  tabindex="1" class="form-control" placeholder="Email Address" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" tabindex="2" class="form-control" placeholder="Password" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password_confirmation" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" required>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <!-- <div class="col-sm-6 col-sm-offset-3"> -->
                                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; background-image: linear-gradient(to right, #fbb05c 0%, #ff6963 100%);width: 100%;" value="Register Now">
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </form>
                                        <div class="error-lists" style="display: none">
                                            <p>Errors:</p>
                                            <ul class="error-lists">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3. Forgot password -->
<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Forgot Your Password</h4>
            </div>
            <div class="modal-body">
                <p>Don't worry. Resseting your password is easy, just tell us the email address.</p>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-login">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form  id="reset-password-form" action="/affiliates/password/email" method="post" role="form" style="display: block;">
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <input type="email" name="email" tabindex="1" class="form-control" placeholder="Email Address" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <input type="submit" name="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; background-image: linear-gradient(to right, #fbb05c 0%, #ff6963 100%);width: 100%;" value="Send">
                                                </div>
                                            </div>
                                        </form>
                                        <div class="error-lists" style="display: none">
                                            <p>Errors:</p>
                                            <ul class="error-lists">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4. Forgot password. Status send -->
<div class="modal fade" id="myModal4" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Status Window</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Itaque cupiditate facilis veritatis tempora dicta, consectetur repellat, omnis nulla aliquid molestiae tempore incidunt nesciunt at maxime animi officiis unde numquam qui?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript" src="/partner/js/jquery.1.11.1.js"></script>
    <script type="text/javascript" src="/partner/js/bootstrap.js"></script>
    <script type="text/javascript" src="/partner/js/SmoothScroll.js"></script>
    <script type="text/javascript" src="/partner/js/nivo-lightbox.js"></script>
    <script type="text/javascript" src="/partner/js/jqBootstrapValidation.js"></script>
    {{--<script type="text/javascript" src="/partner/js/contact_me.js"></script>--}}
    <script type="text/javascript" src="/partner/js/main.js"></script>
    <script type="text/javascript" src="/partner/js/leanding.js?v={{time()}}"></script>
@show
</body>
</html>