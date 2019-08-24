<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ProjectBundle\Entity\User;
use ProjectBundle\Entity\CustomerOrder;
use ProjectBundle\Entity\DeliveryAddress;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

class AdminMemberController extends Controller
{
	const ROUTER_CONTROLLER = 'AdminMember';
	const ROUTER_INDEX = 'admin_member';


	protected function prepare_query_data($request){
		$arr_data = $request->query->get('search_member');
		$arr_query_data['q'] = trim($arr_data['q']);
		return $arr_query_data;
	}

	protected function getQuerySearchUser($arr_query_data)
	{
		$repository = $this->getDoctrine()->getRepository(User::class);
    	$query = $repository->getAllMemberByData($arr_query_data);
		return $query;
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function indexAction(Request $request)
	{
		$util = $this->container->get('utilities');
		try {
			$acctoken = $util->getAccessToken();
		} catch(\Exception $e) {
			return $this->redirectToRoute('admin_user_generate_token');
		}

		$arr_query_data = $this->prepare_query_data($request);
		$query = $this->getQuerySearchUser($arr_query_data);
		$paginated = $util->setPaginatedOnPagerfanta($query);
		$util->setBackToUrl();
		return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':index.html.twig', array(
			'paginated' =>$paginated,
			'acctoken' => $acctoken,
		));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function viewAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$util->setCkAuthorized();
		$user_id = $request->get('id');
		$em = $this->getDoctrine()->getManager();
		$repository = $this->getDoctrine()->getRepository(User::class);
		$member = $repository->getMemberById($user_id)->getQuery()->getSingleResult();

		$order = $em->getRepository(CustomerOrder::Class)->findCustomerOrderHasItemByUser($member)->getQuery();

		$default_shipping_address = $em->getRepository(DeliveryAddress::Class)->findDefaultShippingAddressById($member)->getQuery()->getOneOrNullResult();
		$default_billing_address = $em->getRepository(DeliveryAddress::Class)->findDefaultBillingAddressById($member)->getQuery()->getOneOrNullResult();

		$paginated = $util->setPaginatedOnPagerfanta($order);

		return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':view.html.twig', array(
			'member' =>$member,
			'default_shipping_address' => $default_shipping_address,
			'default_billing_address' => $default_billing_address,
			'paginated' => $paginated
		));
	}

	/**
  * @Secure(roles="ROLE_EDITOR")
  */
  public function group_export_excelAction(request $request)
  {
		$util = $this->container->get('utilities');
		$arr_query_data = $this->prepare_query_data($request);
		$em = $this->getDoctrine()->getManager();
		$members = $em->getRepository(User::class)->getAllMemberByData($arr_query_data)->getQuery()->getResult();
		// $members = $this->getAllMemberByData($arr_query_data)->getQuery()->getResult();

		//$members = $this->getQuerySearchUser($arr_query_data)->find();

		$export_excel = $this->container->get('exportexcel');
		$export_excel->getExcelObjectMember();
		$export_excel->exportMemberData($members);


		$name_post_fix = date('dMy');
		$file_name = 'Member '.$name_post_fix.'.xls';
		$export_excel->saveOutputExcel($file_name);
		$response =  $export_excel->streamOutputExcel($file_name);

		//headers
		// $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		// $response->headers->set('Content-Disposition', 'attachment;filename="'.$file_name.'"');
		// $response->headers->set('Pragma', 'public');
		// $response->headers->set('Cache-Control', 'maxage=1');
		return $response;
	}

}
