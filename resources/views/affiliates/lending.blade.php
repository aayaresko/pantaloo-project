@extends('layouts.partner')

@section('title')
    Casino
@endsection

@section('content')
   

<section class="mainSection" id="main">
    <div class="contWrap">			
    <div class="leftBlock">
        <h1>Start with 50 profit!</h1>
        <p class="topTxt">You'll receive a 50-70% revenue share. Start earning already today.</p>
        <button class="btn" data-toggle="modal" data-target="#myModal2">GET STARTED</button>
    </div>
    <div class="centerBlock">
        <img src="/partner/img/pc.png" alt="">
    </div>
    <div class="rightBlock">
        <div class="videoWrap">
            <img src="/partner/img/videoBtn.png" alt="">
        </div>
        <h3>Video</h3>
        <p>Your time is precious, so you can learn everything about our affiliate program in just one minute</p>
    </div>
</div>
</section>


<section class="about" id="about">
    <h2>About us</h2>
    <div class="contWrap">
        <div class="leftBlock">
            <h3>CasinoBit.io</h3>
            <p>CasinoBio.io is the first bitcoin casino offering a customized bonus system for its partners. If you are eager for expanding your affiliate network, you can always negotiate more favourable conditions.</p>
            <p>We have a wide range of games from 30+ top industry providers. This is more than 1500 games, including 700+ slots to have a great time. You will always have players — and therefore, earnings.</p>
            <p>CasinoBio.io provides high-level services, round-the-clock English-speaking online support, and regular payments in bitcoins. We know how to attract users and fix their retention.</p>
            <h3>Affiliates</h3>
            <p>Once you become a CasinoBio.io affiliate, you will get a constant source of passive income. We offer favorable conditions for those partners who are aimed at a long-term cooperation.</p>
            <p>You can create your own affiliate network to get even more profit. You’ll be able to determine the reward amount for your sub-affiliates and see all financial flows.</p>
            <p>Our affiliate program supports the Revenue Sharing commission type. All payments are made in bitcoins only — it is safe, fast and doesn’t require paying fees to intermediaries.</p>
            <p>You can learn more about our affiliate program by watching the presentation:</p>
            <div class="btnWrap">
                <a href="/partner/img/portfolio/Presentation_English_(ENG).pdf" target="_blank" class="btn">Download</a>
            </div>
        </div>
        <div class="rightBlock">
            <img src="/partner/img/mob.png" alt="">
        </div>
    </div>
</section>


<section class="payouts" id="payouts">
    <h2>Revenue Share</h2>
    <p>Find out how much profit you’ll get if you enter our affiliate program</p>
    <div class="contWrap">
        <div class="profitBoard">
            <div class="profitBoardItem">
                <span class="precent">50%</span>
                <span class="amount">0-1</span>
                <span class="currency">BTC</span>
            </div>
            <div class="profitBoardItem">
                <span class="precent">55%</span>
                <span class="amount">1-2</span>
                <span class="currency">BTC</span>
            </div>
            <div class="profitBoardItem">
                <span class="precent">60%</span>
                <span class="amount">2-5</span>
                <span class="currency">BTC</span>
            </div>
            <div class="profitBoardItem">
                <span class="precent">65%</span>
                <span class="amount">5-10</span>
                <span class="currency">BTC</span>
            </div>
            <div class="profitBoardItem">
                <span class="precent">70%</span>
                <span class="amount">10-20</span>
                <span class="currency">BTC</span>
            </div>
        </div>
        <div class="calcWrap">
            <div id="calcRange">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1281.18 1281.18"><defs>
                <style>    
                    .cls-1{
                    fill:none;
                    stroke-linecap:round;
                    stroke-miterlimit:10;
                    stroke-width:62.6px;
                    stroke:url(#linear-gradient);
                    }</style>
                <linearGradient id="linear-gradient" x1="12.4" y1="240.2" x2="1269.36" y2="240.2" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#4d2485"/>
                    <stop offset="0.53" stop-color="#620bb0"/>
                    <stop offset="1" stop-color="#c60293"/>
                </linearGradient>
                </defs>
                <path class="cls-1" d="M1219.67,449.1C1139.29,206.4,910.54,31.3,640.88,31.3S142.47,206.4,62.09,449.1"/>
                </svg>
                <div class="rangeCicleWrap">
                    <div class="ball"></div>

                </div>
            <div class="calcDigits">
                <div class="leftBlock">
                    <p>Player lost <span>(mBTC)</span></p>
                    <input type="text" id="mainVal" value="500">
                </div>
                <div class="middleBlock">
                    <p>Profit you get</p>
                    <div id="profit">250</div>
                </div>
                <div class="rightBlock">
                    <p>% you get</p>
                    <div class="gainPercentWrap">
                        <span id="gainPercent">50</span>
                    </div>
                </div>
            </div>	



            </div>
        </div>
    </div>
</section>

<section class="createAcc">
    <h3>Create Account</h3>
    <div class="contWrap">
        <div class="formWrap">
            <form action="/affiliates/register" class="regForm">
                {{csrf_field()}}
                <input required name="email" placeholder="Enter your e-mail" type="email">
                <input required name="password" placeholder="Password" type="password" class="firstPass">
                <input required name="password_confirmation" placeholder="Confirm Password" type="password">
                <button class="btn">Sign up</button>
                <br>
                <div class="termsCheckWrap">
                    <input required type="checkbox" id="agree" name="agree">
                    <label class="checkLabel" for="agree"></label>
                    <span>I accept the <a href="#" class="popUpBtn" data-toggle="modal" data-target="#exampleModalLong">Terms & Conditions</a></span>
                </div>
                <div class="error-lists" style="display: none">
                    <ul class="error-lists">
                    </ul>
                </div>
            </form>
        </div>
    </div>
</section>


<section class="marketing" id="marketing">
    <h2>Marketing</h2>
    <div class="contWrap">
        <div class="leftBlock">
            <div class="marketItem">
                <div class="imgWrap">
                    <img src="/partner/img/banner.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>Banners</h3>
                    <p>Choose from over 60 pre-made banners that you can start using immediately</p>
                </div>		
            </div>
            <div class="marketItem">
                <div class="imgWrap">
                    <img src="/partner/img/admin.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>Admin Panel</h3>
                    <p>We offer a convenient back office showing you all necessary information about attracted users and sub-affiliates</p>
                </div>		
            </div>
            <div class="marketItem">
                <div class="imgWrap">
                    <img src="/partner/img/stats.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>Statistics</h3>
                    <p>You can view transaction statistics of each player in your network, including their deposits, losses, and withdrawals</p>
                </div>		
            </div>
            <div class="marketItem">
                <div class="imgWrap">
                    <img src="/partner/img/subaff.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>Sub-affiliates</h3>
                    <p>You can view full information about your affiliate network and change the reward size for sub-affiliates at will</p>
                </div>		
            </div>
        </div>
        <div class="rightBlock">
            <img src="/partner/img/marketing.png" alt="">
        </div>
    </div>
</section>



<section class="createAcc botAcc">
    <h3>Create Account</h3>
    <div class="contWrap">
        <div class="formWrap">
            <form action="/affiliates/register" class="regForm">
                {{csrf_field()}}
               <input required name="email" placeholder="Enter your e-mail" type="email">
               <input required name="password" placeholder="Password" type="password" class="firstPass">
               <input required name="password_confirmation" placeholder="Confirm Password" type="password">
                <button class="btn">Sign up</button>
                <br>
                <div class="termsCheckWrap">
                    <input required type="checkbox" id="agree2" name="agree">
                    <label class="checkLabel" for="agree2"></label>
                    <span>I accept the <a href="#" class="popUpBtn" data-toggle="modal" data-target="#exampleModalLong">Terms & Conditions</a></span>
                </div>
                <div class="error-lists" style="display: none">
                    <ul class="error-lists">
                    </ul>
                </div>
            </form>
        </div>
    </div>
</section>



<section class="profitable" id="profitable">
    <h2>Benefits</h2>
    <div class="contWrap">
        <div class="leftBlock">
            <div class="profitItem">
                <div class="descrWrap">
                    <h3>Support</h3>
                    <p>Round-the-clock English-speaking support, including online chat for fast communication</p>
                </div>		
                <div class="imgWrap">
                    <img src="/partner/img/support@2x.png" alt="">
                </div>
            </div>
            <div class="profitItem">
                <div class="descrWrap">
                    <h3>Payouts</h3>
                    <p>Regular payments — once per month and without delay</p>
                </div>		
                <div class="imgWrap">
                    <img src="/partner/img/withdraw@2x.png" alt="">
                </div>
            </div>
            <div class="profitItem">
                <div class="descrWrap">
                    <h3>Stability</h3>
                    <p>A technical team responsible for the smooth work of the website</p>
                </div>		
                <div class="imgWrap">
                    <img src="/partner/img/statbil@2x.png" alt="">
                </div>
            </div>
        </div>
        <div class="rightBlock">
            <div class="profitItem">
                <div class="imgWrap">
                    <img src="/partner/img/report@2x.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>Reports</h3>
                    <p>You always have access to financial reports to understand the effectiveness of advertising channels</p>
                </div>		
            </div>
            <div class="profitItem">
                <div class="imgWrap">
                    <img src="/partner/img/hiBet@2x.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>High Income</h3>
                    <p>A 50-70% revenue share — this is the highest paying affiliate program</p>
                </div>		
            </div>
            <div class="profitItem">
                <div class="imgWrap">
                    <img src="/partner/img/safe@2x.png" alt="">
                </div>
                <div class="descrWrap">
                    <h3>Security</h3>
                    <p>Our own blockchain node that guarantees financial security</p>
                </div>		
            </div>
        </div>
        <div class="middleBlock">
            <img src="/partner/img/people.png" alt="">
        </div>
    </div>
</section>


<!-- 
<section class="faq" id="faq">
    <h2>FAQ</h2>
    <div class="contWrap">
        <div class="leftBlock">
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io</p> 
                    <p>Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
        </div>
        <div class="rightBlock">
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
            <div class="faqItem">
                <div class="question">Как стать парнером CasinoBit.io</div>
                <div class="answer">
                    <p>Easy стать парнером CasinoBit.io</p> 
                </div>
            </div>
        </div>
    </div>
    <div class="suppWrap">
        <h3>Поддержка</h3>
        <p>Не нашли ответ на интересующий Вас вопрос?</p>
        <a href="mailto:test@test.ru" class="btn">Напишите нам</a>
    </div>
</section> -->




<section class="contacts" id="contacts">
    <h2>Contact us</h2>
    <div class="contWrap">
        <div class="leftBlock">
            <div class="formWrap">
                <form name="sentMessage" id="contactForm" class="contForm">
                    <div class="topForm">
                        <div class="leftBlock">
                            <label for="name">Name:</label>
                            <input name="name" type="text" id="name" required>
                        </div>
                        <div class="rightBlock">
                            <label for="contEmail">E-mail:</label>
                            <input name="email" type="email" id="contEmail" required>
                        </div>

                    </div>
                    <div class="midForm">
                        <label for="msg">Message:</label>
                        <textarea name="message" id="msg" required></textarea>
                    </div>
                    <button class="btn">SEND</button>							
                </form>
            </div>
        </div>
        <div class="rightBlock">
            <img src="/partner/img/contactImg.png" alt="">
        </div>
    </div>
</section>


@endsection
