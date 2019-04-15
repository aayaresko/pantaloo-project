@extends('layouts.app')

@section('title')
    {{ trans('casino.frq') }}
@endsection

@section('content')
    <div class="page-content-block"
         style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="page-content-container">
            <div class="page-content-entry">
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.frq'), MB_CASE_UPPER) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <h4>Most Popular</h4>
                        <div class="col-sm-12">
                            <h5>How do I create my casinobit account?</h5>
                            <p class="text">
                                All you have to do to get started is click the 'Registration' button in the upper left
                                corner and fill in all the required fields.
                                We’ll then pop you over an email where you can verify your email address using the
                                verification link we send you.
                                Once all this is done, your account will be fully active.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What should I do if I have forgotten my password?</h5>
                            <p class="text">
                                The quickest way is to create a new password by clicking this ‘Forgot Password?’ link.
                                We will then send an email that will direct you to a new link.
                                Then you can enter a new password for you CasinoBit account.
                                Alternatively, you can contact our helpful customer support either by the Live Chat
                                option on the site or by emailing us at support@casinobit.io.
                                We will be more than happy to assist you further.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Where can I purchase Bitcoins?</h5>
                            <p class="text">
                                There are plenty of places that allow you to buy and sell Bitcoins.
                                Online exchanges include Coinbase, Bitstamp and Bitpanda.
                                To find out more about buying Bitcoins, please visit our About Bitcoin page.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Are the games fair?</h5>
                            <p class="text">
                                Our games are 100% fair.
                                To prove our words, each player can check our provability fair widget.
                                All games are regulated under the license of Curacao.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What currencies and cryptocurrencies do you accept?</h5>
                            <p class="text">
                                Our idea was to create an online casino where you can play with Bitcoin only.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>How to start</h4>
                        <div class="col-sm-12">
                            <h5>How do I create my CasinoBit account?</h5>
                            <p class="text">
                                All you have to do to get started is click the 'Registration' button in the upper left
                                corner and fill in all the required fields.
                                We’ll then pop you over an email where you can verify your email address using the
                                verification link we send you.
                                Once all this is done, your account will be fully active.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>I haven't receved confirmation email...</h5>
                            <p class="text">
                                In this case you need first to check your 'Spam' folder.
                                Sometimes automatic emails can be found there because of your email box filtering
                                settings.
                                If you cannot find our email in any of your email box folders, please contact us in the
                                web chat or over the email support@casinobit.io.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What is the minimum age allowed?</h5>
                            <p class="text">
                                You may participate in any of the Games if and only if: You are over eighteen (18) years
                                of age or such higher minimum legal age of majority as stipulated in the jurisdiction of
                                Your residence; and It is legal for You to participate in the Games according to
                                applicable laws in the jurisdiction of your residence.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>How to make a deposit?</h5>
                            <p class="text">
                                You need to click on '+' button in a top right corner and then you will receive an
                                address to send your Bitcoins.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What currencies and cryptocurrencies do you accept?</h5>
                            <p class="text">
                                We accept only Bitcoin.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What is Bitcoin?</h5>
                            <p class="text">
                                Bitcoin is a digital and global money system currency. It allows people to send or
                                receive money across the internet, even to someone they don't know or don't trust. Money
                                can be exchanged without being linked to a real identity. The mathematical field of
                                cryptography is the basis for Bitcoin's security.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>Account</h4>
                        <div class="col-sm-12">
                            <h5>Can I have more than one account?</h5>
                            <p class="text">
                                No, it is only allowed to open one account during your lifetime,
                                even if an old account is closed down, you would have to request this one to be reopened
                                as no new accounts can not be created.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What should I do if I have forgotten my password?</h5>
                            <p class="text">
                                The quickest way is to create a new password by clicking this ‘Forgot Password?’ link.
                                We will then send an email that will direct you to a new link.
                                Then you can enter a new password for you CasinoBit account.
                                Alternatively, you can contact our helpful customer support either by the web chat
                                option on the site or by emailing us at support@casinobit.io.
                                We will be more than happy to assist you further.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Can I change my registered email address?</h5>
                            <p class="text">
                                No. Unfortunately it's not possible to change your email address.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>Deposits & Withdrawals</h4>
                        <div class="col-sm-12">
                            <h5>What happens if my deposit is not showing?</h5>
                            <p class="text">
                                If your crypto deposit is now showing, this most likely means that it has not yet been
                                confirmed in the blockchain,
                                or that your wallet has not yet sent the funds, you can always check the blockchain
                                to see if your transfer is visible there, or ask the support if you need a hand with
                                this.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>How long does it take for deposits for show in my account?</h5>
                            <p class="text">
                                As soon as your deposit appear in the blockchain network, it's shown in your account.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What happens if I sent my deposit to another address or if I deposited lower than the
                                minimum amount?</h5>
                            <p class="text">
                                As we do not have any access over the actual transfer being made(this is done via the
                                blockchain and miners),
                                funds that are sent to another address or below the minimum amount are unfortunately
                                lost.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What are the rules to withdrawing my balance?</h5>
                            <p class="text">
                                Before a withdrawal is allowed to be made your deposit has to be wagered 3 times.
                                If you only play table games such as Roulette and Blackjack, then the deposit needs to
                                be wagered 10 times.
                                These rules exist to prevent money laundering and fraud.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Is there a maximum withdrawal rule I should know about?</h5>
                            <p class="text">
                                We don't enforce any limits now.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What are the fees on deposits and withdrawals?</h5>
                            <p class="text">
                                CasinoBit does not have any fee on deposit or withdrawal.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What are your minimums on deposits and withdrawals?</h5>
                            <p class="text">
                                Minimum deposit is 1 mBTC (1 BTC = 1000 mBTC)
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>How long will it take for me to receive my money once I request a withdrawal?</h5>
                            <p class="text">
                                While Casinobit.io will endeavour to pay out withdrawals immediately, some withdrawals
                                may take longer than others.
                                Casinobit.io shall try to pay out withdrawals within 1 hour, however in some cases,
                                withdrawals may take longer.
                            </p>
                        </div>
                        <div class="col-sm-12">
                            <h5>Where can I purchase Bitcoins?</h5>
                            <p class="text">
                                There are plenty of places that allow you to buy and sell Bitcoins. Online exchanges include Coinbase, Bitstamp and Bitpanda.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>The Casino</h4>
                        <div class="col-sm-12">
                            <h5>Are the games fair?</h5>
                            <p class="text">
                                Our games are 100% fair. To prove our words, each player can check our provability fair widget.
                                All games are regulated under the license of Curacao.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>What happens if I encounter an error or the game freezes?</h5>
                            <p class="text">
                                Most of the time, if your game freezes then you can pick up exactly where you left off next time you open the game.
                                If there is no option for you to do this, then the round will continue to play out on the server.
                                This will happen even if your computer is frozen or you lose internet connection.
                                All your winnings will be paid into your account as normal.
                                In order to get back into the game a simple refresh of the site usually works,
                                and if this does not let you back into the game,
                                you just need to clear your cookies and cache in your browser and it shall work once again.
                                Is the game still not opening up, you can contact us on support@casinobit.io or via the web chat here.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>I can’t get the casino game to start when I click the link. What should I do?</h5>
                            <p class="text">
                                In order to play in the CasinoBit you will need to have Java installed on your computer.
                                You can download Java here: http://java.com/
                                Please also check that you have installed the latest version of Flash.
                                If you need to download Flash, then you can do so here: http://get.adobe.com/flashplayer/
                                If neither of these resolves the issue, then it could mean you have lost contact with your server.
                                Try logging out and logging back in before you click the link again.
                                Occasionally you may need to shut down your whole browser and reopen it again for the game to load.
                                If you’ve tried all these and the problem continues to persist, then contact our support team.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>An error or technical issue occurred in the casino. What should I do?</h5>
                            <p class="text">
                                We store every single spin you make in our database in order to help with these problems.
                                If you feel that an error has occurred during your game play, then please contact our support team.
                                Remember that the more information you can give us, the easier it will be for us to resolve the problem.
                                Try to note down the game name, the amount of time you have played, the stake you were playing and details on what happened.
                                If you are able to provide a screenshot of the problem then this will also assist us in fixing the problem.
                                It is always a good idea to stop playing until the issue has been resolved.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>How can I take a screenshot?</h5>
                            <p class="text">
                                A screenshot is a photo of the display currently on your computer screen.
                                The image will be saved in your computer as a file, and can easily be attached to emails in order to send to our support team.
                                In order to take a screenshot in Windows, just follow the steps outlined below:
                                Step 1: Ensure that the current view on your screen is the image you want to take a screenshot of.
                                Close or minimise any additional windows that may be obscuring the view.
                                Step 2: Press the 'Print Screen' button on your keyboard.
                                This button is usually located on the right hand side of the keyboard.
                                Step 3: Open a 'Paint' or 'Word' program on your computer and then press Ctrl + V to paste the image in.
                                You can also click the 'Paste' option from the ‘Edit’ menu to do this.
                                You will then see your print screen image appear in the document.
                                Step 4: Save this file to your computer using a name that will help you easily identify it.
                                We also recommend that you save it to your desktop to allow faster access to the file.
                                Step 5: Attach this file into an email addressed to support@casinobit.io, including all the additional information about the error.
                                In order to take a screenshot on a Mac, just follow the steps outlined below:
                                Step 1: Ensure that the current view on your screen is the image you want to take a screenshot of.
                                Close or minimise any additional windows that may be obscuring the view.
                                Step 2: Press Shift + CMD + 4, then click on the spacebar to make the camera appear.
                                Click the mouse in order to take a snapshot of the current window open on your screen.
                                This image will automatically save to your computer desktop, with the current time and date.
                                Step 3: Attach this file into an email addressed to support@casinobit.io, including all the additional information about the error.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>Security</h4>
                        <div class="col-sm-12">
                            <h5>Am I required to upload documents and why?</h5>
                            <p class="text">
                                Our top priority is to make sure that we keep all our players safe and we take this very seriously.

                                If you are playing with cryptocurrencies such as Bitcoins you will not have to provide documents as a standard.
                                However, we do reserve the right to carry out additional verification if we believe any suspicious or fraudulent behaviour has occurred on your account.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Is all my information secure on CasinoBit?</h5>
                            <p class="text">
                                Always. As soon as you log in, any communication you make between your web browser and the CasinoBit website will be protected by industry-standard encryption technology.
                                This means that all your personal data and any activity you make on the site will be kept completely private.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Are my Bitcoins secure on CasinoBit?</h5>
                            <p class="text">
                                We take your security extremely seriously.
                                Our experts have built in various sophisticated measures to prevent theft of funds or case sensitive information.
                                In addition to this, we follow all the best practices to keep your coins safe, as outlined below:
                                We store all the bitcoins in cold wallets, which are completely isolated from any online system.
                                Being in cold wallets, they are protected with air-gap isolation
                                All wallets are encrypted
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>How does your KYC process work, how long does it take and will I be notified?</h5>
                            <p class="text">
                                When you request a withdrawal,
                                if we at this stage require documents from you we will send you an email asking for the required documents with instructions on how to proceed.
                                The documents will be verified within 12 hours time, mostly this is done within one hour though.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>Can my documents be refused?</h5>
                            <p class="text">
                                Yes. We must be in accordance to the rules stated by our licensing institution in Curacao.
                                In cases where it is needed, we will reach out to you to ask for more or alternative document copies, in order to stay within the law.
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>How can I make sure my account is fully protected?</h5>
                            <p class="text">
                                There are a few steps you can take to make sure that your account is as safe as it can be.
                                1. Make sure you are using a different password for CasinoBit than your email account connected to your CasinoBit account or any other site you are using.
                                2. Do regular virus scans on your computer to ensure no malicious programs such as keyloggers that can gather your secure information are hiding on your computer.
                            </p>
                        </div>
                    </div>


                    {{--<div class="col-sm-12">--}}
                    {{--<h4>{{ trans('casino.support.create_account') }}</h4>--}}
                    {{--<p class="text">--}}
                    {{--{{ trans('casino.support.create_account_value') }}--}}
                    {{--</p>--}}
                    {{--</div>--}}

                    {{--<div class="col-sm-12">--}}
                    {{--<h4>{{ trans('casino.support.forgotten_password') }}</h4>--}}
                    {{--<p class="text">--}}
                    {{--{{ trans('casino.support.forgotten_password_value') }}--}}
                    {{--</p>--}}
                    {{--</div>--}}

                    {{--<div class="col-sm-12">--}}
                    {{--<h4>{{ trans('casino.support.buy_bitcoins') }}</h4>--}}
                    {{--<p class="text">--}}
                    {{--{{ trans('casino.support.buy_bitcoins_value') }}--}}
                    {{--</p>--}}
                    {{--</div>--}}

                    {{--<div class="col-sm-12">--}}
                    {{--<h4>{{ trans('casino.support.games_fair') }}</h4>--}}
                    {{--<p class="text">--}}
                    {{--{{ trans('casino.support.games_fair_value') }}--}}
                    {{--</p>--}}
                    {{--</div>--}}


                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-static">
        <div class="bitcoin-block">
            <span class="bitcoin-msg"><i class="bitcoin-icon"></i> We work only with bitcoin</span>
        </div>
        <div class="msg-block">
            <span class="msg">{{ trans('casino.do_you_want_to_play') }}</span>
        </div>
        <div class="games-listing-block">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
        <div class="footer-copyrights">
            <p>© All rights reserved</p>
        </div>
    </footer>
@endsection


@section('js')
@endsection