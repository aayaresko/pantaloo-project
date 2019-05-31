<?php

namespace App\Http\Controllers;

use App\Slot;
use App\Type;
use App\User;
use App\Category;
use App\Slots\Ezugi;
use App\Slots\Casino;
use App\Http\Requests;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SlotController extends Controller
{
    public function test1()
    {
        $user = User::find(6);

        $casino = new Ezugi();

        $data = $casino->getStartUrl(Slot::find(1));

        return view('slot', ['data' => $data]);
    }

    public function casino()
    {
        if (Auth::check()) {
            $data = [
                'europe' => route('slot', Slot::find(304)),
                'baltic' => route('slot', Slot::find(303)),
                'latin' => route('slot', Slot::find(302)),
                'est_europe' => route('slot', Slot::find(301)),
                'usa' => route('slot', Slot::find(305)),
                'name' => 'Choose game',
            ];
        } else {
            $data = [
                'europe' => route('demo', Slot::find(304)),
                'baltic' => route('demo', Slot::find(303)),
                'latin' => route('demo', Slot::find(302)),
                'est_europe' => route('demo', Slot::find(301)),
                'usa' => route('demo', Slot::find(305)),
                'name' => 'Choose game',
            ];
        }

        $data['description'] = 'Новое казино биткоинов на русском с бездепозитным бонусом при регистрации. Отзывы. ТОП 10 рейтинга онлайн казино биткоин. Играть в лучшие бесплатные игры казино 2016, 2017 с моментальным выводом и без вложений.';
        $data['keywords'] = 'биткоин казино, биткоин казино без вложений, биткоин казино с бесплатными биткоинами, биткоин казино с бонусами, бездепозитные казино биткоинов, биткоин казино 2017, биткоин казино 2016, биткоин казино с бездепозитным бонусом, биткоин казино с выводом, онлайн казино биткоин, лучшие биткоин казино, биткоин казино с бездепозитным бонусом за регистрацию, биткоин игры казино, казино на биткоины с моментальным выводом, топ биткоин казино, играть в биткоин казино, биткоин казино отзывы, новое биткоин казино, биткоин казино на русском, казино биткоинов без вложений 2016, биткоин казино с бонусом при регистрации, рейтинг биткоин казино, топ 10 биткоин казино';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function blackjack()
    {
        if (Auth::check()) {
            $data = [
                'europe' => false,
                'baltic' => route('slot', [Slot::find(303), 'blackjack']).'?lobby=1',
                'latin' => route('slot', [Slot::find(302), 'blackjack']).'?lobby=1',
                'est_europe' => route('slot', [Slot::find(301), 'blackjack']).'?lobby=1',
                'usa' => route('slot', [Slot::find(305), 'blackjack']).'?lobby=1',
                'name' => 'Blackjack',
            ];
        } else {
            $data = [
                'europe' => false,
                'baltic' => route('demo', [Slot::find(303), 'blackjack']).'?lobby=1',
                'latin' => route('demo', [Slot::find(302), 'blackjack']).'?lobby=1',
                'est_europe' => route('demo', [Slot::find(301), 'blackjack']).'?lobby=1',
                'usa' => route('demo', [Slot::find(305), 'blackjack']).'?lobby=1',
                'name' => 'Blackjack',
            ];
        }

        $data['description'] = '';
        $data['keywords'] = '';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function roulette()
    {
        if (Auth::check()) {
            $data = [
                'europe' => route('slot', [Slot::find(304), 5001]),
                'baltic' => route('slot', [Slot::find(303), 601000]),
                'latin' => route('slot', [Slot::find(302), 1000]),
                'est_europe' => route('slot', [Slot::find(301), 501000]),
                'usa' => route('slot', [Slot::find(305)]),
                'name' => 'Roulette',
            ];
        } else {
            $data = [
                'europe' => route('demo', [Slot::find(304), 5001]),
                'baltic' => route('demo', [Slot::find(303), 601000]),
                'latin' => route('demo', [Slot::find(302), 1000]),
                'est_europe' => route('demo', [Slot::find(301), 501000]),
                'usa' => route('demo', [Slot::find(305)]),
                'name' => 'Roulette',
            ];
        }

        $data['description'] = 'Предлагаем сыграть в рулетку на биткоины совершенно без вложений, но, с моментальным выводом. Рулетка на биткоины очень популярна среди игроков всего мира, поэтому мы предлагаем ее своим посетителям.';
        $data['keywords'] = 'биткоин рулетка, биткоин рулетка без вложений, рулетка на биткоины с моментальным выводом';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function holdem()
    {
        if (Auth::check()) {
            $data = [
                'europe' => false,
                'baltic' => false,
                'latin' => false,
                'est_europe' => route('slot', [Slot::find(301), 507000]),
                'usa' => false,
                'name' => 'Holdem',
            ];
        } else {
            $data = [
                'europe' => false,
                'baltic' => false,
                'latin' => false,
                'est_europe' => route('demo', [Slot::find(301), 507000]),
                'usa' => false,
                'name' => 'Holdem',
            ];
        }

        $data['description'] = 'Здесь Вы можете играть онлайн в покер румы на биткоины. Покер румы - это сетевая игра в которой могу принимать участие люди со всего мира и соответственно делать ставки и выигрывать биткоины.';
        $data['keywords'] = 'покер на биткоины, биткоин покер румы, покер на биткоины играть';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function baccarat()
    {
        if (Auth::check()) {
            $data = [
                'europe' => false,
                'baltic' => false,
                'latin' => route('slot', [Slot::find(302), 'baccarat']).'?lobby=1',
                'est_europe' => false,
                'usa' => route('slot', [Slot::find(305), 'baccarat']).'?lobby=1',
                'name' => 'Baccarat',
            ];
        } else {
            $data = [
                'europe' => false,
                'baltic' => false,
                'latin' => route('demo', [Slot::find(302), 'baccarat']).'?lobby=1',
                'est_europe' => false,
                'usa' => route('demo', [Slot::find(305), 'baccarat']).'?lobby=1',
                'name' => 'Baccarat',
            ];
        }

        $data['description'] = '';
        $data['keywords'] = '';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function numbers()
    {
        if (Auth::check()) {
            $data = [
                'europe' => false,
                'baltic' => route('slot', [Slot::find(303), 602000]),
                'latin' => false,
                'est_europe' => false,
                'usa' => false,
                'name' => 'Numbers',
            ];
        } else {
            $data = [
                'europe' => false,
                'baltic' => route('demo', [Slot::find(303), 602000]),
                'latin' => false,
                'est_europe' => false,
                'usa' => false,
                'name' => 'Numbers',
            ];
        }

        $data['description'] = 'Предлагаем сыграть в нашем онлайн казино в кости на биткоин. У нас высокие ставки, с помощью которых, можно быстро пробиться в лидеры и получить большой выигрыш.';
        $data['keywords'] = 'биткоин кости казино';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function keno()
    {
        if (Auth::check()) {
            $data = [
                'europe' => false,
                'baltic' => route('slot', [Slot::find(303), 606000]),
                'latin' => false,
                'est_europe' => false,
                'usa' => false,
                'name' => 'Keno',
            ];
        } else {
            $data = [
                'europe' => false,
                'baltic' => route('demo', [Slot::find(303), 606000]),
                'latin' => false,
                'est_europe' => false,
                'usa' => false,
                'name' => 'Keno',
            ];
        }

        $data['description'] = '';
        $data['keywords'] = '';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function dice()
    {
        $data = [
            'europe' => route('slot', [Slot::find(304), 404000]),
            'baltic' => false,
            'latin' => false,
            'est_europe' => false,
            'usa' => false,
            'name' => 'Dice',
        ];

        $data['description'] = '';
        $data['keywords'] = '';
        $data['iso_code'] = session('iso_code');

        return view('casino', $data);
    }

    public function test()
    {
        if (Casino::isMobile()) {
            $slots = Slot::where('is_mobile', 1)->paginate(6);
        } else {
            $slots = Slot::paginate(16);
        }

        return view('test', ['slots' => $slots]);
    }

    public function startUrl(Slot $slot)
    {
        if ($slot->category_id == 6) {
            $casino = new Ezugi();
            $data = $casino->getStartUrl($slot, null, false, Config::get('lang'));
        } else {
            $casino = new Casino(env('CASINO_OPERATOR_ID'), env('CASINO_KEY'));
            $data = $casino->SlotStartURL($slot);
        }

        $data['category'] = $slot->category_id;

        return response()->json($data);
    }

    public function index()
    {
        if (Casino::isMobile()) {
            $slots = Slot::where('is_mobile', 1)->where('is_working', 1)->orderBy('raiting')->paginate(6);
        } else {
            $slots = Slot::orderBy('raiting')->where('is_working', 1)->paginate(30);
        }

        $meta = [
            'description' => 'Играть онлайн в слоты на биткоины предлагаем в этом разделе. Слоты - это имитация игровых автоматов, где выигрыш получают в биткоинах. Большой выбор игровых слотов и возможностей выиграть максимальный приз.',
            'keywords' => 'биткоин слоты',
        ];

        return view('slots', ['slots' => $slots, 'meta' => $meta]);
    }

    public function demo(Request $request, Slot $slot, $game_id = null)
    {
        if ($request->input('lobby')) {
            $lobby = true;
        } else {
            $lobby = false;
        }

        if ($slot->category_id == 6) {
            $casino = new Ezugi();
            $data = $casino->getStartUrl($slot, $game_id, $lobby, Config::get('lang'), true);
        } else {
            app()->abort(500);
        }

        if ($data['url']) {
            return redirect($data['url']);
        }

        return view('slot', ['slot' => $slot, 'data' => $data]);
    }

    public function freeSpins(Request $request)
    {
        if (Auth::user()->free_spins == 0) {
            app()->abort(500);
        }

        $slot = Slot::find(1);

        $casino = new Casino(env('FREE_CASINO_OPERATOR_ID'), env('FREE_CASINO_KEY'));
        $data = $casino->SlotStartURL($slot);

        if ($data['url']) {
            return redirect($data['url']);
        }

        return view('slot', ['slot' => $slot, 'data' => $data]);
    }

    public function get(Request $request, Slot $slot, $game_id = null)
    {
        if ($request->input('lobby')) {
            $lobby = true;
        } else {
            $lobby = false;
        }

        if ($slot->category_id == 6) {
            $casino = new Ezugi();
            $data = $casino->getStartUrl($slot, $game_id, $lobby, Config::get('lang'));
        } else {
            $casino = new Casino(env('CASINO_OPERATOR_ID'), env('CASINO_KEY'));
            $data = $casino->SlotStartURL($slot);
        }

        if ($data['url']) {
            return redirect($data['url']);
        }

        return view('slot', ['slot' => $slot, 'data' => $data]);
    }

    public function balance()
    {
        $transaction = Auth::user()->transactions()->where('status', 3)->where('notification', 0)->first();

        if ($transaction) {
            $sum = $transaction->sum;
        } else {
            $sum = false;
        }

        $transaction->notification = 1;
        $transaction->save();

        return response()->json(['balance' => Auth::user()->getBalance(), 'deposit' => $sum]);
    }

    public function adminSlots(Request $request)
    {
        $slots = Slot::orderBy('raiting');

        if ($request->input('q')) {
            $slots = $slots->where('display_name', 'LIKE', '%'.$request->input('q').'%');
        }

        $slots = $slots->get();

        return view('admin.slots', ['slots' => $slots]);
    }

    public function edit(Slot $slot)
    {
        return view('admin.slot', ['slot' => $slot]);
    }

    public function update(Slot $slot, Request $request)
    {
        $this->validate($request, [
            'display_name' => 'required|min:3|max:255',
            'raiting' => 'required|integer|min:0',
            'image' => 'mimes:jpeg,png',
            'type_id' => 'required',
            //'is_bonus' => 'required'
        ]);

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $web_path = '/images/'.$slot->id.'.png';
                $real_path = public_path().'/images/';

                if (file_exists($real_path.$slot->id.'.png')) {
                    unlink($real_path.$slot->id.'.png');
                }

                $request->file('image')->move($real_path, $slot->id.'.png');

                $slot->image = $web_path;
            } else {
                return redirect()->back()->withErrors(['Upload file problem']);
            }
        }

        $slot->display_name = $request->input('display_name');
        $slot->raiting = $request->input('raiting');

        $slot->demo_url = $request->input('demo_url');

        if ($request->input('is_working') == 'on') {
            $slot->is_working = 1;
        } else {
            $slot->is_working = 0;
        }

        if ($request->input('is_mobile') == 'on') {
            $slot->is_mobile = 1;
        } else {
            $slot->is_mobile = 0;
        }

        if ($request->input('is_bonus') == 'on') {
            $slot->is_bonus = 1;
        } else {
            $slot->is_bonus = 0;
        }

        $type = Type::find($request->input('type_id'));

        if (! $type) {
            return redirect()->back();
        }

        $slot->type()->associate($type);

        $slot->save();

        return redirect()->route('admin.slot', $slot)->with('msg', 'Slot info was saved');
    }

    public function filter(Request $request)
    {
        $slots = Slot::orderBy('raiting');

        if ($request->has('q')) {
            $slots = $slots->where('display_name', 'LIKE', '%'.$request->input('q').'%');
        }

        if ($request->has('category_id')) {
            $slots = $slots->where('category_id', $request->input('category_id'));
        }

        if ($request->has('type')) {
            $slots = $slots->where('type_id', $request->input('type'));
        }

        $slots = $slots->where('is_working', 1);

        if (Casino::isMobile()) {
            $slots = $slots->where('is_mobile', 1)->paginate(6);
        } else {
            $slots = $slots->paginate(30);
        }

        $pag = $slots->links();

        if ($pag) {
            $html = $pag->toHtml();
        } else {
            $html = '';
        }

        return response()->json(['slots' => json_decode($slots->toJson()), 'pagination' => $html]);
    }
}
