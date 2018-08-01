<?php

namespace App\Console\Commands;

use App\Slot;
use App\Slots\Curl;
use App\User;
use Illuminate\Console\Command;
use App\Slots\Ezugi;
use App\Slots\Casino;
use Illuminate\Support\Facades\Auth;

class SlotChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slots:checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all slots';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slots = Slot::all();

        $user = User::find(6);

        Auth::login($user);

        foreach ([0, 1] as $is_mobile) {
            if($is_mobile)
            {
                Casino::$mobile_debug = true;
                echo "\n";
                $this->info("MOBILE");
                echo "\n";
            }
            else $this->info("DESKTOP");

            foreach ($slots as $slot) {
                if($is_mobile)
                {
                    if($slot->is_mobile != 1) continue;
                }

                try {
                    if ($slot->category_id == 6) {
                        $casino = new Ezugi();
                        $data = $casino->getStartUrl($slot);
                    } else {
                        $casino = new Casino(env('CASINO_OPERATOR_ID'), env('CASINO_KEY'));
                        $data = $casino->SlotStartURL($slot);
                    }

                    if (isset($data['url']) and !empty($data['url'])) {

                        Curl::Start();
                        Curl::$verbose = false;

                        $page = Curl::OpenPage($data['url']);

                        if (Curl::$http_status != 200) throw new \Exception("Http code != 200");
                    }
                } catch (\Exception $e) {
                    $this->info('#' . $slot->id . ' ' . $slot->display_name . ' / ' . $slot->category->name);
                    $this->error(' Error: ' . $e->getMessage());
                }
            }
        }

    }
}
