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

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css"  href="/partner/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="/partner/css/magnific-popup.css">   -->
    <link rel="stylesheet" type="text/css" href="/partner/fonts/font-awesome/css/font-awesome.css">

    <!-- Stylesheet
        ================================================== -->
    <link rel="stylesheet" type="text/css" href="/partner/css/style.css?v=996736">
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
                <li>
                <button type="button" class="btn popUpBtn" data-toggle="modal" data-target="#exampleModalLong">
                Terms and Conditions</button>
                </li>
                <li><a href="#about" class="page-scroll">About</a></li>
                <li><a href="#services" class="page-scroll">Services</a></li>
                <!-- <li><a href="#portfolio" class="page-scroll">Gallery</a></li> -->
                <li><a href="#advantages" class="page-scroll">Advantages</a></li>
                <!--li><a href="#commissions" class="page-scroll">Commissions Structure</a></li-->
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
                                            <div class="form-group">
                                                <div class="row">
                                                    <!-- <div class="col-sm-6 col-sm-offset-3"> -->
                                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; background-image: linear-gradient(to right, #fbb05c 0%, #ff6963 100%);width: 100%;" value="Register Now">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                                                    <input type="submit" name="register-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; background-image: linear-gradient(to right, #fbb05c 0%, #ff6963 100%);width: 100%;" value="Send">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<p>By completing the affiliate application to the CasinoBit.io Affiliate Program (hereafter referred to as the “Affiliate Program”) and clicking “I agree to the Terms and Conditions” (the “Terms”) within the registration form, you (the “Affiliate”) hereby agree to abide by all the Terms and Conditions set out in this Agreement. The outlined commission structure in the ARTICLE 19 of this Agreement shall be deemed to form an integral part thereof. CasinoBit.io reserves the right to amend, alter, delete or extend any provisions of this Agreement at any time and at its sole discretion, without giving any advance notice to the Affiliate subject to the Terms set out in this Agreement. You hereby comply with:
</p>       
        <ul>
        <li>1. The participation in the Affiliate Program.</li>
        <li>2. The usage of the CasinoBit.io affiliate website and/or CasinoBit.io marketing tools (as hereafter defined).
</li>
        <li>3. The condition that the acceptance of any affiliate commissions from CasinoBit.io confirms your irrevocable acceptance of this Agreement and any modifications thereto.
</li>
        </ul>
  
<p>Therefore, you shall be obliged to continuously comply with the Terms of this Agreement as well as to comply with the General Terms and Conditions and Privacy Policy of the CasinoBit.io website (hereafter referred to as the “CasinoBit.io”), in addition to any other rules and/or guidelines brought from time to time. The Agreement between the CasinoBit.io company and the Affiliate shall be coming into effect on the date when the affiliate application was approved.
</p>

<h3 class="itemTitle">1. Purpose: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>1.1 The Affiliate maintains and operates one or more websites on the Internet (hereafter collectively referred to as “the Website”), and/or refers potential customers through other channels.</p>
<p>1.2 This Agreement governs the Terms and Conditions which are related to the promotion of CasinoBit.io by the Affiliate, whereby the Affiliate will be paid a commission as defined in this Agreement depending on the traffic sent to CasinoBit.io and as subject to the Terms of this Agreement.</p>
<p>1.3 The definition of the term “Net Gaming Revenue” can be found within the ARTICLE 19 of the Terms. In case of an introduction of another product, or group of products in the future, CasinoBit.io reserves the right to use an individual definition of the term “Net Gaming Revenue” for each product.</p>
</div>
<h3 class="itemTitle">2. Acceptance of an Affiliate: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>2.1 CasinoBit.io reserves the right to refuse any registration in its sole and absolute discretion.</p>
</div>

<h3 class="itemTitle">3. Qualifying Conditions: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>3.1 The Affiliate hereby warrants that he/she:</p>
<ul>
<li>a) Is of legal age in the applicable jurisdiction in order to agree to and to enter into the Agreement.</li>
<li>b) Is competent and duly authorized to enter into the binding Agreements.</li>
<li>c) Is the proprietor of all rights, licenses and permits to market, promote and advertise CasinoBit.io in accordance with the provisions of this Agreement.</li>
<li>d) Will comply with all applicable rules, laws and regulations in correlation with the promotion of CasinoBit.io.</li>
<li>e) Fully understands and accepts the Terms of the Agreement.</li>
</ul>
</div>

<h3 class="itemTitle">4. Responsibilities and Obligations of CasinoBit.io: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>4.1 CasinoBit.io shall provide the Affiliate with all required information and marketing material for the implementation of the tracking link.</p>
<p>4.2 CasinoBit.io shall administer the turnover generated via the tracking links, record the revenue and the total amount of commission earned via the link, provide the Affiliate with commission statistics, and handle all customer services related to the business. A unique tracking identification code/s will be assigned to all referred customers.</p>
<p>4.3 CasinoBit.io shall pay the Affiliate the amount due depending on the traffic generated subject to the Terms of this Agreement.</p>
</div>

<h3 class="itemTitle">5. Responsibilities and Obligations of the Affiliate: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>5.1 The Affiliate hereby warrants:</p>
<ul>
<li>a) To use their best efforts to actively and effectively advertise, market and promote CasinoBit.io as widely as possible in order to maximize the benefit to the parties and to abide by the guidelines of the Company as they may be brought forward from time to time and/or as being published online.</li>
<li>b) To market and refer potential players to CasinoBit.io at their own cost and expense. The Affiliate will be solely responsible for the distribution, content and manners of their marketing activities. All of the Affiliate's marketing activities must be professional, proper and lawful under applicable laws and must be in accordance with this Agreement.</li>
<li>c) To use only a tracking link provided within the scope of the Affiliate Program, otherwise no warranty whatsoever can be assumed for proper registration and sales accounting. Also, not to change or modify in any way any link or marketing material without prior written authorization from the Company.</li>
<li>d) To be responsible for the development, operation, and maintenance of their Website as well as for all materials appearing on their Website.</li>
</ul>
<p>5.2 The Affiliate hereby warrants:</p>
<ul>
<li>a) That they will not perform any act which is libelous, discriminatory, obscene, unlawful or otherwise unsuitable or which contains sexually explicit, pornographic, obscene or graphically violent materials.</li>
<li>b) That they will not actively target any person who is under the legal age for gambling.</li>
<li>c) That they will not actively target any jurisdiction where gambling and the promotion thereof is illegal.</li>
<li>d) That they will not generate traffic to CasinoBit.io by illegal or fraudulent activity, particularly, but not limited to, by:</li>
</ul>
<p>I. Sending spam.</p>
<p>II. Incorrect meta-tags.</p>
<p>III. Registering as a player or making deposits directly or indirectly to any player account through their tracker(s) for their own personal use and/or the use of their relatives, friends, employees or other third parties, or in any other way attempt to artificially increase the commission payable or to otherwise defraud the Company. Violation of this provision shall be deemed to be fraud.</p>
<ul>
<li>e) That it will not present their Website in such a way that it might evoke any risk of confusion with CasinoBit.io and/or the Company and or convey the impression that the Website of the contracting party is partly or fully originated with CasinoBit.io and/or the Company.</li>
<li>f) Without prejudice to the marketing material as may be forwarded by the Company and/or made available online through the Affiliates CasinoBit.io website the Affiliate may not use CasinoBit.io or other terms, trademarks and other intellectual property rights that are vested in the Company, unless the Company consents to such use in writing.</li>
<li>g) “PPC and Keyword Bidding” not allowed. It is a marketing activity conducted via advertising by the use of search engine ads; or purchase or register keywords, search terms or other identifiers for use in any search engine, portal, social network (including but not solely or limited to Facebook, Twitter, Bebo, Vkontakte etc), sponsored advertising service or other search or referral service; or Meta Tag keywords in PPC advertising.</li>
</ul>
</div>


<h3 class="itemTitle">6. Payment: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>6.1 The Company agrees to pay the Affiliate a commission based on the Net Gaming Revenue generated from new customers referred by the Affiliate's Website and/or other channel. New customers are those customers of the Company who do not yet have a gaming account and who access CasinoBit.io via a tracking link and who properly register and make bitcoin transfers at least equivalent to the minimum deposit into their CasinoBit.io betting account. The commission shall be deemed to be inclusive of value added tax or any other tax if applicable.</p>
<p>6.2 The commission shall be a percentage of the Net Gaming Revenue (NGR) in accordance with what is set out in the commission structures for a particular product. The calculation is product-specific and it is set out in every product-specific commission structure. </p>
<p>6.3 The commission is calculated at the end of each month and payments shall be performed by the 5th (fifth) day of each calendar month. A commission rate is defined by the previous month’s performance.</p>
<p>d) If this Agreement is terminated by the Company on the basis of the Affiliate's breach, the Company shall be entitled to withhold the Affiliate's earned but unpaid commissions as of the termination date as collateral for any claim arising from such breach. It is further specified that termination by the Company due to a breach by the Affiliate of any of the clauses in this Agreement shall not require a notice period and such termination shall have immediate effect upon simple notification by the Company to the Affiliate.</p>
<p>e) The Affiliate must return to the Company any and all confidential information (and all copies and derivations thereof) in the Affiliate's possession, custody, and control.</p>
<p>f) The Affiliate will release the Company from all obligations and liabilities occurring or arising after the date of such termination, except with respect to those obligations that by their nature are designed to survive termination. Termination will not relieve the Affiliate from any liability arising from any breach of this Agreement, which occurred prior to termination and/or to any liability arising from any breach of confidential information even if the breach arises at any time following the termination of this Agreement. The Affiliate's obligation of confidentiality towards the Company shall survive the termination of this Agreement.</p>
</div>


<h3 class="itemTitle">8. Warranties: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>8.1 The Affiliate expressly acknowledges and agrees that the use of the Internet is at their own risk and that this Affiliate Program is provided “as is” and “as available” without any warranties or conditions whatsoever, express or implied. No guarantee is made that it will make access to their Website possible at any particular time or any particular location.</p>
<p>8.2 The Company shall in no event be liable to the Affiliate or anyone else for any inaccuracy, error or omission in, or loss, injury or damage caused in whole or in part by failures, delays or interruptions of the CasinoBit.io website or the Affiliate Program.</p>
</div>

<h3 class="itemTitle">9. Indemnification: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>9.1 The Affiliate agrees to defend, indemnify and hold the Company and its affiliates, successors, officers, employees, agents, directors, shareholders and attorneys, free and harmless from and against any and all claims and liabilities, including reasonable attorneys' and experts' fees, related to or arising from:</p>

<p>a) Any breach of the Affiliate's representations, warranties or covenants under this Agreement.</p>
<p>b) The Affiliate's use (or misuse) of the marketing materials.</p>
<p>c) All conduct and activities occurring under the Affiliate's user ID and password.</p>
<p>d) Any defamatory, libelous or illegal material contained within the Affiliate's Website or as a part of the Affiliate's information and data.</p>
<p>e) Any claim or contention that the Affiliate's Website or the Affiliate's information and data infringes any third party's patent, copyright, trademark, or other intellectual property rights or violates any third party's rights of privacy or publicity.</p>
<p>f) Third party access or use to/of the Affiliate's Website or the Affiliate's information and data.</p>
<p>g) Any claim related to the Affiliate’s Website.</p>
<p>h) Any violation of this Agreement.</p>
<p>9.2 The Company reserves the right to participate, at its own expense in the defense of any matter.</p>
</div>


<h3 class="itemTitle">10. The Company’s Rights: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>10.1 The Company and/or CasinoBit.io may refuse any player or close a player's account if it is necessary to comply with the Company's and/or CasinoBit.io's policy and/or protect the interest of the Company.</p>
<p>10.2 The Company may refuse any applicant and/or may close any Affiliate's account if it is necessary to comply with the Company's policy and/or protect the interest of the Company. If the Affiliate is in breach of this Agreement or the Company's Terms or other rules, policies and guidelines of the Company, the Company may, besides closing the Affiliate's account, take any other steps at law to protect its interests.</p>
</div>

<h3 class="itemTitle">11. Assignment: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>11.1 The Affiliate may not assign this Agreement, by operation of law or otherwise, at any time without obtaining the prior written consent of the Company. In the event that the Affiliate acquires or otherwise obtains control of another affiliate of CasinoBit.io, then accounts will coexist on individual terms.</p>
</div>

<h3 class="itemTitle">12. Non-Waiver: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>12.1 The Company's failure to enforce the Affiliate's adherence to the Terms outlined in this Agreement shall not constitute a waiver of the right of the Company to enforce said Terms at any time.</p>
</div>

<h3 class="itemTitle">13. Force Majeure: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>13.1 Neither party shall be liable to the other for any delay or failure to perform their obligations under this Agreement if such delay or failure is not the fault of such party and arises from a cause beyond the reasonable control of the party, including but not limited to labor disputes, strikes, industrial disturbances, acts of God, acts of terrorism, floods, lightning, utility or communications failures, earthquakes or other casualties. If a force majeure event occurs, the non-performing party is excused from whatever performance is prevented by the force majeure event to the extent prevented. Provided that, if the force majeure event subsists for a period exceeding thirty (30) days then either party may terminate the Agreement without notice.</p>
</div>

<h3 class="itemTitle">14. Relationship of the Parties: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>14.1 Nothing contained in this Agreement, nor any action taken by any party to this Agreement, shall be deemed to constitute either party (or any of such party's employees, agents, or representatives) an employee, or legal representative of the other party, nor to create any partnership, joint venture, association, or syndication among or between the parties, nor to confer on either party any express or implied right, power or authority to enter into any Agreement or commitment on behalf of (nor to impose any obligation upon) the other party.</p>
</div>

<h3 class="itemTitle">15. Severability / Waiver: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>15.1 Whenever possible, each provision of this Agreement shall be interpreted in such a manner as to be effective and valid under applicable law, but if any provision of this Agreement is held to be invalid, illegal or unenforceable in any respect, such provision will be ineffective only to the extent of such invalidity, or unenforceability, without invalidating the remainder of this Agreement. No waiver will be implied from conduct or failure to enforce any rights and must be in writing to be effective.</p>
</div>

<h3 class="itemTitle">16. Confidentiality: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>16.1 All information, including but not limited to business and financial, lists of customers and buyers, as well as price and sales information and any information relating to products, records, operations, business plans, processes, product information, business know-how or logic, trade secrets, market opportunities and personal data of the Company shall be treated confidentially. Such information must not be used for own commercial or other purposes or divulged to any person or third party neither direct nor indirectly unless the prior explicit and written consent of the Company. This provision shall survive the termination of this Agreement.</p>
<p>16.2 The Affiliate obliges themselves not to use the confidential information for any purpose other than the performance of their obligations under this Agreement.</p>
</div>

<h3 class="itemTitle">17. Changes to this Agreement: <i class="fas fa-angle-down"></i></h3>
<div class="itemContent">
<p>17.1 The Company reserves the right to amend, alter, delete or add to any of the provisions of this Agreement, at any time and at its sole discretion, without giving any advance notice to the Affiliate subject to the Terms set out in this Agreement. Any such changes will be published on CasinoBit.io.</p>
<p>17.2 In case of any discrepancy between the meanings of any translated versions of this Agreement, the English language version shall prevail.</p>
</div>

<h3 class="itemTitle">18. Trademarks: <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>18.1 Nothing contained in this Agreement will grant either party any right, title or interest in the trademarks, trade names, service marks or other intellectual property rights (hereafter referred to as “Mark”) of the other party. At no time during or after the term will either party attempt or challenge or assist or allow others to challenge or to register or to attempt to register the Marks of the other party or of any company within the group of companies of the other party. Provided also that neither of the parties will register nor attempt to register any Mark which is basically similar to and/or confusingly similar to any Mark which belongs to the other party or to any company contained within the other party's group of companies.</p>
</div>

<h3 class="itemTitle">19. Commissions are paid out as a percentage of the Net Gaming Revenue. <i class="fas fa-angle-down"></i></h3>

<div class="itemContent">
<p>NGR = (((Bets - Wins) - Paid Bonuses) - Admin Fee) - Bonus Cost
</p>
<p>In this case Paid Bonuses means bonuses credited to the customer (virtual money), not Bonus Cost. The difference is that a Paid Bonus is the actual credited amount of bonus money. Bonus Cost is the actual cost that is calculated off lost Paid Bonus and is charged by the Game Provider. Whereof the Admin Fee is a value which contains the License Fee, Game Provider Fee and Platform Fee (usually around 18%, depending on the game provider in question).
</p>
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
    <script type="text/javascript" src="/partner/js/nivo-lightbox.js"></script>
    <script type="text/javascript" src="/partner/js/jqBootstrapValidation.js"></script>
    {{--<script type="text/javascript" src="/partner/js/contact_me.js"></script>--}}
    <!-- <script src="/partner/js/jquery.magnific-popup.min.js"></script> -->
    <script type="text/javascript" src="/partner/js/main.js"></script>
    <script type="text/javascript" src="/partner/js/leanding.js?v={{time()}}"></script>
@show
</body>
</html>