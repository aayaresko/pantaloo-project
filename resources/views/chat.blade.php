@extends('layouts.app')

@section('title')
    Chat
@endsection


@section('content')
    <div class="page-content-block" style="background: #000 url('/media/images/bg/content-bg.png') center no-repeat; background-size: cover;">
        <div class="page-content-container">
            <div class="page-content-entry">


                <div class="page-heading">
                    <h1 class="page-title">Support</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{translate('How do I create my account?')}}</h4>
                            <p class="text">
    {{translate('Please press the sign up button in the upper right corner and fill in all the required fields. After correct filling of the forms we will email you with the confirmation link to verify your email address. Once your email is confirmed, the account will be successfully created and active. So, you can start playing our beautiful games.')}}
</p>
</div>

<div class="col-sm-12">
<h4>{{translate('What should I do if I have forgotten the password?')}}</h4>
<p class="text">
    {{translate('The best way is to click “forgot the password” button and follow the link. You will be automatically sent an email with the recover link where you will be advised to enter new password. Otherwise, you can report us through livechat. We are happy to help.')}}
</p>
</div>

<div class="col-sm-12">
<h4>{{translate('Where can I buy bitcoins?')}}</h4>
<p class="text">
    {{translate('There are numbers of bitcoins’ services. For example, bitstamp, coinbase, and spicepay. For more detailed information please visit our “what is bitcoin” page or contact our livechat for advice.')}}
</p>
</div>

<div class="col-sm-12">
<h4>{{translate('Are the games fair?')}}</h4>
<p class="text">
    {{translate('Our games are 100% fair. To prove our words, each player can check our provability fair widget. All games are regulated under the license of Curacao.')}}
</p>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection


@section('js')

@endsection