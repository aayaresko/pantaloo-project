@extends('layouts.app')

@section('title')
    {{translate('Bonus')}}
@endsection

@section('content')
    <div class="cabinet-block act" style="background: #000 url('media/images/bg/content-bg.png') center no-repeat; background-size: cover;">


        <div class="actions">

            <div class="container">
                <div class="row">

                    <section class="bookof">
                        <div class="info-spin">
                            {!! translate('<span class="numb">50</span><span class="info-sub-text"><span class="free">Free</span><span class="sub-text">Spins</span></span><br>')  !!}
                            <br>
                            @if(Auth::check())
                                <a href="{{ route('deposit') }}" class="btn-play-action"><span>{{translate('D e p o s i t')}}</span></a>
                            @else
                                <a href="#" class="btn-play-action reg-btn"><span>{{translate('Registration')}}</span></a>
                            @endif
                            <a href="#uls" class="usl-link">{{translate('terms')}}</a>
                        </div>
                        <div class="bonus bonus-{{ \Illuminate\Support\Facades\Config::get('lang') }}">
                            <div class="text">{{translate('Welcome bonus')}}</div>
                        </div>
                    </section>

                </div>
            </div>

            <div class="container">
                <div class="row">

                    <div class="col-md-6 npl ac-wrap">
                        <section class="block-bonus block-bonus1 clearfix">
                            <div class="info-block clearfix">

                                <div class="block-money">
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <span class="big">200%</span>
                                </div>

                                <div class="number-block">
                                    {!! translate('
                                    <span class="number">1</span>
                                    <span class="lett">st</span>
                                    <span class="descr">Deposit</span>
                                    <div class="desc-bonus">Bonus</div>') !!}
                                </div>

                            </div>

                            <div class="btn-play-wrap">
                                @if(Auth::check())
                                    <a href="{{ route('deposit') }}" class="btn-play-action"><span>{{translate('D e p o s i t')}}</span></a>
                                @else
                                    <a href="#" class="btn-play-action reg-btn"><span>{{translate('Registration')}}</span></a>
                                @endif
                            </div>
                            <a href="#uls" class="usl-link">{{translate('terms')}}</a>
                        </section>
                    </div>
                    <div class="col-md-6 npr ac-wrap">
                        <section class="block-bonus block-bonus2 clearfix">
                            <div class="info-block info-block2 clearfix">

                                <div class="block-money">
                                    <span class="big">100%</span>
                                </div>

                                <div class="number-block">
                                    {!! translate('
                                    <span class="number">2</span>
                                    <span class="lett">nd</span>
                                    <span class="descr">Deposit</span>
                                    <div class="desc-bonus">Bonus</div>') !!}
                                </div>



</div>

<div class="btn-play-wrap">
@if(Auth::check())
    <a href="{{ route('deposit') }}" class="btn-play-action"><span>{{translate('D e p o s i t')}}</span></a>
@else
    <a href="#" class="btn-play-action reg-btn"><span>{{translate('Registration')}}</span></a>
@endif
</div>
<a href="#uls" class="usl-link">{{translate('terms')}}</a>
</section>
</div>

</div>
</div>

</div>

</div>

<div class="hidden">

@if(\Illuminate\Support\Facades\Config::get('lang') == 'ru')

<div id="uls">
<h3>БОНУСНЫЕ ПРАВИЛА И УСЛОВИЯ</h3>
    <ul class="parent-list">
        <li>
            <h4>ПРАВИЛА И УСЛОВИЯ ПО БЕЗДЕПОЗИТНЫМ БОНУСАМ</h4>
            <ul>
                <li>
                    <span>1a.</span>

                    <p>Максимальный лимит на вывод по выигрышам с бездепозитного бонуса или фриспинов (например, 20 фриспинов за регистрацию) составляет 6,000 RUB, 100 EUR/USD/CAD/AUD или 1,000 SEK/NOK, кроме GBP, для которой максимальный размер выплаты составляет 50 GBP (даже если сумма выигрыша с этого бонуса превышает указанную сумму). Что касается игроков на BTC, то максимальный лимит на вывод по выигрышам с бездепозитного бонуса или фриспинов составит 100 mBTC. Оставшаяся на балансе сумма будет вычтена.</p>
                </li>
                <li>
                    <span>1b.</span>

                    <p>Условия отыгрыша по бездепозитным бонусам или бонусам за регистрацию - сорок (40х). 40 раз нужно отыграть сумму бонуса, если в дополнительных правилах и условиях не указано иное. (Учтите, что для вывода выигрыша, полученного при игре с бонусами за регистрацию, дополнительными вращениями, фриспинами и бесплатными бонусами, для получения которых НЕ нужно делать депозит, Вам необходимо сделать минимальный депозит размером 1,000 RUB, 20 EUR/GBP/CAD/AUD/USD, 200 SEK/NOK или 0.05 BTC (или эквивалент в другой валюте). Помимо этого, нельзя выводить выигрыш с любого бесплатного бонуса, пока не выполнены условия отыгрыша.</p>
                </li>
                <li>
                    <span>2a.</span>

                    <p>Для обработки вывода средств, полученных с бездепозитного бонуса, мы оставляем за собой право запрашивать документы для верификации аккаунта. Необходимо загрузить следующие документы: принятый государством документ, удостоверяющий личность (удостоверение личности, паспорт или водительское удостоверение) и содержащий фотографию. А также подтверждение места жительства: например, счет за коммунальные услуги (которому должно быть не более 6 месяцев). Если Вы делали депозит с помощью кредитной карты, то мы запросим сканированную копию лицевой и задней части этой кредитной карты.</p>
                </li>
                <li>
                    <span>2b.</span>

                    <p>Общие бонусные условия распространяются на эти бонусы.</p>
                </li>
                <li>
                    <span>3.</span>

                    <p>Игроки, запрашивающие вывод средств, полученных при игре с бездепозитным бонусом, не могут делать это с помощью банковского перевода.</p>
                </li>
                <li>
                    <span>4.</span>

                    <p>Игроки с одноразовыми адресами электронной почты не претендуют на бездепозитные фриспины. Если, несмотря на это, игрок получит бездепозитные фриспины, весь выигрыш, полученный при игре с бонусом, будет конфискован.</p>
                </li>
                <li>
                    <span>5a.</span>

                    <p>Игроки из следующих стран не претендуют на бездепозитные фриспины: Малайзия (MY), Индонезия (ID), Бангладеш (BD), Албания (AL), Грузия (GE), Таиланд (TH), Непал (NP), Чехия (CZ), Объединенные Арабские Эмираты (AE), Румыния (RO), Болгария (BG), Венгрия (HU), Польша (PL), Индия (IN), Пакистан (PK), Филиппины (PH), Португалия (PT), Словакия (SK), Казахстан (KZ), Вьетнам (VN), Бахрейн (BH), Египет (EG), Иран (IR), Ирак (IQ), Иордания (JO), Кувейт (KW), Ливан (LB), Оман (OM), Палестина (PS), Сирийская Арабская Республика (SY), Йемен (YE), Монголия (MN), Алжир (DZ), Ангола (AO), Бенин (BJ), Ботсвана (BW), Бурунди (BI), Камерун (CM), Кабо-Верде (CV), Чад (TD), Коморские острова (KM), Джибути (DJ), Египет (EG), Экваториальная Гвинея (GQ), Эритрея (ER), Эфиопия (ET), Габон (GA), Гамбия (GM), Гана (GH), Гвинея (GN), Гвинея-Бисау (GW), Кот-д'Ивуар (CI), Кения (KE), Лесото (LS), Либерия (LR), Молдова (MD), Мадагаскар (MG), Малави (MW), Мали (ML), Мавритания (MR), Маврикий ( MU), Марокко (MA), Мозамбик (MZ), Намибия (NA), Нигер (NE), Нигерия (NG), Руанда (RW), Сан-Томе и Принсипи (ST), Сенегал (SN), Сейшельские острова (SC), Сьерра-Леоне (SL), Сомали (SO), Южный Судан (СС), Судан (SD), Свазиленд (SZ), Танзания (TZ), Того (TG), Тунис (TN), Уганда (UG), Замбия (ZM), Зимбабве (ZW), Греция (GR), Венгрия (HU), Маврикий (MU), Словения (SI), Хорватия (HR), Босния и Герцеговина (BA), Македония (MC), Черногория (ME), Австрия (AT) и Сербия (RS).</p>
                </li>
            </ul>
        </li>
        <li>
            <h4>ПРАВИЛА И УСЛОВИЯ ПО ФРИСПИНАМ</h4>
            <ul>
                <li>
                    <span>1.</span>

                    <p>Что касается дополнительных 180 фриспинов, являющихся частью приветственного набора бонусов, то для их получения необходим минимальный депозит размером от 1,000 RUB, 20 EUR/GBP/CAD/AUD/USD, 200 SEK/NOK или 0.05 BTC. Также необходимо отыграть сумму депозита как минимум один раз.</p>
                </li>
                <li>
                    <span>2.</span>

                    <p>Не нужно вводить бонусный код, чтобы получить фриспины. Если Вы отмените бонус на первый депозит, а также если Вы поставите галочку в пункте "Я отказываюсь от всех бонусов", то Вы не получите 180 приветственных фриспинов за первый депозит.</p>
                </li>
                <li>
                    <span>3.</span>

                    <p>Любой выигрыш, полученный при игре с фриспинами, будет начислен как денежный бонус с условиями отыгрыша в 40 (сорок) раз.</p>
                </li>
                <li>
                    <span>4.</span>

                    <p>Вы должны активировать фриспины и войти в нужную игру не позднее 3 дней после выдачи, иначе срок их действия истечет.</p>
                </li>
                <li>
                    <span>5.</span>

                    <p>Как только фриспины активированы, Вы должны завершить все игровые раунды в течение 1 дней, иначе срок их действия истечет.</p>
                </li>
                <li>
                    <span>6.</span>

                    <p>Казино Casinobit оставляет за собой право отменить или изменить правила и условия в любой момент и без оповещения.</p>
                </li>
                <li>
                    <span>7.</span>

                    <p>180 фриспинов, которые начисляются в качестве части нашего приветственного набора бонусов, будут добавляться частями по 20 фриспинов в течение 9 дней подряд (если игрок претендует на этот бонус и выполнил его условия). Первые 20 фриспинов будут добавлены через 23 часа, после того как Вы сделаете свой первый депозит, а затем каждые 24 часа.</p>
                </li>
                <li>
                    <span>8.</span>

                    <p>Если слот Starburst доступен, то фриспины будут начислены на эту игру в рамках приветственного набора бонусов. Если слот Starburst не доступен из-за выбранной для игры валюты или ограничения по стране, то фриспины будут выданы на слот Fruit Zen или Boomanji.</p>
                </li>
            </ul>
        </li>
        <li>
            <h4>ОБЩИЕ ПРАВИЛА И УСЛОВИЯ ПО БОНУСАМ</h4>
            <ul>
                <li>
                    <span>1.</span>

                    <p>Все бонусные предложения могут быть выданы лишь одному человеку, семье, по одному месту жительства, адресу электронной почты, номеру телефона, счету платежного средства (например, дебетовой или кредитной карты, Neteller и тд.), IP-адресу и общему компьютеру, например, в общественной библиотеке или на рабочем месте.</p>
                </li>
                <li>
                    <span>2a.</span>

                    <p>Мы оставляем за собой право не платить игрокам, использующим одноразовый адрес почты.</p>
                </li>
                <li>
                    <span>2b.</span>
                    <p>Игроки из следующих стран не претендуют на промо-акции: Греция (GR), Венгрия (HU), Маврикий (MU), Словения (SI), Хорватия (HR), Босния и Герцеговина (BA), Македония (MC), Австрия (AT) Черногория (ME) и Сербия (RS).</p>

                </li>
                <li>
                    <span>2c.</span>

                    <p>Мы оставляем за собой право понизить процент бонуса в понедельник до 25% игрокам, которые, по нашему мнению, получили непропорциональное количество бонусов относительно количества сделанных депозитов.</p>
                </li>

                <li>
                    <span>3.</span>

                    <p>Чтобы получить депозитный приветственный бонус или любой депозитный бонус (за вычетом фриспинов), пользователь должен сделать минимальный депозит размером 1,000 RUB, 20 EUR/GBP/CAD/AUD/USD, 200 SEK/NOK или 50 mBTC. Например, игрок автоматически получит свой бонус на первый депозит, внеся 1,000 RUB, 20 EUR/GBP/CAD/AUD/USD, 200 SEK/NOK или 0.05 BTC. Бонус на второй депозит будет начислен, как только игрок сделает второй депозит размером от 1,000 RUB, 20 EUR/GBP/CAD/AUD/USD, 200 SEK/NOK или 0.05 BTC. Такое же правило распространяется на бонусы на третий и четвертый депозиты.</p>
                </li>
                <li>
                    <span>4.</span>

                    <p>Как только Вы получили бонус на первый депозит, Вы можете добавить и другую валюту для игры. Например, если игрок сделал депозит в €20 и получил бонус на первый депозит, то он может запросить бонус на второй депозит - в BTC.</p>
                </li>
                <li>
                    <span>5.</span>

                    <p>Все бонусы нужно отыграть 40 (сорок) раз, чтобы иметь право сделать вывод, если не указано иное.</p>
                </li>
                <li>
                    <span>6.</span>

                    <p>Пожалуйста, учтите, что разные игры вносят различный вклад в отыгрыш бонуса. Слоты - 100% (кроме исключенных игр и классических слотов, указанных ниже), а все настольные игры и видеопокер - 5% (игры с живым дилером не вносят вклад в отыгрыш бонусов).</p>
                </li>
                <li>
                    <span>7.</span>

                    <p>Следующие игры исключаются из бонусных промо-акций: Immortal Romance, Aliens, Mega Moolah, Dead or Alive, Dr. Jekyll &amp; Mr. Hyde, Ragnarok, Pocket Dice, Lucky Angler, Eggomatic, Big Bang, The Dark Knight, Peek-A-Boo, Tomb Raider, The Dark Knight Rises, Forsaken Kingdom, The Wish Master, Scrooge, Secret of the Stones, Devil's Delight, Champion of the Track, Robin Hood, Tomb Raider 2, Queen of Gold, Ninja и Castle Builder. </p>
                    <p>Bloodsuckers, Bloodsuckers touch, Kings of Chicago, Simsalabim, Zombies, Jack Hammer 2, Jack Hammer 2 touch, Demolition Squad, Steam Tower, Reel Rush, Reel Rush touch, Muse: Wild Inspiration, Victorious, Victorious touch, Ghost Pirates, Johnny the Octopus и Mega Gems вносят 50% вклад в отыгрыш бонусов. Jackpot 6000, Mega Joker, Gypsy Rose and Safari вносят 75% вклад в отыгрыш бонусов.</p>
                    <p>
                        Учтите, что в слоты  Ninja, Dr. Jekyll &amp; Mr. Hyde, Devil's Delight, The Wish Master, а также все игры с джекпотом нельзя играть на бонусные деньги.
                    </p>
                </li>
                <li>
                    <span>8.</span>

                    <p>Игроки могут запросить вывод их депозита до выполнения условий отыгрыша. Тем не менее, как бонус, так и выигрыш, полученный при игре с ним, будут отменены. Пожалуйста, учтите, что во время игры с активным бонусом игрок теряет первыми настоящие деньги.</p>
                </li>
                <li>
                    <span>9.</span>

                    <p>Срок действия всех депозитных и бездепозитных бонусов истекает через 30 дней, если не указано иное.</p>
                </li>
                <li>
                    <span>10.</span>

                    <p>Бонусные средства и выигрыш будут отменены после истечения срока действия бонуса.</p>
                </li>
                <li>
                    <span>11.</span>

                    <p>Во время игры сделанные ставки снимаются с баланса игрока. Но если на балансе средств больше нет, то они начнут сниматься с бонусного баланса.</p>
                </li>
                <li>
                    <span>12.</span>

                    <p>Максимальный размер ставки с активным бонусом составляет 300 RUB, 5 EUR/GBP/CAD/AUD/USD, 50 SEK/NOK либо 25 mBTC (0.025 BTC). Это касается и двойных отыгрышей после окончания игрового раунда, например, отыгрыш выигрыша с игрового раунда Х на красное/черное.</p>
                </li>
                <li>
                    <span>13.</span>

                    <p>Любой бонус можно отменить до выполнения условий отыгрыша. Пожалуйста, обратитесь в службу поддержки казино Casinobit, чтобы узнать больше о том, как отменить бонус. Или воспользуйтесь кнопкой "Отменить" в личном профиле, раздел "Бонусы".</p>
                </li>
                <li>
                    <span>14.</span>

                    <p>За один раз можно получить только один бонус. Бонусы, относящиеся к депозитам, не складываются. Любые бонусы или выигрыш, полученные путем мошенничества, будут обнулены менеджментом казино Casinobit.</p>
                </li>
                <li>
                    <span>15.</span>

                    <p>Любой выигрыш, полученный при игре с бонусом или фриспинами уже после того, как сам бонус отыгран, проигран или отменен, будет вычтен.</p>
                </li>
                <li>
                    <span>16.</span>

                    <p>Все выплаты до обработки будут проходить внутреннюю проверку. Казино Casinobit оставляет за собой все права отменить бонус либо весь выигрыш, если проверка пройдена не успешно. Тем самым игрок заранее соглашается с этим правилом.</p>
                </li>
                <li>
                    <span>17.</span>

                    <p>Если после такой проверки выявится, что игрок(и) применяет мошеннические стратегии, пользуется технической неполадкой или ошибкой в системе или программе либо принимает участие в какой-либо деятельности, которую казино Casinobit полностью по своему усмотрению посчитает неправомерной (например, "злоупотребление бонусами"), то казино Casinobit оставляет за собой право упразднить право такого игрока пользоваться промо-акцией (и/или отменить выигрыш, полученный таким путем). Лишь выигрышные и проигрышные ставки будут вносить вклад в отыгрыш бонуса.</p>
                </li>
                <li>
                    <span>18.</span>

                    <p>Запрещается иметь более одного аккаунта. Создание дополнительных аккаунтов в казино Casinobit с целью получения бонусов будет расцениваться как злоупотребление бонусами, после чего будет производиться конфискация средств.</p>
                </li>
                <li>
                    <span>19.</span>

                    <p>Согласно правилам провайдеров программного обеспечения казино, бонусные раунды и фриспины не претендуют на джекпот-бонусы. Лишь раунды на реальные деньги претендуют на них.</p>
                </li>

                <li>

                    <span>20.</span>
                    <p>
                        Ставки с минимальным риском в любой игре (т.е. ставка, сделанная пропорционально на несколько различных результатов в одной руке для создания “действия” с минимальным риском) не будут учитываться при отыгрыше. Примером ставки с минимальным риском является одновременная ставка на красное и черное в рулетке или, например, одновременная ставка на решение игрока и банкира в баккаре. В таких и подобных им случаях игрок рискует потерять как бонус, так и выигрыш, полученный при игре с ним.
                    </p>
                </li>

                <li>

                    <span>21.</span>
                    <p>
                        Если обнаружится, что группа игроков связана одним типом платежной системы, геолокацией или IP-адресом, а также использует одинаковые стратегии игры, то Casinobit по своему усмотрению имеет право конфисковать бонусные средства и/или депозит.
                    </p>
                </li>

                <li>

                    <span>22.</span>
                    <p>
                        Бонусные средства, полученные при игре с фриспинами, выданными на игры SoftSwiss, можно использовать для ставок лишь в играх SoftSwiss, если не указано иное.
                    </p>
                </li>


                <li>
                    <span>23.</span>

                    <p>Если во время игры в казино Casinobit, Вы выиграете сумму, рассматриваемую менеджментом казино как достойную публичного освещения, Вы соглашаетесь принять участие в любом подобном мероприятии, организованном казино Casinobit. Хотя все Ваши личные данные находятся в казино Casinobit под защитой, но мы оставляем за собой право использовать Ваше имя и первую букву фамилии в любых своих объявлениях о результатах промо-акции на странице сайта или лобби.</p>
                </li>
                <li>
                    <span>24.</span>

                    <p>В спорных случаях решение казино Casinobit будет окончательным.</p>
                </li>
                <li>
                    <span>25.</span>

                    <p>Правила и условия, опубликованные на сайте (и обновляемые время от времени), написаны на английском языке, и только английская версия правил и условий является базовой и основной. Могут быть предоставлены адекватные переводы и на другие языки. Тем не менее, в случае разногласий между английской и переводной версиями, английская версия будет иметь приоритет над переводной.</p>
                </li>
                <li>

                    <p>Последнее обновление: 3 февраля 2017 года</p>
                </li>

            </ul>
        </li>
    </ul>
</div>

@elseif(\Illuminate\Support\Facades\Config::get('lang') == 'en')

<div id="uls">
    <h3>BONUS TERMS AND CONDITIONS</h3>
    <ul class="parent-list">

                <li>
                    <h4>NO DEPOSIT BONUSES TERMS AND CONDITIONS</h4>
                    <ul>
                        <li>
                            <span>1a.</span>

                            <p>The maximum winnings that will be paid out resulting from a free bonus or free spins without deposit (eg. the 20 free spins upon registration, Wednesday Free Spins Drops, Second Chance Spins) will be 100 EUR/USD/CAD/AUD, 1,000 SEK/NOK or 6 000 RUB. Max winnings for GBP is £50 (even if the amount of winnings accrued by you utilizing this bonus is in excess of this amount). In regards to BTC players, the maximum winnings that will be paid out resulting from a free bonus or free spins without deposit will be 100 mBTC. Any winnings exceeding this amount will be forfeited.</p>
                        </li>
                        <li>
                            <span>1b.</span>

                            <p>The Wagering Requirements for Free and Registration bonuses are forty (40) times the Bonus sum awarded to you, unless stated otherwise in the supplemental Terms and Conditions. (For Registration bonuses, extra spins, free spins and free bonuses which require NO deposit, note that no winnings at all may be withdrawn/transferred until you have transferred at least 20 EUR/GBP/USD/CAD/AUD, 200 SEK/NOK, 1,000 RUB or 0.05 BTC (or currency equivalent) into your Player Account. In addition, no winnings accrued in connection with any Free Bonus may be withdrawn/transferred until the wagering requirements have been met.)</p>
                        </li>
                        <li>
                            <span>2a.</span>

                            <p>For no deposit bonus withdrawals, we reserve the right to request a KYC. Regarding the KYC (Know Your Customer), the following documents may be needed for verification: One government approved identity card (ID card, passport and driver's license) with a picture, as well as proof of residency such as a utility bill (which is not older than 2 months). If your deposit method was via a credit card we will require a scanned copy of the credit card (front and back) which you used.</p>
                        </li>
                        <li>
                            <span>2b.</span>

                            <p>General bonus terms and conditions apply.</p>
                        </li>
                        <li>
                            <span>3.</span>

                            <p>Players requesting a withdrawal of money won with a no deposit bonus cannot do so via bank transfer.</p>
                        </li>
                        <li>
                            <span>4.</span>

                            <p>Players with disposable email addresses are not eligible for any free spins without deposit. If a player despite this would receive free spins without a deposit being made, all winnings from the spins will be confiscated.</p>
                        </li>
                        <li>
                            <span>5a.</span>

                            <p>Players from the following countries are not eligible for the no deposit free spins: Albania (AL)
                                Algeria (DZ)
                                Angola (AO)
                                Austria (AT)
                                Bahrain (BH)
                                Bangladesh (BD)
                                Belarus (BY)
                                Benin (BJ)
                                Bosnia and Herzegovina (BA)
                                Botswana (BW)
                                Bulgaria (BG)
                                Burundi (BI)
                                Cameroon (CM)
                                Cape Verde (CV)
                                Chad (TD)
                                Comoros (KM)
                                Croatia (HR)
                                Czech Republic (CZ)
                                Côte d'Ivoire (CI)
                                Djibouti (DJ)
                                Egypt (EG)
                                Equatorial Guinea (GQ)
                                Eritrea (ER)
                                Ethiopia (ET)
                                Gabon (GA)
                                Gambia (GM)
                                Georgia (GE)
                                Ghana (GH)
                                Greece (GR)
                                Guinea (GN)
                                Guinea-Bissau (GW)
                                Hungary (HU)
                                India (IN)
                                Indonesia (ID)
                                Iran (IR)
                                Iraq (IQ)
                                Islamic Republic of (IR)
                                Jordan (JO)
                                Kazakhstan (KZ)
                                Kenya (KE)
                                Kuwait (KW)
                                Lebanon (LB)
                                Lesotho (LS)
                                Liberia (LR)
                                Macedonia Republic of (MK)
                                Madagascar (MG)
                                Malawi (MW)
                                Malaysia (MY)
                                Mali (ML)
                                Mauritania (MR)
                                Mauritius (MU)
                                Moldova Republic of (MD)
                                Mongolia (MN)
                                Montenegro (ME)
                                Morocco (MA)
                                Mozambique (MZ)
                                Namibia (NA)
                                Nepal (NP)
                                Niger (NE)
                                Nigeria (NG)
                                Oman (OM)
                                Pakistan (PK)
                                Palestine State of (PS)
                                Philippines (PH)
                                Poland (PL)
                                Portugal (PT)
                                Romania (RO)
                                Rwanda (RW)
                                Sao Tome and Principe (ST)
                                Senegal (SN)
                                Serbia (RS)
                                Seychelles (SC)
                                Sierra Leone (SL)
                                Slovakia (SK)
                                Slovenia (SI)
                                Somalia (SO)
                                South Sudan (SS)
                                Sudan (SD)
                                Swaziland (SZ)
                                Syrian Arab Republic (SY)
                                Tanzania United Republic of (TZ)
                                Thailand (TH)
                                Togo (TG)
                                Tunisia (TN)
                                Uganda (UG)
                                United Arab Emirates (AE)
                                Vietnam (VN)
                                Yemen (YE)
                                Zambia (ZM) and
                                Zimbabwe (ZW)</p>
                        </li>
                    </ul>
                </li>
                <li>
                    <h4>FREE SPINS TERMS AND CONDITIONS</h4>
                    <ul>
                        <li>
                            <span>1.</span>

                            <p>When it comes to the 180 additional Free Spins that are a part of the welcome package. A minimum of 20 EUR/GBP/USD/CAD/AUD, 200 SEK/NOK, 1,250 RUB or 0.05 BTC is required to receive the free spins. You also need to wager the amount once.</p>
                        </li>
                        <li>
                            <span>2.</span>

                            <p>You do not have to enter a bonus code to qualify for free spins.</p>
                        </li>
                        <li>
                            <span>3.</span>

                            <p>Any winnings generated from free spins will be awarded with a wagering requirement of 40 (forty).</p>
                        </li>
                        <li>
                            <span>4.</span>

                            <p>To activate your free spins you must access and open the game within 3 days of them being credited, otherwise they will expire.</p>
                        </li>
                        <li>
                            <span>5.</span>

                            <p>Once your free spins have been activated you must complete all game rounds within 1 days otherwise they will expire.</p>
                        </li>
                        <li>
                            <span>6.</span>

                            <p>Casinobit reserves the right to cancel or amend these terms &amp; conditions at any point without any prior notice.</p>
                        </li>
                        <li>
                            <span>7.</span>

                            <p>The 180 Free Spins that are being credited as a part of our welcome package will be credited in increments of 20 per day for 9 days straight (as long as the player qualifies and meet all the requirements for the promotion). The first 20 Free Spins will be credited 23 hours after your first deposit and then in 24 hour increments following the first crediting.</p>
                        </li>
                        <li>
                            <span>8.</span>

                            <p>Where Starburst is available, free spins will be credited on that game as a part of the welcome package. If Starburst is not available due to currency or country restriction, free spins will be given on Fruit Zen or Boomanji.</p>
                        </li>
                    </ul>
                </li>
                <li>
                    <h4>GENERAL BONUS TERMS AND CONDITIONS</h4>
                    <ul>
                        <li>
                            <span>1.</span>

                            <p>All customer offers are limited to one per person, family, household address, email address, telephone number, same payment account number (e.g. debit or credit card, NETeller etc), IP, and shared computer, e.g. public library or workplace.</p>
                        </li>
                        <li>
                            <span>2.a</span>

                            <p>Players who are solely making deposits when there is a bonus available will risk of having their winnings from the bonus confiscated and being left with the initial deposited amount of the bonus in question. Players who make 5 deposits in a row (excluding welcome package) to the casino and trigger a bonus on all of those will risk of losing their winnings from the bonus.<br><br>

                                At least a single genuine deposit without bonus release has to be made prior to reaching 5 bonus releasing deposits in a row. The amount of the deposit has to be at least the average amount of the previous deposits made that triggered a bonus.<br><br>

                                Decision if  to confiscate funds will be made by the Casinobit Management and the decision made will be final.</p>
                        </li>
                        <li>
                            <span>2b.</span>

                            <p>We reserve the right to not pay players using disposable email addresses</p>
                        </li>
                        <li>
                            <span>2c.</span>
                            <p>Players from the following countries are not eligible for any promotional offer: Greece (GR), Hungary (HU), Mauritius (MU), Slovenia (SI), Croatia (HR), Bosnia and Herzegovina (BA), Macedonia, Republic of (MK), Austria (AT) Montenegro (ME) and Serbia (RS)</p>

                        </li>
                        <li>
                            <span>2d.</span>

                            <p>We reserve the right to lower the percentage of the Monday Reload bonus to a 25% bonus for players who we deem have received an un-proportionate level of bonuses based on their bonus release to deposit ratio.</p>
                        </li>

                        <li>
                            <span>3.</span>

                            <p>To claim the deposit welcome bonus or any deposit bonus (excluding free spins), the user must make a minimum deposit of 20 EUR/GBP/USD/CAD, 200 SEK/NOK, 1,250 RUB or 50 mBTC. For example, the player will automatically receive their first bonus after depositing an initial 20 EUR/GBP/USD/CAD/AUD, 200 SEK/NOK, 1,250 RUB or 0.05 BTC. The second deposit bonus will be triggered once the player makes a second deposit of 20 EUR/GBP/USD/CAD, 200 SEK/NOK, 1,250 RUB or 50 mBTC or more at Casinobit. The same rule applies for the third and the fourth deposit bonus.</p>
                        </li>
                        <li>
                            <span>4.</span>

                            <p>Once the first step of the welcome package is activated, the customer can then switch currency as follows, i.e. if a player deposits €20 and activates the first step of welcome package, he can then claim the second step by depositing in bitcoins.</p>
                        </li>
                        <li>
                            <span>5.</span>

                            <p>All bonuses (unless otherwise specified) need to be wagered 40 (forty) times before the funds can be withdrawn.</p>
                        </li>
                        <li>
                            <span>6.</span>

                            <p>Please note that different games contribute to a different percentage towards the wagering requirements. Slots contribute 100% (aside from excluded ones and classic slots, see below), while all table games and video poker contribute 5% (live games do not contribute).</p>
                        </li>
                        <li>
                            <span>7.</span>

                            <p>The following games do not contribute towards the completion of the wagering requirement of a bonus: Immortal Romance, Aliens, Mega Moolah, Dead or Alive, Ragnarok, Pocket Dice, Lucky Angler, Eggomatic, Big Bang, The Dark Knight, Peek-A-Boo, Tomb Raider, The Dark Knight Rises, Forsaken Kingdom, Scrooge, Secret of the Stones, Champion of the Track, Robin Hood, Tomb Raider 2, Queen of Gold and Castle Builder. </p>
                            <p>Bloodsuckers, Bloodsuckers touch, Kings of Chicago, Simsalabim, Zombies, Jack Hammer 2, Jack Hammer 2 touch, Demolition Squad, Steam Tower, Reel Rush, Reel Rush touch, Muse: Wild Inspiration, Victorious, Victorious touch, Ghost Pirates, Johnny the Octopus and Mega Gems contribute at a 50% rate. Jackpot 6000, Mega Joker, Gypsy Rose, Good Girl Bad Girl and Safari contribute at a 75% rate.</p>
                            <p>Kindly note that Ninja, Dr. Jekyll &amp; Mr. Hyde, Devil's Delight, The Wish Master and Jackpot Games are not able to be played with bonus money.   </p>
                        </li>
                        <li>
                            <span>8.</span>

                            <p>Players can request a withdrawal of their deposited amount prior to meeting the wagering requirements. However, the bonus amount and winnings will be forfeited as a result. Please note that when playing with a bonus, real money gets exhausted first.</p>
                        </li>
                        <li>
                            <span>9.</span>

                            <p>All deposit and free cash bonuses will expire after 30 days, unless otherwise stated.</p>
                        </li>
                        <li>
                            <span>10.</span>

                            <p>Bonus funds and winnings will be forfeited upon expiry of the bonus.</p>
                        </li>
                        <li>
                            <span>11.</span>

                            <p>When betting, placed bets are deducted from the player’s cash balance. However, if no cash balance is available the bet placed will be deducted from the player’s bonus balance.</p>
                        </li>
                        <li>
                            <span>12.</span>

                            <p>Until the play through requirements have been met, the maximum bet that can be placed is 5 EUR/GBP/USD/CAD/AUD, 50 SEK/NOK or 300 RUB. When it comes to Bitcoin players the maximum bet is 25 mBTC (0.025 BTC). This includes double up wagers after the game round has been completed, for example, wagering winnings from X game round on red/black.</p>
                        </li>
                        <li>
                            <span>13.</span>

                            <p>Any bonus can be removed prior to any wagering conditions being met. Please contact Casinobit support to learn more or use the forfeit button under ’Bonuses’.</p>
                        </li>
                        <li>
                            <span>14.</span>

                            <p>Only one bonus can be claimed at a time. Deposit related bonuses cannot be "stacked". Casinobit.com management reserve the right to void any bonuses and/or winnings obtained by fraudulent behavior.</p>
                        </li>
                        <li>
                            <span>15.</span>

                            <p>Any "free spin" or "bonus" winnings resulting from bonus funds after the bonus has been wagered, lost or forfeited shall be removed.</p>
                        </li>
                        <li>
                            <span>16.</span>

                            <p>All withdrawals will be subject to an internal audit before being processed. Casinobit reserves all rights to void Bonuses or any winnings for failed audits. Player hereby consents in advance to same.</p>
                        </li>
                        <li>
                            <span>17.</span>

                            <p>If, upon such a review, it appears that a Player(s) are participating in strategies, taking advantage of any software or system bug or failure, or participating in any form of activity that Casinobit, in its sole and complete discretion, deems to be abusive ('Promotion Abuse'), Casinobit reserves the right to revoke the entitlement of such a Player to receive or benefit from the promotion (and/or withhold the pay out of the proceeds of such abuse to the Player/s in question). Only fully settled bets (i.e. bets that result in a win or loss) will be counted towards wagering.</p>
                        </li>
                        <li>
                            <span>18.</span>

                            <p>Multiple accounts are not permitted. Creating more than one accounts with Casinobit in order to claim any bonuses is deemed as bonus abuse and may result in confiscated funds.</p>
                        </li>
                        <li>
                            <span>19.</span>

                            <p>Bonus round and free spins do not qualify for the jackpot bonuses pursuant to casino software provider rules. Only real money rounds qualify for the jackpot bonuses.</p>
                        </li>
                        <li>
                            <span>20.</span>

                            <p>Minimal risk bets on any games (i.e. betting in proportions on different outcomes in the same hand to create "action" with minimal risk) do not qualify for completing the wagering requirement. Examples of minimal risk bets include betting on red and black simultaneously in Roulette, and betting player and banker simultaneously in Baccarat. Players who are found to be adopting these practices risk of having their bonus and winnings confiscated. </p>
                        </li>
                        <li>
                            <span>21.</span>

                            <p>If it's discovered that a group players are using the same betting patterns and are connected via (but not limited to) location, banking pattern or IP, Casinobit management will at it's own discretion have the mandate to confiscate bonus winnings and/or deposit.  </p>
                        </li>
                        <li>
                            <span>22.</span>

                            <p>If not stated otherwise, bonus money derived from free spins given on Softswiss games can only be wagered on other Softswiss games.  </p>
                        </li>


                        <li>
                            <span>23.</span>

                            <p>If, while playing at Casinobit Casino, you win a sum regarded by Casinobit Management as worthy of publicity, you agree to make yourself available for any event of such nature arranged by Casinobit. While Casinobit protects all personal data entrusted to us, we reserve the right to use first names and the first initial of the last name in any Casinobit announcement about promotion results or on the website or lobby.</p>
                        </li>
                        <li>
                            <span>24.</span>

                            <p>Regarding the bonuses and promotions, all times and dates are stated in CET.</p>
                        </li>

                        <li>
                            <span>25.</span>

                            <p>In the event of any dispute, the decision of Casinobit will be final.</p>
                        </li>
                        <li>
                            <span>26.</span>

                            <p>The Casinobit and Conditions as published on the Website (and updated from time to time) are in English and it is the English version of these Terms and Conditions that form the basis of these Terms and Conditions only. Translations into other languages may be made as a service and are made in good faith. However, in the event of differences between the English version and a translation, the English version has priority over any translation.</p>
                        </li>
                        <li>
                            <p>Last updated: Mar 3rd, 2017</p>
                        </li>

                    </ul>
                </li>
            </ul>

</div>

@endif

</div>
@endsection