<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(3)->create()->each(function($u) { // because Question has foreign key from User
            // questions() is the methode in User Model, -> hasMany
            $u->questions()->saveMany(\App\Models\Question::factory(rand(1,5))->make());    
        });
        
    }
}
