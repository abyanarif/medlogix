<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'bank_name'], ['value' => 'Bank Central Asia (BCA)']);
        Setting::updateOrCreate(['key' => 'account_number'], ['value' => '12345678']);
        Setting::updateOrCreate(['key' => 'account_name'], ['value' => 'MedLogix']);
        Setting::updateOrCreate(['key' => 'monthly_fee'], ['value' => '50000']);
        Setting::updateOrCreate(['key' => 'price_monthly'], ['value' => '30000']);
        Setting::updateOrCreate(['key' => 'price_yearly'], ['value' => '300000']);
        Setting::updateOrCreate(['key' => 'price_addon_slot'], ['value' => '30000']);
    }
}
