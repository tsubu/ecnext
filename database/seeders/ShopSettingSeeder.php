<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ShopSetting::create([
            'shop_name' => 'EC NEXT Demo Store',
            'company_name' => 'EC NEXT Co., Ltd.',
            'email' => 'contact@example.com',
            'phone' => '03-1234-5678',
            'postal_code' => '100-0001',
            'address1' => '東京都千代田区千代田1-1',
            'address2' => '',
            'trade_law_manager' => 'John Doe',
            'trade_law_address' => '東京都千代田区千代田1-1',
            'trade_law_tel' => '03-1234-5678',
            'trade_law_email' => 'trade@example.com',
            'trade_law_price_info' => '表示価格は消費税込みです。配送料は別途かかります。',
            'trade_law_payment_methods' => 'クレジットカード、銀行振込、代金引換がご利用いただけます。',
            'trade_law_delivery_info' => 'ご注文確定後、3営業日以内に発送いたします。',
            'trade_law_return_policy' => '商品到着後7日以内で未開封の場合に限り、返品を承ります。',
        ]);
    }
}
