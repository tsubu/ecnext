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

namespace Plugin\AmazonPayV2_42_Bundle\Form\Type\Admin;

use Doctrine\ORM\EntityRepository;
use Eccube\Form\Type\Master\OrderStatusType;
use Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('OrderStatuses', OrderStatusType::class, [
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('AmazonStatuses', EntityType::class, [
                'class' => AmazonStatus::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.id', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ]);
    }
}
