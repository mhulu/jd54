<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IcenterSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PopSeeder::class);
    }
}
