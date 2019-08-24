<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ProjectBundle\Entity\Pages;

use ProjectBundle\Form\Type\AdminPagesType;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

class AdminPageShippingDeliveryController extends Controller
{
    const ROUTER_INDEX = 'admin_page_shipping_delivery';
	const ROUTER_CONTROLLER = 'AdminPageShippingDelivery';

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function indexAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$util->setCkAuthorized();

		$em = $this->getDoctrine()->getManager();
		$repository = $this->getDoctrine()->getRepository(Pages::class);
		$data = $em->getRepository(Pages::class)->findOneBy(['name'=>'shipping_delivery']);
		if (!$data) {
			//set default data, with translator
			$data = new Pages();
			$data->translate('en')->setTitle('Shipping & Delivery');
			$data->translate('th')->setTitle('การจัดส่งสินค้า');
            $data->setName('shipping_delivery');
			$em->persist($data);
			$data->mergeNewTranslations();
			$em->flush();
		}

        $util->setBackToUrl();
		$form = $this->createForm(AdminPagesType::class, $data);
		return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array(
			'data'=>$data,
			'form'=>$form->createview()
		));
	}

    /**
    * @Secure(roles="ROLE_EDITOR")
    */
    public function updateAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository(Pages::class)->find($request->get('id'));
        if (!$data) { throw $this->createNotFoundException('No data found'); }

        $form = $this->createForm(AdminPagesType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $util->setUpdateNotice();
            $redirect_uri = $util->getBackToUrl(self::ROUTER_INDEX);
            return $this->redirect($redirect_uri);
        }
        return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array('form'=>$form->createview()));
    }
}
