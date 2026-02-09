<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PasswordUpdateRequest;
use Illuminate\Console\Command;

class SendPasswordUpdateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-password-update-notifications';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$users = User::whereDate('last_password_change', '<=', now()->subDays(30))->get();
        $users = User::find(1)->get();
        foreach ($users as $user) {
            $user->notify(new PasswordUpdateRequest());
        }

        $this->info('Password update notifications sent successfully.');
    }
}
