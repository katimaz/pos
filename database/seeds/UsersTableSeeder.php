<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_types')->insert([
            [
                'name' => 'Sales',
                'value' => 'S',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Rental',
                'value' => 'R',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Rental Termination',
                'value' => 'RT',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Sleep Study',
                'value' => 'PSG',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Titration',
                'value' => 'TIT',
                'created_by' => 'system',
                'updated_by' => 'system',
            ]
        ]);

        DB::table('users')->insert([
            'name' => 'CPAPPAL',
            'email' => 'cpappal@cpappal.com',
            'password' => bcrypt('12345678'),
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'AutoCPAP',
                'priority' => 1,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'BiLevel',
                'priority' => 2,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'TravelCPAP',
                'priority' => 3,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'OC',
                'priority' => 4,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'AirMini_acc',
                'priority' => 5,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'DSGo_acc',
                'priority' => 6,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Htd_Humidifier',
                'priority' => 7,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Nasal_Mask',
                'priority' => 8,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Pillow_Mask',
                'priority' => 9,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'FF_Mask',
                'priority' => 10,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Tubing',
                'priority' => 11,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Filter',
                'priority' => 12,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '_Frame',
                'priority' => 13,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '_Elbow',
                'priority' => 14,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '_Headgear',
                'priority' => 15,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '_Clip',
                'priority' => 16,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '_Cushion',
                'priority' => 17,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Hygiene',
                'priority' => 18,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Service',
                'priority' => 19,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Other',
                'priority' => 20,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Discount_Rebate',
                'priority' => 21,
                'created_by' => 'system',
                'updated_by' => 'system',
            ]

        ]);

        DB::table('products')->insert([
            [
                'category_id' => '4',
                'name' => 'Philips Respironics飛利浦偉康 – SimplyGo Mini 手提氧氣機',
                'price' => '28000',
                'maintenance_period' => '24',
                'device_check' => 'Y',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '1',
                'name' => 'Dreamstation 自動型呼吸機 – Philips Respironics 飛利浦偉康',
                'price' => '7600',
                'maintenance_period' => '12',
                'device_check' => 'Y',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '2',
                'name' => 'AirCurve 10 VAuto 雙氣壓自動型呼吸機 – ResMed 瑞思邁',
                'price' => '15800',
                'maintenance_period' => '36',
                'device_check' => 'Y',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '3',
                'name' => 'Dreamstation BiPAP-Pro 固定雙氣壓呼吸機 – Philips Respironics 飛利浦偉康',
                'price' => '10800',
                'maintenance_period' => '6',
                'device_check' => 'Y',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '5',
                'name' => 'Dreamstation 自動型呼吸機 DSX500',
                'price' => '7600',
                'maintenance_period' => '12',
                'device_check' => 'Y',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '6',
                'name' => 'DS. HumCorePack加熱濕潤器DSXHCP',
                'price' => '1000',
                'maintenance_period' => null,
                'device_check' => null,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '7',
                'name' => '6 ft. 22mm Universal Tube通用喉管',
                'price' => '1000',
                'maintenance_period' => null,
                'device_check' => null,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '7',
                'name' => 'Dreamstation 15mm Htd Tube 加熱喉',
                'price' => '1000',
                'maintenance_period' => null,
                'device_check' => null,
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'category_id' => '8',
                'name' => 'Wisp 鼻罩 – Philips Respironics 飛利浦偉康',
                'price' => '1000',
                'maintenance_period' => null,
                'device_check' => null,
                'created_by' => 'system',
                'updated_by' => 'system',
            ]

        ]);

        DB::table('customers')->insert([
            [
                'country_name' => '香港 HongKong',
                'name' => '陳大明',
                'address' => '香港九龍荔枝角青山道654-656號 浪淘大廈2樓E室',
                'delivery_address' => '香港九龍荔枝角青山道654-656號 浪淘大廈2樓E室',
                'phone' => '12345678',
                'remark' => '新客',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'country_name' => '香港 HongKong',
                'name' => '李四',
                'address' => '香港灣仔告士打道七號',
                'delivery_address' => '香港灣仔告士打道七號',
                'phone' => '98765432',
                'remark' => '轉介客',
                'created_by' => 'system',
                'updated_by' => 'system',
            ]
        ]);

        DB::table('currencies')->insert([
            [
                'name' => '港幣',
                'en_name' => 'HKD',
                'ratio' => '1',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '台幣',
                'en_name' => 'TWD',
                'ratio' => '4',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '人民幣',
                'en_name' => 'RMB',
                'ratio' => '1.14',
                'created_by' => 'system',
                'updated_by' => 'system',
            ]
        ]);

        DB::table('countries')->insert([
            [
                'name' => '香港 HongKong',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '台灣 Taiwan',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '澳門 Macau',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '中國 China',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '美國 USA',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '加拿大 Canada',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => '英國 UK',
                'created_by' => 'system',
                'updated_by' => 'system',
            ]
        ]);
    }
}
