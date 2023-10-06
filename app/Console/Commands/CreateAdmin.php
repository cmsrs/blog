<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin {admin-email} {pass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and Edit admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = $this->argument('admin-email');
        $pass =  $this->argument('pass');

        $user = User::where( 'email', $admin)->where('is_admin', 1)->first();

        if($user){
            $this->info("Edit user-admin {$admin} with password: {$pass}");
            $user->password = $pass;
            $user->save();
        }else{
            $this->info("Create user-admin {$admin} with password: {$pass}");
            $user = new User;
            $user->email =  $admin;
            $user->name =  'Admin';
            $user->password = $pass;
            $user->is_admin = 1;
            $user->save();
        }

        return 0;
    }
}
