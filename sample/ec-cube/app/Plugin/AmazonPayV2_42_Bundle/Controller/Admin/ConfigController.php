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

namespace Plugin\AmazonPayV2_42_Bundle\Controller\Admin;

use Eccube\Controller\AbstractController;
use Plugin\AmazonPayV2_42_Bundle\Form\Type\Admin\ConfigType;
use Plugin\AmazonPayV2_42_Bundle\Repository\ConfigRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConfigController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     * 
     * @param ValidatorInterface $validator
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        ValidatorInterface $validator,
        ConfigRepository $configRepository
    ) {
        $this->validator = $validator;
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/amazon_pay_v2_42/config", name="amazon_pay_v2.42.bundle_admin_config")
     * @Template("@AmazonPayV2_42_Bundle/admin/config.twig")
     */
    public function index(Request $request)
    {
        $Config = $this->configRepository->get(true);
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            if ($Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['prod']) {
                // 本番環境切り替え時
                $prod_key = $Config->getProdKey();
                $errors = $this->validator->validate(
                    $prod_key,
                    [
                        new Assert\NotBlank(),
                    ]
                );
                if ($errors->count() != 0) {
                    foreach ($errors as $error) {
                        $messages[] = $error->getMessage();
                    }
                    $form['prod_key']->addError(new FormError($messages[0]));
                } else if (!password_verify($prod_key, '$2y$10$m3aYrihBaIKarlrmI39tGORK4fFBC7cWoSLFy6jkMpT7IduYVsVtO')) {
                    $form['prod_key']->addError(new FormError('本番切り替えキーは有効なキーではありません。'));
                }
            }

            if ($Config->getAmazonAccountMode() == $this->eccubeConfig['amazon_pay_v2']['account_mode']['owned']) {
                $privateKeyPath = $this->getParameter('kernel.project_dir') . '/' . $Config->getPrivateKeyPath();
                if (mb_substr($Config->getPrivateKeyPath(), 0, 1) == '/') {
                    $form['private_key_path']->addError(new FormError('プライベートキーパスの先頭に"/"は利用できません'));
                } elseif (!is_file($privateKeyPath) || file_exists($privateKeyPath) === false) {
                    $form['private_key_path']->addError(new FormError('プライベートキーファイルが見つかりません。'));
                }
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($Config);
                $this->entityManager->flush($Config);

                $this->addSuccess('amazon_pay_v2.admin.save.success', 'admin');
                return $this->redirectToRoute('amazon_pay_v2.42.bundle_admin_config');
            }
        }

        $testAccount = $this->eccubeConfig['amazon_pay_v2']['test_account'];

        return [
            'form' => $form->createView(),
            'testAccount' => $testAccount,
        ];
    }
}
