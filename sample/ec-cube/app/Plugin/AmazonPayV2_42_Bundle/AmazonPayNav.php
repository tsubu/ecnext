<?php

/*
 * Amazon Pay V2 for EC-CUBE4.2
 * Copyright(c) 2023 EC-CUBE CO.,LTD. all rights reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * This program is not free software.
 * It applies to terms of service.
 *
 */

namespace Plugin\AmazonPayV2_42_Bundle;

use Eccube\Common\EccubeNav;

class AmazonPayNav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'order' => [
                'children' => [
                    'amazon_pay_v2_admin_payment_status' => [
                        'name' => 'amazon_pay_v2.admin.nav.payment_list',
                        'url' => 'amazon_pay_v2_admin_payment_status',
                    ]
                ]
            ],
        ];
    }
}
