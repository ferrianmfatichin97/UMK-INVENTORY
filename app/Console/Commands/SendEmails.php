<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Support\DripEmailer;
use Illuminate\Support\Facades\Log;


class SendEmails extends Command
{
   protected $signature = 'mail:send {item}';

   protected $description = 'Send an email to the specified address';

   public function handle()
   {

    $itemJson = $this->argument('item');
    $item = json_decode($itemJson, true);

    Log::info('ini command SendEmails: ' . json_encode($item));

    $users = ['radenmasvery97@gmail.com', 'ferrianmfatichin@gmail.com'];
    foreach ($users as $user) {
        Mail::to($user)
            ->send(new AdminMail($item));
            Log::info('After Send Mail: ' . json_encode($item));
    }
   }
}
