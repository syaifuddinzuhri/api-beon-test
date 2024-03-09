<?php

namespace Database\Seeders;

use App\Constant\GlobalConstant;
use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::create([
            'name' => 'Iuran Kebersihan',
            'type' => GlobalConstant::IN,
            'nominal' => 15000
        ]);
        PaymentType::create([
            'name' => 'Iuran Satpam',
            'type' => GlobalConstant::IN,
            'nominal' => 100000
        ]);
    }
}
