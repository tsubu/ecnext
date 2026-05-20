<?php

/*
 * Amazon Pay V2 for EC-CUBE4
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
use Eccube\Entity\AbstractEntity;

/**
 * AmazonBanner
 *
 * @ORM\Table(name="plg_amazon_pay_v2_banner")
 * @ORM\Entity(repositoryClass="Plugin\AmazonPayV2_42_Bundle\Repository\AmazonBannerRepository")
 */
class AmazonBanner extends AbstractEntity
{
    /** page用定数：トップページ */
    public const PAGE_TOP = 1;

    /** page用定数：カート画面 */
    public const PAGE_CART = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * ページ種類
     *
     * @var int
     * 
     * @ORM\Column(name="page", type="integer", options={"unsigned":true})
     */
    private $page;

    /**
     * 画面へ表示するラベル
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * バナーのソースコード
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=1024, nullable=true)
     */
    private $code;

    /**
     * デフォルト選択値かどうか
     * ただのdefaultは予約語なのでis_をつける
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true, options={"default":false})
     */
    private $isDefault;

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get ページ種類
     *
     * @return  int
     */ 
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set ページ種類
     *
     * @param  int  $page  ページ種類
     *
     * @return  self
     */ 
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of code
     *
     * @return  string
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @param  string  $code
     *
     * @return  self
     */ 
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get ただのdefaultは予約語なのでis_をつける
     *
     * @return  boolean
     */ 
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set ただのdefaultは予約語なのでis_をつける
     *
     * @param  boolean  $isDefault  ただのdefaultは予約語なのでis_をつける
     *
     * @return  self
     */ 
    public function setIsDefault(bool $isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

}
