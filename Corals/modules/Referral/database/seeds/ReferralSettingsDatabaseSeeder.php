<?php

namespace Corals\Modules\Referral\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReferralSettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('settings')->insert([
            [
                'code' => 'referral_point_value',
                'type' => 'NUMBER',
                'category' => 'Referral',
                'label' => 'Value per reword point',
                'value' => '0.1',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
