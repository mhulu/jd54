<?php

use App\Pop;
use Illuminate\Database\Seeder;

class PopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Pop::class, 60)->create()->each(function ($u) {
        	$u->popArchives()->save(factory(App\PopArchive::class)->make());
        });
    }
}
