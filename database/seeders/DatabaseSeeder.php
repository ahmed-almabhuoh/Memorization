<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if (! User::where('email', 'super@auto.com.ps')->exists()) {
            $user = new User();
            $user->fname = 'Super';
            $user->sname = 'Super';
            $user->tname = 'Super';
            $user->lname = 'Super';
            $user->position = 'manager';
            $user->email = 'super@auto.com.ps';
            $user->identity_no = '123456789';
            $user->phone = '0567077653';
            $user->gender = 'male';
            $user->status = 'active';
            $user->password = Hash::make('Ahmad0599ahmad!@#056');
            $user->save();
        }else {
            $user = User::where('email', 'super@auto.com.ps')->first();
            $user->fname = 'Super';
            $user->sname = 'Super';
            $user->tname = 'Super';
            $user->lname = 'Super';
            $user->position = 'manager';
            $user->email = 'super@auto.com.ps';
            $user->identity_no = '123456789';
            $user->phone = '0567077653';
            $user->gender = 'male';
            $user->status = 'active';
            $user->password = Hash::make('Ahmad0599ahmad!@#056');
            $user->save();
        }


    }
}
