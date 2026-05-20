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

namespace Plugin\AmazonPayV2_42_Bundle\Repository;

use Doctrine\ORM\EntityRepository;

class AmazonOrderRepository extends EntityRepository
{
    /** @var array */
    public $config;

    protected $app;

    public function setApplication($app)
    {
        $this->app = $app;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     *
     * @param  Order       $Orders
     * @return QueryBuilder
     */
    public function getAmazonOrderByOrderDataForAdmin($Orders)
    {
        $AmazonOrders = [];
        foreach ($Orders as $Order) {
            $AmazonOrder = $this->findby(['Order' => $Order]);
            if (!empty($AmazonOrder)) {
                $AmazonOrders[] = $AmazonOrder[0];
            }
        }

        return $AmazonOrders;
    }
}
