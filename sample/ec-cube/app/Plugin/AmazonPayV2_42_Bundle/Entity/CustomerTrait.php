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
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @var string
     * 
     * @ORM\Column(name="v2_amazon_user_id", type="string", length=255, nullable=true)
     */
    private $v2_amazon_user_id;

    /**
     * {@inheritdoc}
     */
    public function setV2AmazonUserId($v2_amazon_user_id)
    {
        $this->v2_amazon_user_id = $v2_amazon_user_id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getV2AmazonUserId()
    {
        return $this->v2_amazon_user_id;
    }
}
