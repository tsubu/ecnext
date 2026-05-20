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

namespace Plugin\AmazonPayV2_42_Bundle\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\AbstractMasterEntity;

/**
 * AmazonStatus
 *
 * @ORM\Table(name="plg_amazon_pay_v2_status")
 * @ORM\Entity(repositoryClass="Plugin\AmazonPayV2_42_Bundle\Repository\Master\AmazonStatusRepository")
 */
class AmazonStatus extends AbstractMasterEntity
{
    /**
     * オーソリ
     */
    const AUTHORI = 1;

    /**
     * 売上
     */
    const CAPTURE = 2;

    /**
     * 取消
     */
    const CANCEL = 3;
}
