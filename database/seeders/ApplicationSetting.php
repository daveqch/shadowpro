<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ApplicationSetting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ApplicationSetting::create([
            'item_name' => 'ShadowPro',
            'item_short_name' => 'ShadowPro',
            'item_version' => 'V 2.0',
            'company_name' => 'ambitiousitbd',
            'company_email' => 'ambitiousitbd@gmail.com',
            'company_address' => 'Natore, Bangladesh',
            'developed_by' => 'Ambitiousitbd',
            'developed_by_href' => 'http://ambitiousit.net/',
            'developed_by_title' => 'Your hope our goal',
            'developed_by_prefix' => 'Developed by',
            'support_email' => 'ambitiousitbd@gmail.com',
            'language' => 'en',
            'is_demo' => '0',
            'time_zone' => 'Asia/Dhaka',
        ]);
    }
}
