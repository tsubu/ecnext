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

namespace Plugin\AmazonPayV2_42_Bundle\Service\Method;

use Plugin\AmazonPayV2_42_Bundle\Service\AmazonOrderHelper;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonRequestService;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Order;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Service\Payment\PaymentMethodInterface;
use Eccube\Service\Payment\PaymentResult;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Symfony\Component\Form\FormInterface;

class AmazonPay implements PaymentMethodInterface
{

    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        ProductClassRepository $productClassRepository,
        PurchaseFlow $shoppingPurchaseFlow,
        AmazonOrderHelper $amazonOrderHelper,
        AmazonRequestService $amazonRequestService
    ) {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->productClassRepository = $productClassRepository;
        $this->purchaseFlow = $shoppingPurchaseFlow;
        $this->amazonOrderHelper = $amazonOrderHelper;
        $this->amazonRequestService = $amazonRequestService;
    }

    /**
     * {@inheritdoc}
     */
    public function verify()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->purchaseFlow->prepare($this->Order, new PurchaseContext());

        // 受注ステータスを決済処理中へ変更
        $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PENDING);
        $this->Order->setOrderStatus($OrderStatus);

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function checkout()
    {
        $response = $this->amazonOrderHelper->completeCheckout($this->Order, $this->amazonCheckoutSessionId);

        // Amazon受注情報を登録
        $this->amazonOrderHelper->setAmazonOrder($this->Order, $response->chargePermissionId, $response->chargeId);

        $this->purchaseFlow->commit($this->Order, new PurchaseContext());

        $result = new PaymentResult();
        $result->setSuccess(true);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormType(FormInterface $form)
    { }

    /**
     * {@inheritdoc}
     */
    public function setOrder(Order $Order)
    {
        $this->Order = $Order;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmazonCheckoutSessionId($amazonCheckoutSessionId)
    {
        $this->amazonCheckoutSessionId = $amazonCheckoutSessionId;
    }

}
