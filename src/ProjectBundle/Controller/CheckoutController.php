<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ProjectBundle\Entity\User;
use ProjectBundle\Entity\Product;
use ProjectBundle\Entity\DeliveryAddress;
use ProjectBundle\Entity\CustomerOrder;
use ProjectBundle\Entity\CustomerOrderItem;
use ProjectBundle\Entity\Sku;
use ProjectBundle\Entity\CustomerOrderDelivery;
use ProjectBundle\Entity\BankAccount;
use ProjectBundle\Entity\CustomerPaymentEpayment;
use ProjectBundle\Entity\Pages;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use ProjectBundle\Form\Type\Cart\ApplyCouponType;
use ProjectBundle\Form\Type\Cart\PlaceOrderType;
use ProjectBundle\Form\Type\Member\DeliveryAddressType;

use JMS\SecurityExtraBundle\Annotation\Secure;

class CheckoutController extends Controller
{
	/**
	* @Secure(roles="ROLE_CUSTOMER")
	*/
	public function indexAction(Request $request)
	{
		//return $this->render('ProjectBundle:'.$this->container->getParameter('view_checkout').':_email_order_confirm.html.twig', array());

		$user = $this->getUser();
		$util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');
		$session = $request->getSession();
		$em = $this->getDoctrine()->getManager();

		$delivery_address_form_data = new DeliveryAddress();
		if ($request->isMethod('get')){
            //set default data
            $delivery_address_form_data->setFirstName($user->getFirstName());
            $delivery_address_form_data->setLastName($user->getLastName());
            $delivery_address_form_data->setCompanyName($user->getCompanyName());
            $delivery_address_form_data->setPhone($user->getPhoneNumber());
        }

		$delivery_form = $this->createForm(DeliveryAddressType::class, $delivery_address_form_data, array('allow_extra_fields'=>true));
		$first_delivery_form = $this->createForm(DeliveryAddressType::class, $delivery_address_form_data, array('allow_extra_fields'=>true));
		$discount_setting_status = $product_util->validateDiscountSetting();

		//prepare session delivery information
		$session_cart = $product_util->getCartSession();
		$product_util->getDeliveryInformation($session_cart);

		$delivery_address = $em->getRepository(DeliveryAddress::class)->findAllDataByUserIdWithCountry($user)->getQuery()->getArrayResult();
		$default_billing_address = $em->getRepository(DeliveryAddress::class)->findDefaultBillingAddressById($user)->getQuery()->getArrayResult();
		$default_billing_address = ($default_billing_address) ? $default_billing_address[0] : null;
		$product_util->setSessionInitShippingAddress($delivery_address);
		$product_util->setSessionInitBillingAddress($default_billing_address);

		// $product_util->testRemoveDeliveryAddressData();

		//get current data after set session
		$arr_cart_data = $product_util->getArrProductCartData();
		$session_cart = $product_util->getCartSession();

		//check data in cart
		if(!$arr_cart_data['products']){
			//redirect to cart
			return $this->redirect($this->generateUrl('cart'));
		}

		$payment_options = $product_util->getPaymentGateway();
		$delivery_date = $product_util->getDeliveryDate();

		$coupon_form = $this->createForm(ApplyCouponType::class);
		$place_order_form = $this->createForm(PlaceOrderType::class, new CustomerOrder());

		$page_data = $this->getDoctrine()->getRepository(Pages::class)->getActiveDataByName('product_detail', $request->getLocale());

		return $this->render('ProjectBundle:'.$this->container->getParameter('view_checkout').':index.html.twig', array(
			'delivery_form'=>$delivery_form->createView(),
			'first_delivery_form'=>$first_delivery_form->createView(),
			'coupon_form'=>$coupon_form->createView(),
			'place_order_form'=>$place_order_form->createView(),
			'arr_cart_data'=>$arr_cart_data,
			'delivery_address'=>$delivery_address,
			'session_cart'=>$session_cart,
			'payment_options'=>$payment_options,
			'discount_setting_status'=>$discount_setting_status,
			'delivery_date' =>$delivery_date,
			'page_data'=>$page_data
		));
	}

	/**
	* @Secure(roles="ROLE_CUSTOMER")
	*/
	public function updateDeliveryAddressAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$product_util = $this->container->get('app.product');
		$mode = $request->query->get('mode');
		$delivery_address_id = $request->query->get('delivery_address_id');
		$user = $this->getUser();

		$arr_cart_data = array();
		$arr_result = array('status'=>false, 'error_msg'=>'', 'mode'=>$mode);

		//validate address
		$delivery_address = $em->getRepository(DeliveryAddress::class)->findDataById($delivery_address_id, $user)->getQuery()->getOneOrNullResult();
		if($delivery_address){
			$delivery_id = $delivery_address->getId();
			if($mode=='shipping_address'){
				$product_util->setSessionShippingAddress($delivery_id);
			}elseif($mode='billing_address'){
				$product_util->setSessionBillingAddress($delivery_id);
			}

			$arr_cart_data = $product_util->getArrProductCartData();
			$arr_result['status'] = true;
		}else{
			$arr_result['error_msg'] = 'Data not found';
		}

		$json_response = new JsonResponse();
		$json_response->setEncodingOptions(JSON_NUMERIC_CHECK);
	    $json_response->setData(array(
					'arr_cart_data'  => $arr_cart_data,
					'arr_result' => $arr_result,
					'time' => date('Y/m/d H:i:s')
	    ));
		return $json_response;
	}

	/**
	* @Secure(roles="ROLE_CUSTOMER")
	*/
	public function addDeliveryAddressAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$product_util = $this->container->get('app.product');
		$util = $this->container->get('utilities');

		$mode = $request->request->get('mode');
		$user = $this->getUser();

		$arr_cart_data = array();
		$arr_result = array('status'=>false, 'errors'=>false, 'mode'=>$mode);

		$delivery_address = new DeliveryAddress();
		$form = $this->createForm(DeliveryAddressType::class, $delivery_address, array('allow_extra_fields'=>true));
		$form->submit($request->request->all());
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $request->request->all();

			$delivery_address->setUser($user);
			$em->persist($delivery_address);
            $em->flush();

			$delivery_id = $delivery_address->getId();
			if($mode=='shipping_address'){
				$product_util->setSessionShippingAddress($delivery_id);
			}elseif($mode=='billing_address'){
				$product_util->setSessionBillingAddress($delivery_id);
			}elseif($mode=='first_delivery_address'){
				$product_util->setSessionShippingAddress($delivery_id);
				$product_util->setSessionBillingAddress($delivery_id);
			}

			$arr_cart_data = $product_util->getArrProductCartData();
			$arr_result['status'] = true;

		}else{
			$errors = $util->getFormErrorMessage($form);
            $arr_result['errors'] = $errors;
            $arr_result['status'] = false;
		}

		$arr_delivery_address = $em->getRepository(DeliveryAddress::class)->findAllDataByUserIdWithCountry($user)->getQuery()->getArrayResult();
		$json_response = new JsonResponse();
		$json_response->setEncodingOptions(JSON_NUMERIC_CHECK);
	    $json_response->setData(array(
					'arr_cart_data'  => $arr_cart_data,
					'arr_result' => $arr_result,
					'arr_delivery_address' => $arr_delivery_address,
					'time' => date('Y/m/d H:i:s')
	    ));
		return $json_response;
	}

	/**
	* @Secure(roles="ROLE_CUSTOMER")
	*/
	public function setPaymentOptionAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$product_util = $this->container->get('app.product');
		$util = $this->container->get('utilities');

		$code = $request->query->get('code');
		$user = $this->getUser();

		$arr_cart_data = array();
		$arr_result = array('status'=>false, 'errors'=>false);

		$payment_options = $product_util->getPaymentGateway();
		if (in_array($code, $payment_options)) {

			$product_util->setSessionPaymentOption($code);
			$arr_result['status'] = true;

			$arr_cart_data = $product_util->getArrProductCartData();
		}

		$json_response = new JsonResponse();
		$json_response->setEncodingOptions(JSON_NUMERIC_CHECK);
	    $json_response->setData(array(
					'code' => $code,
					'arr_cart_data' => $arr_cart_data,
					'arr_result' => $arr_result,
					'time' => date('Y/m/d H:i:s')
	    ));
		return $json_response;
	}

	/**
	* @Secure(roles="ROLE_CUSTOMER")
	*/
	public function placeOrderAction(Request $request)
	{
		$place_order_form = $this->createForm(PlaceOrderType::class);
		$em = $this->getDoctrine()->getManager();
		$product_util = $this->container->get('app.product');
		$util = $this->container->get('utilities');
		$user = $this->getUser();

		$discount_setting_status = $product_util->validateDiscountSetting();

		$data = new CustomerOrder();
		$form = $this->createForm(PlaceOrderType::class, $data, array('allow_extra_fields'=>true));
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			// valid
			$arr_cart_data = $product_util->getArrProductCartData();
			$session_cart = $product_util->getCartSession();

			//check data in cart
			if(!$arr_cart_data['products']){
				//redirect to cart
				return $this->redirect($this->generateUrl('cart'));
			}

			// validate inventory in cart
			$arr_validate_inventory_result = $product_util->validateInventoryInCart($arr_cart_data);
			if(!$arr_validate_inventory_result['status']){
				//inventory warning redirect to checkout
				return $this->redirect($this->generateUrl('checkout'));
			}

			$payment_bank_transfer_code = $this->container->getParameter('payment_bank_transfer_code');
			$payment_credit_code = $this->container->getParameter('payment_credit_code');
			$payment_cash_on_deliveryr_code = $this->container->getParameter('payment_cash_on_deliveryr_code');

			$payment_options = $this->container->getParameter('payment_options');

			//generate order_number
			$order_number = $product_util->generateOrderNumber($user->getId());
			$order_date = new \DateTime('now');
			$arr_cart_data_summary = $arr_cart_data['summary'];
			$arr_products = $arr_cart_data['products'];
			$payment_option = $arr_cart_data_summary['payment_option'];
			$payment_option_title = $payment_options[$payment_option]['title'];

			//get delivery date
			$delivery_date = $product_util->getDeliveryDate();
			if($delivery_date){
				$data->setShipDate($delivery_date);
			}

			//save customerOrder
			$data->setUser($user);
			$data->setOrderNumber($order_number);
			$data->setOrderDate($order_date);
			$data->setPaymentOption($payment_option);
			$data->setPaymentOptionTitle($payment_option_title);
			$data->setItemCount($arr_cart_data_summary['item_count']);
			$data->setShippingCost($arr_cart_data_summary['shipping_cost']);
			$data->setSubTotal($arr_cart_data_summary['sub_total']);
			$data->setTotalPrice($arr_cart_data_summary['total']);
			if($arr_cart_data_summary['discount_code']){
				$data->setDiscountCode($arr_cart_data_summary['discount_code']);
				$data->setDiscountAmount($arr_cart_data_summary['discount_amount']);
			}
			$em->persist($data);
			$em->flush();

			//save customerOrderItem
			foreach ($arr_products as  $arr_product) {
				$product_util->saveCustomerOrderItemAndUpdateInventory($data, $arr_product);
			}
			//save customerOrderDelivery shipping
			$arr_delivery_information = $arr_cart_data['delivery_information'];
			if(isset($arr_delivery_information['shipping_address'])) {
				$arr_shipping_address = $arr_delivery_information['shipping_address'];
				$product_util->saveCustomerOrderDelivery($data, 1, $arr_shipping_address);
			}
			//save customerOrderDelivery billing
			if(isset($arr_delivery_information['billing_address'])) {
				$arr_billing_address = $arr_delivery_information['billing_address'];
				$product_util->saveCustomerOrderDelivery($data, 2, $arr_billing_address);
			}

			//save customerOrdersDiscounts
			if($arr_cart_data_summary['discount_code']){
				$product_util->saveCustomerOrdersDiscounts($data, $arr_cart_data_summary['discount_code']);
			}

			//save payment_option
			switch ($payment_option) {
				case $payment_credit_code:
					//save customerPaymentEpayment credit
					$product_util->saveCustomerPaymentEpayment($data, $arr_cart_data_summary);
					break;
				case $payment_bank_transfer_code:
				case $payment_cash_on_deliveryr_code:
					//BT and COD
					break;
			}

			// create session for order success
			$product_util->setSessionOrderSuccess($data);

			// // debug sendmail template
			// $bankAccount = $em->getRepository(BankAccount::Class)->findAllActiveData()->getQuery()->getResult();
			// $epayment = $this->container->get('doctrine')->getRepository(CustomerPaymentEpayment::class)->findOneByCustomerOrder($data);
			// $client_protocal = $util->getClientProtocal();
			// return $this->render(
            //     'ProjectBundle:'.$this->container->getParameter('view_checkout').':_email_order_confirm.html.twig',
            //     array('arr_cart_data'=>$arr_cart_data, 'order'=>$data, 'epayment'=>$epayment, 'user'=>$user, 'client_protocal'=>$client_protocal, 'bankAccount'=>$bankAccount)
            // );

			//send email order_confirm
			$util->sendMailOrderConfirm($arr_cart_data, $data, $user, 'admin');
			$util->sendMailOrderConfirm($arr_cart_data, $data, $user, 'customer');

			return $this->redirect($this->generateUrl('checkout_success'));

		}else{
			// invalid
			// redirect to cartindex
			return $this->redirect($this->generateUrl('checkout'));
		}
	}

	public function checkout_successAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');

		$session_cart_success = $product_util->getSessionOrderSuccess();
		$order_number = $session_cart_success['order_number'];

		$em = $this->getDoctrine()->getManager();
        $customerOrder = $em->getRepository(CustomerOrder::Class)->findCustomerOrderHasItemByOrderNumber($order_number)->getQuery()->getResult();
		$bankAccount = $em->getRepository(BankAccount::Class)->findAllActiveData()->getQuery()->getResult();



		return $this->render('ProjectBundle:'.$this->container->getParameter('view_checkout').':checkout_success.html.twig', array(
			'customerOrder'=>$customerOrder, 'bankAccount'=>$bankAccount
		));

	}

}
