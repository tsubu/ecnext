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

namespace Plugin\AmazonPayV2_42_Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AmazonTrading
 * 
 * @ORM\Table(name="plg_amazon_pay_v2_trading")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Plugin\AmazonPayV2_42_Bundle\Repository\AmazonTrading")
 */
class AmazonTrading
{

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Eccube\Entity\Order
     * 
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Order", inversedBy="AmazonTrading")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $Order;

    /**
     * @var string
     * 
     * @ORM\Column(name="charge_permission_id", type="string", length=255)
     */
    private $charge_permission_id;

    /**
     * @var string
     *
     * @ORM\Column(name="charge_id", type="string", length=255)
     */
    private $charge_id;

    /**
     * @var integer
     * 
     * @ORM\Column(name="authori_amount", type="integer")
     */
    private $authori_amount;

    /**
     * @var integer
     * 
     * @ORM\Column(name="capture_amount", type="integer")
     */
    private $capture_amount;

    /**
     * @var integer
     * 
     * @ORM\Column(name="refund_count", type="integer")
     */
    private $refund_count;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_id", type="string", length=255, nullable=true)
     */
    private $refund_id;

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(\Eccube\Entity\Order $Order)
    {
        $this->Order = $Order;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->Order;
    }

    /**
     * {@inheritdoc}
     */
    public function setChargePermissionId($chargePermissionId)
    {
        $this->charge_permission_id = $chargePermissionId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChargePermissionId()
    {
        return $this->charge_permission_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setChargeId($chargeId)
    {
        $this->charge_id = $chargeId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChargeId()
    {
        return $this->charge_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthoriAmount($authoriAmount)
    {
        $this->authori_amount = $authoriAmount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthoriAmount()
    {
        return $this->authori_amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setCaptureAmount($captureAmount)
    {
        $this->capture_amount = $captureAmount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCaptureAmount()
    {
        return $this->capture_amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setRefundCount($refundCount)
    {
        $this->refund_count = $refundCount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefundCount()
    {
        return $this->refund_count;
    }

    /**
     * {@inheritdoc}
     */
    public function setRefundId($refundId)
    {
        $this->refund_id = $refundId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefundId()
    {
        return $this->refund_id;
    }
}
