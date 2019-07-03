@if ($currentUser != false)
    <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
    <script>
        var balance = {
            'total': {
                'element': $(".balancebox-getbalance"),
                'val': {{ $currentUser->getBalance() }},
                'timer': null,
                'finish_timer' : null
            },
            'real': {
                'element': $(".balancebox-getrealbalance"),
                'val': {{ $currentUser->getRealBalance() }},
                'timer': null,
                'finish_timer' : null
            },
            'bonus': {
                'element': $(".balancebox-getbonusbalance"),
                'val': {{ $currentUser->getBonusBalance() }},
                'timer': null,
                'finish_timer' : null
            },
            'currency': '{{ ' m' . strtoupper($currentUser->currency->title) }}',
            'updater': function (selector, finishVal) {

                clearInterval(this[selector].timer);
                clearInterval(this[selector].finish_timer);
                var step = 25;
                var time = 500;

                var startVal = Number(this[selector].val);
                var delta = (Number(finishVal) - startVal) / (time / step);

                this[selector].timer = setInterval(this.f.bind(this), step, selector, delta);

                this[selector].finish_timer = setTimeout(this.e.bind(this), time, selector, finishVal);
            },
            'f': function (selector, delta) {
                this[selector].val += delta;
                this[selector].element.text(this[selector].val.toFixed(2) + this.currency);
            },
            'e': function (selector, finishVal) {
                clearInterval(this[selector].timer);
                this[selector].element.text(finishVal.toFixed(2) + this.currency);
                this[selector].val = finishVal;
                @if ($testMode == true)
                    this.a.loop = false;
                    this.a.currentTime = 0;
                    this.a.play();
                @endif
            },
            'a': new Audio('/media/moregold.mp3')

        };

        // setInterval(function(){
        //     var keys = ['total', 'real', 'bonus'];
        //     var key = keys[Math.floor(Math.random()*3)];
        //     balance.updater(key, Math.round(Math.random()*50000+5000)/100);
        // }, 4000);

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: 'eu',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                }
            }
        });

        var channel = pusher.subscribe('private-App.User.{{ \Illuminate\Support\Facades\Auth::user()->id }}');
        channel.bind('transaction', function (data) {
            console.log(data);
            balance.currency = data.currency;
            balance.updater('total', data.balance);
            balance.updater('real', data.real_balance);
            balance.updater('bonus', data.bonus_balance);
        });
    </script>
@endif