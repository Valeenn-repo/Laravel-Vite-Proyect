<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePasswords extends Command
{
    protected $signature = 'update:passwords';
    protected $description = 'Convierte contraseñas en texto plano a Bcrypt';

    public function handle()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if (!password_get_info($user->password_hash)['algo']) {
                $user->password_hash = Hash::make($user->password_hash);
                $user->save();
                $count++;
            }
        }

        $this->info("Se actualizaron {$count} contraseñas a Bcrypt.");
    }
}
