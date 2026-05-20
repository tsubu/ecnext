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

use Eccube\Annotation\EntityExtension;
use Doctrine\ORM\Mapping as ORM;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    public function getAmazonPayV2SumAuthoriAmount()
    {
        $sumAuthoriAmount = 0;
        foreach ($this->AmazonPayV2AmazonTradings as $AmazonTrading) {
            $sumAuthoriAmount += $AmazonTrading->getAuthoriAmount();
        }

        return $sumAuthoriAmount;
    }

    public function getAmazonPayV2SumCaptureAmount()
    {
        $sumCaptureAmount = 0;
        foreach ($this->AmazonPayV2AmazonTradings as $AmazonTrading) {
            $sumCaptureAmount += $AmazonTrading->getCaptureAmount();
        }

        return $sumCaptureAmount;
    }

    /**
     * @var string
     * 
     * @ORM\Column(name="amazonpay_v2_charge_permission_id", type="string", length=255, nullable=true)
     */
    private $amazonpay_v2_charge_permission_id;

    /**
     * @var integer
     * 
     * @ORM\Column(name="amazonpay_v2_billable_amount", type="integer", nullable=true)
     */
    private $amazonpay_v2_billable_amount;

    /**
     * @var AmazonStatus
     * @ORM\ManyToOne(targetEntity="Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="amazonpay_v2_amazon_status_id", referencedColumnName="id")
     * })
     */
    private $AmazonPayV2AmazonStatus;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\OneToMany(targetEntity="Plugin\AmazonPayV2_42_Bundle\Entity\AmazonTrading", mappedBy="Order", cascade={"persist", "remove"})
     */
    private $AmazonPayV2AmazonTradings;

    /**
     * @var string
     * @ORM\Column(name="amazonpay_v2_session_temp", type="text", length=36777215, nullable=true)
     */
    private $amazonpay_v2_session_temp;

    /**
     * {@inheritdoc}
     */
    public function setAmazonPayV2ChargePermissionId($AmazonPayV2ChargePermissionId)
    {
        $this->amazonpay_v2_charge_permission_id = $AmazonPayV2ChargePermissionId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmazonPayV2ChargePermissionId()
    {
        return $this->amazonpay_v2_charge_permission_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmazonPayV2BillableAmount($amazonpayV2BillableAmount)
    {
        $this->amazonpay_v2_billable_amount = $amazonpayV2BillableAmount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmazonPayV2BillableAmount()
    {
        return $this->amazonpay_v2_billable_amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmazonPayV2AmazonStatus(\Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus $AmazonPayV2AmazonStatus)
    {
        $this->AmazonPayV2AmazonStatus = $AmazonPayV2AmazonStatus;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmazonPayV2AmazonStatus()
    {
        return $this->AmazonPayV2AmazonStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function addAmazonPayV2AmazonTrading(\Plugin\AmazonPayV2_42_Bundle\Entity\AmazonTrading $AmazonPayV2AmazonTrading)
    {
        $this->AmazonPayV2AmazonTradings[] = $AmazonPayV2AmazonTrading;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAmazonPayV2AmazonTradings()
    {
        $this->AmazonPayV2AmazonTradings->clear();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmazonPayV2AmazonTradings()
    {
        return $this->AmazonPayV2AmazonTradings;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmazonPayV2SessionTemp($AmazonPayV2SessionTemp)
    {
        $this->amazonpay_v2_session_temp = $AmazonPayV2SessionTemp;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmazonPayV2SessionTemp()
    {
        return $this->amazonpay_v2_session_temp;
    }
}
