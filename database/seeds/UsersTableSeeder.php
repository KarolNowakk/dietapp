<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        App\User::create([
            'name' => 'Karol',
            'email' => 'karol@eamil.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => Str::random(10),
        ]);
        factory(App\User::class, 10)->create();

        App\User::all()->each(function ($user) {
            $user->notWantedSubstances()->attach(random_int(1, 7));
        });
    }
}
