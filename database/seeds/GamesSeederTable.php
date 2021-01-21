<?php

use Illuminate\Database\Seeder;

class GamesSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            ['name' => 'Call of Duty', 'version' => '2019'],
            ['name' => 'Mortal Kombat', 'version' => '2012'],
            ['name' => 'FIFA', 'version' => '2020'],
            ['name' => 'Just Cause', 'version' => '2018'],
            ['name' => 'Apex Legend', 'version' => '2017'],
        ];
        foreach ($games as $game) {
            DB::table('games')->insert([
                'name' => $game['name'],
                'version' => $game['version'],
                'created_at' => \Carbon\Carbon::now(),
                'Updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
