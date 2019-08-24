<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityManagerInterface;
use ProjectBundle\Entity\User;
use ProjectBundle\Entity\DeliveryAddress;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use ProjectBundle\Form\Type\Member\DeliveryAddressType;

use JMS\SecurityExtraBundle\Annotation\Secure;

class DeliveryAddressController extends Controller
{
    /**
    * @Secure(roles="ROLE_CUSTOMER, ROLE_CLIENT")
    */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $user_id = $user->getId();
        $em = $this->getDoctrine();
        $delivery_address = $em->getRepository(DeliveryAddress::class)->findAllDataById($user)->getQuery()->getResult();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_delivery_address').':index.html.twig',array(
            'delivery_address'=>$delivery_address
        ));
    }

    /**
    * @Secure(roles="ROLE_CUSTOMER, ROLE_CLIENT")
    */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $delivery_address = new DeliveryAddress();
        $user = $this->getUser();

        if ($request->isMethod('get')) {
            //set default data
            $delivery_address->setFirstName($user->getFirstName());
            $delivery_address->setLastName($user->getLastName());
            $delivery_address->setCompanyName($user->getCompanyName());
            $delivery_address->setPhone($user->getPhoneNumber());
        }

        $form = $this->createForm(DeliveryAddressType::class,$delivery_address, array('allow_extra_fields'=>true));
        $form->handleRequest($request);
        $session = $request->getSession();


        if ($form->isSubmitted() && $form->isValid()) {

            $delivery_address_data = $request->request->get('delivery_address');

            if ((array_key_exists('default_shipping', $delivery_address_data)) && (array_key_exists('default_tax', $delivery_address_data))) {
                $em->getRepository(DeliveryAddress::class)->setClearDefaultShippingValue($user);
                $em->getRepository(DeliveryAddress::class)->setClearDefaultTaxValue($user);
            }elseif (array_key_exists('default_tax', $delivery_address_data)) {
                $em->getRepository(DeliveryAddress::class)->setClearDefaultTaxValue($user);
            }elseif (array_key_exists('default_shipping', $delivery_address_data)) {
                $em->getRepository(DeliveryAddress::class)->setClearDefaultTaxValue($user);
            }

            $delivery_address->setUser($user);
            // $delivery_address->setPosition(0);
            // $delivery_address->setDeleted(0);
            $em->persist($delivery_address);

            $em->flush();
            // //set notice
            //   $this->get('session')->getFlashBag()->add('notice', 'Your changes were saved');
            return $this->redirect($this->generateUrl('address'));
        }

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_delivery_address').':create.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Secure(roles="ROLE_CUSTOMER, ROLE_CLIENT")
    */
    public function editAction(DeliveryAddress $delivery_address,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data_shipping = $delivery_address->getDefaultShipping();
        $data_tax = $delivery_address->getDefaultTax();

        $form = $this->createForm(DeliveryAddressType::class, $delivery_address, array('allow_extra_fields'=>true));
        $form->handleRequest($request);
        $session = $request->getSession();
        $user = $this->getUser();
        $delivery_address_data = $request->request->get('delivery_address');


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $delivery_address_data = $request->request->get('delivery_address');

            if (array_key_exists('default_shipping', $delivery_address_data) && (!$data_shipping) ) {
                $em->getRepository(DeliveryAddress::class)->setClearDefaultShippingValue($user);
            }

            if (array_key_exists('default_tax', $delivery_address_data) && (!$data_tax) ) {
                $em->getRepository(DeliveryAddress::class)->setClearDefaultTaxValue($user);
            }

            $em->flush();

            // //set notice
            //   $this->get('session')->getFlashBag()->add('notice', 'Your changes were saved');
            return $this->redirect($this->generateUrl('address'));
        }

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_delivery_address').':edit.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Secure(roles="ROLE_CUSTOMER, ROLE_CLIENT")
    */
    public function deleteAction(DeliveryAddress $delivery_address,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $user = $this->getUser();

        $em->remove($delivery_address);
        $em->flush();
        // //set notice
        // $this->get('session')->getFlashBag()->add('notice', 'Your changes were saved');
        return $this->redirect($this->generateUrl('address'));
    }
}
