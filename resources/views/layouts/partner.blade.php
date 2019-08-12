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
    <link rel="shortcut icon" href="/partner/img/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="/partner/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/partner/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/partner/img/apple-touch-icon-114x114.png">
    <link rel="stylesheet" type="text/css" href="/partner/fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" type="text/css"  href="/partner/css/bootstrap.css"> -->
  

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">

    <!-- Stylesheet
        ================================================== -->
    <link rel="stylesheet" type="text/css" href="/partner/css/style.css?v={{time()}}">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="page-top"  {!! \Helpers\GeneralHelper::isTestMode() ? 'style="border:#cccc00 dashed"' : '' !!}>


<div class="pageWrap">
<header class="headerWrap">
    <div class="leftTop">			
        <div class="logoWrap">
            <a href="/" class="logoPc">
                <img src="/partner/img/casinobit_logo_white_empty.svg" alt="logo">
                <span class="svgWrap">
                    <svg id="anim" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        x="0px" y="0px" viewBox="0 0 1250 110" enable-background="new 0 0 1250 110" xml:space="preserve" class="">
                        <path fill="#fff" d="M398.7,39.9c-2.6,0-5.1,0.4-7.5,1.2c8-8.9,7.2-22.6-1.7-30.6s-22.6-7.2-30.6,1.7c-7.4,8.2-7.4,20.7,0,29
                                    c-2.4-0.8-5-1.2-7.5-1.2c-13.5,0-24.5,10.9-24.5,24.4s10.9,24.5,24.4,24.5c9.6,0,18.4-5.6,22.3-14.3c-3.7,13.8-14.3,24.8-28,28.8
                                    l1.5,1.7H403l1.5-1.7c-13.7-4.1-24.3-15-28-28.8c5.6,12.3,20.1,17.7,32.4,12.1c12.3-5.6,17.7-20.1,12.1-32.4
                                    C417,45.5,408.3,39.9,398.7,39.9z"></path>
                        <path fill="#fff" d="M875,5c-3.1,8.1-10.5,18.8-20.9,29.1C843.8,44.5,833.1,51.9,825,55c8.1,3.1,18.8,10.5,29.1,20.9
                                    C864.5,86.2,871.9,97,875,105c3.1-8.1,10.5-18.8,20.9-29.1C906.2,65.5,917,58.1,925,55c-8.1-3.1-18.8-10.5-29.1-20.9
                                    C885.5,23.8,878.1,13.1,875,5z"></path>
                        <path fill="#fff" d="M1147.7,52.4c6.2-3.4,9.4-8.6,9.4-15.6c0-10.7-5.9-18.2-19.5-19.9V5h-12.4v11.3c-2,0-2.9,0-9.5,0V5h-12.4
                                    v11.3h-13.1v77.4h13.1V105h12.4V93.7h9.5V105h12.4V93.3c14.7-1.4,22.2-10,22.2-22.8C1159.8,61.3,1156.1,55.4,1147.7,52.4z
                                    M1133.9,40.5c0,8-7.2,6.7-19.6,6.7V33.8C1128.2,33.8,1133.9,33.1,1133.9,40.5z M1114.3,75.6V61.3c13.7,0,22.1-1.3,22.1,7.1
                                    C1136.4,77.1,1127.8,75.6,1114.3,75.6L1114.3,75.6z"></path>
                        <path fill="#fff" d="M625,22.8c-4.9-23.7-44.4-25-53.4,3.3C557.3,70.7,615.2,84.9,625,105c9.8-20.1,67.7-34.3,53.5-78.9
                                    C669.4-2.1,629.9-0.8,625,22.8z"></path>
                        <path fill="#fff" d="M125,5c-8.2,17-57.2,29-45.2,66.7c7.1,22.3,36.7,22.8,44,6.5c-3.1,12.2-12.3,21.8-24.4,25.4l1.3,1.4h48.5
                                    l1.3-1.5c-12-3.6-21.3-13.2-24.4-25.4c7.3,16.4,36.9,15.8,44-6.5C182.2,34,133.3,22,125,5z"></path>
                    </svg>
                </span>
            </a>
        </div>
        <a href="#" class="menu-btn"><span></span></a>
        <div class="topNavWrap">
            <nav class="topNav">	
                <ul class="nav">
                    <li class="active"><a href="#main" class="page-scroll">Main page</a></li>
                    <li><a href="#about" class="page-scroll">About us</a></li>
                    <li><a href="#payouts" class="page-scroll">Payment</a></li>
                    <li><a href="#marketing" class="page-scroll">Marketing</a></li>
                    <li><a href="#profitable" class="page-scroll">Benefits</a></li>
                    <!-- <li><a href="#faq" class="page-scroll">FAQ</a></li> -->
                    <li><a href="#contacts" class="page-scroll">Contact us</a></li>
                </ul>				   
            </nav>
            <div class="btnWrapMob">
                <button class="btn small empty " data-toggle="modal" data-target="#myModal">LOG IN</button>
                <button class="btn small" data-toggle="modal" data-target="#myModal2">GET STARTED</button>
            </div>				
        </div>
    </div>
    <div class="rightTop">
        <div class="langSwitch">
            <div class="currlang">
                <img src="/partner/img/en.png" alt="">
            </div>
            <div class="langItems">
                <a href="#"><img src="/partner/img/en.png" alt=""></a>
            </div>
        </div>
        <div class="btnWrapPc">
            <button class="btn small empty " data-toggle="modal" data-target="#myModal">LOG IN</button>
            <button class="btn small" data-toggle="modal" data-target="#myModal2">GET STARTED</button>
        </div>
    </div>
</header>









@yield('content')

<!-- Footer Section -->
    <footer>
        <span class="copyRight">© 2003-2019 CasinoBit. All right reserved.</span>
    </footer>
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
                                            <div class="form-group checkWrap">
                                                <input type="checkbox" tabindex="3" class="" name="remember_me" id="remember">
                                                <label for="remember" class="checkLabel"></label> <span>Remember Me</span>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" value="Log In">
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
                                            {{--<div class="form-group">--}}
                                                {{--<input type="text" name="name" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>--}}
                                            {{--</div>--}}
                                            <div class="form-group">
                                                <input type="email" name="email"  tabindex="1" class="form-control" placeholder="Email Address" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" tabindex="2" class="form-control" placeholder="Password" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password_confirmation" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" required>
                                            </div>
                                            <input type="hidden" name="ref" value="{{request()->ref}}">
                                            <div class="form-group">
                                                <div class="row">
                                                    <!-- <div class="col-sm-6 col-sm-offset-3"> -->
                                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 15px;" value="Register Now">
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </form>
                                        <div class="error-lists" style="display: none">
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
           
        </div>
    </div>
</div>


{{--<div class="modal fade" id="myModal2" role="dialog">--}}

    {{--<div class="modal-dialog">--}}

        {{--<!-- Modal content-->--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                {{--<h4 class="modal-title">Register a CasinoBit Affiliate Account</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-6 col-md-offset-3">--}}
                        {{--<div class="panel panel-login">--}}
                            {{--<div class="panel-body">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-lg-12">--}}
                                        {{--<h4>Sorry for inconveniences. Registration now is not available. Leave your email for us to contact you as soon is it works.</h4>--}}
                                        {{--<br>--}}
                                        {{--<form id="register-form" action="/affiliates/register" method="post" role="form" style="display: block;">--}}
                                            {{--{{csrf_field()}}--}}
                                            {{--<div class="form-group">--}}
                                            {{--<input type="text" name="name" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group">--}}
                                                {{--<input type="email" name="email"  tabindex="1" class="form-control" placeholder="Email Address" value="" required>--}}
                                            {{--</div>--}}

                                            {{--<div class="form-group">--}}
                                                {{--<div class="row">--}}
                                                    {{--<!-- <div class="col-sm-6 col-sm-offset-3"> -->--}}
                                                    {{--<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; background-image: linear-gradient(to right, #fbb05c 0%, #ff6963 100%);width: 100%;" value="Get Notified">--}}
                                                    {{--<!-- </div> -->--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</form>--}}
                                        {{--<div class="error-lists" style="display: none">--}}
                                            {{--<ul class="error-lists">--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}


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
                                                    <input type="submit" name="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 15px;" value="Send">
                                                </div>
                                            </div>
                                        </form>
                                        <div class="error-lists" style="display: none">
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
           
        </div>
    </div>
</div>

<!-- Modal 4. Forgot password. Status send -->
<div class="modal fade" id="notificationMessage" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reset Your Password</h4>
            </div>
            <div class="modal-body">
                <p>The message sent your our email</p>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Affiliate Terms and Conditions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Updated: 01.06.2019 
        <br>
        <br>
<p>By completing the affiliate application to the CasinoBit.io Affiliate Program (the "Affiliate Program") and clicking " I agree to the Terms and Conditions (the "Terms") within the registration form, you (hereinafter the "Affiliate") hereby agree to abide by all the terms and conditions set out in this Agreement. The outlined commission structure in ARTICLE 19 of this Agreement shall be deemed to form an integral part thereof. CasinoBit.io reserves the right to amend, alter, delete or extend any provisions of this Agreement, at any time and at its sole discretion, without giving any advance notice to the Affiliate subject to the Terms set out in this Agreement. You hereby comply with:</p>      
        <ul>
        <li>1. The participation in the Affiliate Program</li>
    <li>2. The usage of the CasinoBit.io affiliate website and/or CasinoBit.io marketing tools (as hereafter defined).</li>
<li>3. The condition that the acceptance of any affiliate commissions from CasinoBit confirms your irrevocable acceptance of this Agreement and any modifications thereto.</li>
        </ul>
  
<p>Therefore, you shall be obliged to continuously comply with the Terms of this Agreement as well as to comply with the General Terms and Conditions and Privacy Policy of the website CasinoBit.io, in addition to any other, from time to time, brought rules and/or guidelines. An Agreement between the Company and the Affiliate shall be coming into effect on the date when the affiliate application was approved.</p>

<h3 class="itemTitle">1. Purpose:<i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>1.1 The Affiliate maintains and operates one or more websites on the Internet (hereinafter collectively referred to as "the Website"), and/or refers potential customers through other channels.</p>
<p>1.2 This Agreement governs the terms and conditions which are related to the promotion of the website CasinoBit.io by the Affiliate, hereinafter referred to as "CasinoBit.io", whereby the Affiliate will be paid a commission as defined in this Agreement depending on the traffic sent to CasinoBit.io and as subject to the Terms of this Agreement.</p>
<p>1.3 The definition of the term Net Gaming Revenue can be found within ARTICLE 19 of the Terms. In case of an introduction of another product, or group of products in the future, CasinoBit.io reserves the right to use an individual definition of the term Net Gaming Revenue for each product.</p>
</div>
<h3 class="itemTitle">2. Acceptance of an Affiliate: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>2.1 The Company reserves the right to refuse any registration in its sole and absolute discretion.</p>
</div>

<h3 class="itemTitle">3. Qualifying Conditions:<i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>3.1 The Affiliate hereby warrants that he/she:</p>
<ul>
<li>a) Is of legal age in the applicable jurisdiction in order to agree to and to enter into an Agreement.</li>
<li>b) Is competent and duly authorized to enter into binding Agreements.</li>
<li>c) Is the proprietor of all rights, licenses and permits to market, promote and advertise CasinoBit.io in accordance with the provisions of this Agreement.</li>
<li>d) Will comply with all applicable rules, laws and regulations in correlation with the promotion of CasinoBit.io.</li>
<li>e) Fully understands and accepts the Terms of the Agreement.</li>
</ul>
<br>
</div>

<h3 class="itemTitle">4. Responsibilities and Obligations of the Company: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>4.1 The Company shall provide the Affiliate with all required information and marketing material for the implementation of the tracking link.</p>
<p>4.2 The Company shall administrate the turnover generated via the tracking links, record the revenue and the total amount of commission earned via the link, provide the Affiliate with commission statistics, and handle all customer services related to the business. An unique tracking identification code/s will be assigned to all referred customers.</p>
<p>4.3 The Company shall pay the Affiliate the amount due depending on the traffic generated subject to the Terms of this Agreement.</p>
</div>

<h3 class="itemTitle">5. Responsibilities and Obligations of the Affiliate:<i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>5.1 The Affiliate hereby warrants:</p>
<ul>
    <li>a) To use its best efforts to actively and effectively advertise, market and promote CasinoBit.io as widely as possible in order to maximize the benefit to the parties and to abide by the guidelines of the Company as they may be brought forward from time to time and/or as being published online.</li>
<li>b) To market and refer potential players to CasinoBit.io at its own cost and expense. The Affiliate will be solely responsible for the distribution, content and manners of its marketing activities. All of the Affiliate's marketing activities must be professional, proper and lawful under applicable laws and must be in accordance with this Agreement.</li>
    <li>c) To use only a tracking link provided within the scope of the Affiliate Program, otherwise no warranty whatsoever can be assumed for proper registration and sales accounting. Also, not to change or modify in any way any link or marketing material without prior written authorization from the Company.</li>
<li>d) To be responsible for the development, the operation, and the maintenance of its website as well as for all material appearing on its website.</li>
</ul>
<p>5.2 The Affiliate hereby warrants:</p>
<ul>
    <li>a) That it will not perform any act which is libelous, discriminatory, obscene, unlawful or otherwise unsuitable or which contains sexually explicit, pornographic, obscene or graphically violent materials.</li>
<li>b) That it will not actively target any person who is under the legal age for gambling.</li>
<li>c) That it will not actively target any jurisdiction where gambling and the promotion thereof is illegal.</li>
<li>d) That it will not generate traffic to CasinoBit.io by illegal or fraudulent activity, particularly but not limited to by:</li>
</ul>
<p>I. Sending spam.</p>
<p>II. Incorrect meta-tags.</p>
<p>III. Registering as a player or making deposits directly or indirectly to any player account through their tracker(s) for their own personal use and/or the use of their relatives, friends, employees or other third parties, or in any other way attempt to artificially increase the commission payable or to otherwise defraud the Company. Violation of this provision shall be deemed to be fraud.</p>
<ul>
    <li>e) That it will not present its website in such a way that it might evoke any risk of confusion with CasinoBit.io and/or the Company and or convey the impression that the website of the contracting party is partly or fully originated with CasinoBit.io and/or the Company.</li>
<li>f) Without prejudice to the marketing material as may be forwarded by the Company and/or made available online through the website Affiliates CasinoBit the affiliate may not use CasinoBit.io or other terms, trademarks and other intellectual property rights that are vested in the Company unless the Company consents to such use in writing.</li>
<li>g) That “PPC and Keyword Bidding” on brand and or brand related terms is not allowed. PPC and Keyword Bidding for terms identical or similar to any of the brands, brand names, URLs (casinobit.io, casinobit.com) are strictly prohibited.</li>
</ul>
<br>
</div>


<h3 class="itemTitle">6. Payment: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>6.1 The Company agrees to pay the Affiliate a commission based on the Net Gaming Revenue generated from new customers referred by the Affiliate's website and/or other channel. New customers are those customers of the Company who do not yet have a gaming account and who access the Website via the tracking link and who properly register and make bitcoin transfers at least equivalent to the minimum deposit into their CasinoBit.io betting account. The commission shall be deemed to be inclusive of value added tax or any other tax if applicable.</p>
<p>6.2 The commission shall be a percentage of the Net Gaming Revenue (NGR) of the in accordance with what is set out in the commission structures for the particular product. The calculation is product specific and it is set out in every product-specific commission structure. </p>
<p>6.3 The commission is calculated at the end of each month and payments shall be performed by the 5th (fifth) day of each calendar month. Commission rate is defined by the previous month’s performance.</p>
<p>d) If this Agreement is terminated by the Company on the basis of the Affiliate's breach, the Company shall be entitled to withhold the Affiliate's earned but unpaid commissions as of the termination date as collateral for any claim arising from such breach. It is further specified that termination by the Company due to a breach by the Affiliate of any of the clauses in this Agreement shall not require a notice period and such termination shall have immediate effect upon simple notification by the Company to the Affiliate.</p>
<p>e) The Affiliate must return to the Company any and all confidential information (and all copies and derivations thereof) in the Affiliate's possession, custody and control.</p>
<p>f) The Affiliate will release the Company from all obligations and liabilities occurring or arising after the date of such termination, except with respect to those obligations that by their nature are designed to survive termination. Termination will not relieve the Affiliate from any liability arising from any breach of this Agreement, which occurred prior to termination and/or to any liability arising from any breach of confidential information even if the breach arises at any time following the termination of this Agreement. The Affiliate's obligation of confidentiality towards the Company shall survive the termination of this Agreement.</p>
</div>

<h3 class="itemTitle">7.Termination:<i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
    <p>7.1 This Agreement may be terminated by Casinobit.io due to breach of contract, or by affiliate by giving a thirty (30) day written notification to the other party. Written notification may be given by an email.</p>
    <p>7.2 The contracting parties hereby agree that upon the termination of this Agreement:</p>
    <p>a) The Affiliate must remove all references to Casinobit.io from the Affiliate's websites and/or other marketing channel and communications, non-respectively of whether the communications are commercial or non-commercial.</p>
    <p>b) All rights and licenses granted to the Affiliate under this Agreement shall immediately terminate and all rights shall revert to the respective licencors, and the Affiliate will cease the use of any trademarks, service marks, logos and other designations vested in the Company.</p>
<p>c) The Affiliate will be entitled only to those earned and unpaid commissions as of the effective date of termination. However, the Company may withhold the Affiliate's final payment for a reasonable time to ensure that the correct amount is paid. The Affiliate will not be eligible to earn or receive commissions after this termination date.</p>
<p>d) If this Agreement is terminated by the Company on the basis of the Affiliate's breach, the Company shall be entitled to withhold the Affiliate's earned but unpaid commissions as of the termination date as collateral for any claim arising from such breach. It is further specified that termination by the Company due to a breach by the Affiliate of any of the clauses in this Agreement shall not require a notice period and such termination shall have immediate effect upon simple notification by the Company to the Affiliate.</p>

<p>e) The Affiliate must return to the Company any and all confidential information (and all copies and derivations thereof) in the Affiliate's possession, custody and control.</p>
<p>f) The Affiliate will release the Company from all obligations and liabilities occurring or arising after the date of such termination, except with respect to those obligations that by their nature are designed to survive termination. Termination will not relieve the Affiliate from any liability arising from any breach of this Agreement, which occurred prior to termination and/or to any liability arising from any breach of confidential information even if the breach arises at any time following the termination of this Agreement. The Affiliate's obligation of confidentiality towards the Company shall survive the termination of this Agreement.</p>

</div>

<h3 class="itemTitle">8. Warranties: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>8.1 The Affiliate expressly acknowledges and agrees that the use of the Internet is at its risk and that this affiliate program is provided "as is" and "as available" without any warranties or conditions whatsoever, express or implied. No guarantee is made that it will make access to its website possible at any particular time or any particular location.</p>
<p>8.2 The Company shall in no event be liable to the Affiliate or anyone else for any inaccuracy, error or omission in, or loss, injury or damage caused in whole or in part by failures, delays or interruptions of the www.CasinoBit.io website or the affiliate program.</p>
</div>

<h3 class="itemTitle">9. Indemnification: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>9.1 The Affiliate agrees to defend, indemnify and hold the Company and its affiliates, successors, officers, employees, agents, directors, shareholders and attorneys, free and harmless from and against any and all claims and liabilities, including reasonable attorneys' and experts' fees, related to or arising from:</p>

<p>a) Any breach of the Affiliate's representations, warranties or covenants under this Agreement.</p>
<p>b) The Affiliate's use (or misuse) of the marketing materials.</p>
<p>c) All conduct and activities occurring under the Affiliate's user ID and password.</p>
<p>d) Any defamatory, libelous or illegal material contained within the Affiliate's website or as part of the Affiliate's information and data.</p>
<p>e) Any claim or contention that the Affiliate's website or the Affiliate's information and data infringes any third party's patent, copyright, trademark, or other intellectual property rights or violates any third party's rights of privacy or publicity.</p>
<p>f) Third party access or use of the Affiliate's website or to the Affiliate's information and data.</p>
<p>g) Any claim related to the Affiliate website.</p>
<p>h) Any violation of this Agreement.</p>
<p>9.2 The Company reserves the right to participate, at its own expense in the defense of any matter.</p>
</div>


<h3 class="itemTitle">10. Company Rights:<i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>10.1 The Company and/or CasinoBit.io may refuse any player or close a player's account if it is necessary to comply with the Company's and/or CasinoBit.io's policy and/or protect the interest of the Company.</p>
<p>10.2 The Company may refuse any applicant and/or may close any Affiliate's account if it is necessary to comply with the Company's policy and/or protect the interest of the Company. If the Affiliate is in breach of this Agreement or the Company's Terms or other rules, policies and guidelines of the Company, the Company may, besides closing the Affiliate's account take any other steps at law to protect its interest.</p>
</div>

<h3 class="itemTitle">11. Assignment: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>11.1 The Affiliate may not assign this Agreement, by operation of law or otherwise, without obtaining the prior written consent of the Company. In the event that the affiliate acquires or otherwise obtains control of another affiliate of CasinoBit.io, then accounts will coexist on individual terms.</p>
<p>11.2 The Company may assign this Agreement, by operation of the law or otherwise, at any time without obtaining the prior consent of the Affiliate.</p>
</div>

<h3 class="itemTitle">12. Non-Waiver: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>12.1 The Company's failure to enforce the Affiliate's adherence to the Terms outlined in this Agreement shall not constitute a waiver of the right of the Company to enforce said terms at any time.</p>
</div>

<h3 class="itemTitle">13. Force Majeure: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>13.1 Neither party shall be liable to the other for any delay or failure to perform its obligations under this Agreement if such delay or failure arises from a cause beyond the reasonable control of and is not the fault of such party, including but not limited to labor disputes, strikes, industrial disturbances, acts of God, acts of terrorism, floods, lightning, utility or communications failures, earthquakes or other casualties. If a force majeure event occurs, the non-performing party is excused from whatever performance is prevented by the force majeure event to the extent prevented. Provided that, if the force majeure event subsists for a period exceeding thirty (30) days then either party may terminate the Agreement without notice.</p>
</div>

<h3 class="itemTitle">14. Relationship of the Parties:<i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>14.1 Nothing contained in this Agreement, nor any action taken by any party to this Agreement, shall be deemed to constitute either party (or any of such party's employees, agents, or representatives) an employee, or legal representative of the other party, nor to create any partnership, joint venture, association, or syndication among or between the parties, nor to confer on either party any express or implied right, power or authority to enter into any Agreement or commitment on behalf of (nor to impose any obligation upon) the other party.</p>
</div>

<h3 class="itemTitle">15. Severability / Waiver:<i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
    <p>15.1 Whenever possible, each provision of this Agreement shall be interpreted in such a manner as to be effective and valid under applicable law but, if any provision of this Agreement is held to be invalid, illegal or unenforceable in any respect, such provision will be ineffective only to the extent of such invalidity, or unenforceability, without invalidating the remainder of this Agreement. No waiver will be implied from conduct or failure to enforce any rights and must be in writing to be effective.</p>
</div>

<h3 class="itemTitle">16. Confidentiality: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
    <p>16.1 All information, including but not limited to business and financial, lists of customers and buyers, as well as price and sales information and any information relating to products, records, operations, business plans, processes, product information, business know-how or logic, trade secrets, market opportunities and personal data of the Company shall be treated confidentially. Such information must not be used for own commercial or other purposes or divulged to any person or third party neither direct nor indirectly unless the prior explicit and written consent of the Company This provision shall survive the termination of this Agreement.</p>
<p>16.2 The Affiliate obliges himself not to use the confidential information for any purpose other than the performance of its obligations under this Agreement.</p>
</div>

<h3 class="itemTitle">17. Changes to this Agreement: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
    <p>17.1 The Company reserves the right to amend, alter, delete or add to any of the provisions of this Agreement, at any time and at its sole discretion, without giving any advance notice to the Affiliate subject to the Terms set out in this Agreement. Any such changes will be published on CasinoBit.io.</p>
<p>17.2 In case of any discrepancy between the meanings of any translated versions of this Agreement, the English language version shall prevail.</p>
</div>

<h3 class="itemTitle">18. Trademarks:<i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
    <p>18.1 Nothing contained in this Agreement will grant either party any right, title or interest in the trademarks, trade names, service marks or other intellectual property rights [hereinafter referred to simply as 'Marks'] of the other party. At no time during or after the term will either party attempt or challenge or assist or allow others to challenge or to register or to attempt to register the Marks of the other party or of any company within the group of companies of the other party. Provided also that neither of the parties will register nor attempt to register any Mark which is basically similar to and/or confusingly similar to any Mark which belongs to the other party or to any company contained within the other party's group of companies.</p>
</div>

<h3 class="itemTitle">19. Commissions are paid out as a percentage of the Net Gaming Revenue (NGR). <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>Net Gaming Revenue = Bets - Wins - Bonuses - Admin Fee (18%)
</p>
<p>The Admin Fee is a value which contains the License Fee, Game Provider Fee and Platform Fee (casinobit.io charges with 18% a very competitive fee in this industry).</p>
</div>

</div>
 
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

@section('js')
    <script type="text/javascript" src="/partner/js/jquery.1.11.1.js"></script>
    <script type="text/javascript" src="/partner/js/bootstrap.js"></script>
    {{--<script type="text/javascript" src="/partner/js/SmoothScroll.js"></script>--}}
    <script type="text/javascript" src="/partner/js/jqBootstrapValidation.js"></script>
    {{--<script type="text/javascript" src="/partner/js/contact_me.js"></script>--}}
    <script type="text/javascript" src="/partner/js/main.js?v=2"></script>
    <script type="text/javascript" src="/partner/js/leanding.js?v={{time()}}"></script>
@show
</body>
</html>