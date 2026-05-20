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

namespace Plugin\AmazonPayV2_42_Bundle\Exception;

class AmazonPaymentException extends \Exception
{
    // ErrorCodes.
    const UNDEFINED = false;

    const ZERO_PAYMENT = 101;

    const INVALID_PAYMENT_METHOD = 2;
    const AMAZON_REJECTED = 3;
    const EXPIRED = 5;

    public static $errorMessages = [
        self::ZERO_PAYMENT => 'Amazon Payは合計0円のお支払いに対応しておりません。',
        self::INVALID_PAYMENT_METHOD => 'Amazonアカウントでのお支払い選択において問題が発生しました。他の支払方法を選択するか、クレジットカード情報更新してください。',
        self::AMAZON_REJECTED => 'お支払い処理が失敗しました。他の支払い方法で再度購入してください。',
        self::EXPIRED => 'セッションの有効期限が切れました。',
    ];

    public static $amazon_error_list = [
        'InvalidPaymentMethod' => self::INVALID_PAYMENT_METHOD,
        'AmazonRejected' => self::AMAZON_REJECTED,
        'BuyerCanceled' => self::AMAZON_REJECTED,
        'AmazonCanceled' => self::AMAZON_REJECTED,
        'Declined' => self::INVALID_PAYMENT_METHOD,
        'Expired' => self::EXPIRED,
    ];

    /**
     * create method.
     *
     * @param int $error_code
     * @return AmazonPaymentException
     */
    public static function create(int $error_code)
    {
        if (!array_key_exists($error_code, self::$errorMessages)) {
            $message = '予期しないエラーが発生しました。';
        } else {
            $message = self::$errorMessages[$error_code];
        }

        return new self($message, $error_code);
    }

    /**
     * エラーの理由コードを受取って、エラーコードを返す.
     * 未定義のエラーを受取った場合はfalseを返す.
     *
     * @param $reason_code
     * @return bool|mixed
     */
    public static function getErrorCode($reason_code)
    {
        if (!array_key_exists($reason_code, self::$amazon_error_list)) {
            return self::UNDEFINED;
        }
        return self::$amazon_error_list[$reason_code];
    }
}
