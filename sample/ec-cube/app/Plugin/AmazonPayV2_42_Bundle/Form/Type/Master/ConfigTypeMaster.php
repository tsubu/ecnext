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

namespace Plugin\AmazonPayV2_42_Bundle\Form\Type\Master;

class ConfigTypeMaster
{
    const ACCOUNT_MODE = [
        'SHARED' => 1,
        'OWNED' => 2,
    ];

    const ENV = [
        'SANDBOX' => 1,
        'PROD' => 2,
    ];

    const SALE = [
        'AUTORI' => 1,
        'CAPTURE' => 2,
    ];

    const CART_BUTTON_PLACE = [
        'AUTO' => 1,
        'MANUAL' => 2,
    ];

    const MYPAGE_LOGIN_BUTTON_PLACE = [
        'AUTO' => 1,
        'MANUAL' => 2,
    ];

    const PRODUCTS_BUTTON_PLACE = [
        'AUTO' => 1,
        'MANUAL' => 2,
    ];
}
