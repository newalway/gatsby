<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

use ProjectBundle\Entity\Promotion;
use ProjectBundle\Entity\PromotionDownloadCounter;

use ProjectBundle\Form\Type\AdminPromotionType;

class AdminPromotionController extends Controller
{
	const ROUTER_INDEX = 'admin_promotion';
	const ROUTER_ADD = self::ROUTER_INDEX.'_new';
	const ROUTER_EDIT = self::ROUTER_INDEX.'_edit';
  	const ROUTER_PREFIX = 'promotion';
	const ROUTER_CONTROLLER = 'AdminPromotion';

	protected function getQuerySearchData($arr_query_data)
	{
		$repository = $this->getDoctrine()->getRepository(Promotion::class);
    	$query = $repository->findAllData($arr_query_data);
		return $query;
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function indexAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$session = $request->getSession();
	    try {
			$acctoken = $util->getAccessToken();
	    } catch(\Exception $e) {
			return $this->redirectToRoute('admin_user_generate_token');
	    }
    	$arr_query_data = $util->prepare_query_data($request);
		$query = $this->getQuerySearchData($arr_query_data);
		$paginated = $util->setPaginatedOnPagerfanta($query);

	    $util->setBackToUrl();
	    return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':index.html.twig', array(
			'paginated' =>$paginated
		));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function newAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$util->setCkAuthorized();
		$acctoken = $util->getAccessToken();

		$file_data = $util->getFilesInfo();
		$data = new Promotion();
		$date = new \DateTime();
		$data->setPublicDate($date);
		$form = $this->createForm(AdminPromotionType::class, $data, array('allow_extra_fields'=>true));
    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':new.html.twig', array(
			'form'=>$form->createView(),
			'file_data'=>$file_data,
			'acctoken'=>$acctoken
		));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function createAction(Request $request)
	{
	    $util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');
		$acctoken = $util->getAccessToken();
	    $em = $this->getDoctrine()->getManager();

		$data = new Promotion();
	    $form = $this->createForm(AdminPromotionType::class, $data, array('allow_extra_fields'=>true));
	    $form->handleRequest($request);

		//get pdf file info
		$file_name = $data->getFilepath();
		$file_data = $util->getFilesInfo($file_name);

    	if ($form->isSubmitted() && $form->isValid()) {

			if($file_name){
				$data->setFilename($file_data['file_name']);
				$data->setFilesize($file_data['file_size']);
			}

			$em->persist($data);
	    	$em->flush();

			//save specific_products
			$product_util->savePromotionProducts($data);

			$util->setCreateNotice();
			$redirect_uri = $util->getRedirectUriSaveBtn($form, $data, self::ROUTER_INDEX, self::ROUTER_ADD, self::ROUTER_EDIT);
			return $this->redirect($redirect_uri);
    	}
    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':new.html.twig', array(
			'form'=>$form->createView(),
			'file_data'=>$file_data,
			'acctoken'=>$acctoken
		));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function editAction(Request $request)
	{
    	$util = $this->container->get('utilities');
		$util->setCkAuthorized();
		$acctoken = $util->getAccessToken();

		$data = $this->getDoctrine()->getRepository(Promotion::class)->find($request->get('id'));
		if (!$data) { throw $this->createNotFoundException('No data found'); }

		//get pdf file info
		$file_name = $data->getFilepath();
		$file_data = $util->getFilesInfo($file_name);

		$form = $this->createForm(AdminPromotionType::class, $data, array('allow_extra_fields'=>true));
    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array(
			'form'=>$form->createview(),
			'file_data'=>$file_data,
			'acctoken'=>$acctoken
		));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function updateAction(Request $request)
	{
	    $util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');
		$acctoken = $util->getAccessToken();
	    $em = $this->getDoctrine()->getManager();

		$data = $em->getRepository(Promotion::class)->find($request->get('id'));
		if (!$data) { throw $this->createNotFoundException('No data found'); }

	    $form = $this->createForm(AdminPromotionType::class, $data, array('allow_extra_fields'=>true));
	    $form->handleRequest($request);

		//get pdf file info
		$file_name = $data->getFilepath();
		$file_data = $util->getFilesInfo($file_name);
    	if ($form->isSubmitted() && $form->isValid()) {

			if($request->get('removefileimage')==1){
				$data->removeImage();
			}
			if($request->get('removefilepath')==1){
				$data->removeFilepath();
			}else{
				if($file_name){
					$data->setFilename($file_data['file_name']);
					$data->setFilesize($file_data['file_size']);
				}
			}

			$em->flush();

			//save specific_products
			$product_util->savePromotionProducts($data);

			$util->setUpdateNotice();
			$redirect_uri = $util->getRedirectUriSaveBtn($form, $data, self::ROUTER_INDEX, self::ROUTER_ADD, self::ROUTER_EDIT);
			return $this->redirect($redirect_uri);
    	}
    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array(
			'form'=>$form->createview(),
			'file_data'=>$file_data,
			'acctoken'=>$acctoken
		));
	}


	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function deleteAction(Request $request)
	{
    	$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();

    	$data = $em->getRepository(Promotion::class)->find($request->get('id'));
		if (!$data) { throw $this->createNotFoundException('No data found'); }

	    $em->remove($data);
	    $em->flush();

		$util->setRemoveNotice();
    	return $this->redirect($util->getBackToUrl(self::ROUTER_INDEX));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function group_deleteAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();

		$data_ids = $request->get('data_ids');

		if ($data_ids) {
			$flg_del = false;
			foreach ($data_ids as $data_id) {
				$data = $em->getRepository(Promotion::class)->find($data_id);
				if ($data) {
					try {
						$em->remove($data);
				    $em->flush();
						$flg_del = true;
					} catch(\Doctrine\DBAL\DBALException $e) {
						$util->setCustomeFlashMessage('warning', $msg="Can't delete ".$data->getTitle());
					}
				}
			}
			if ($flg_del) {
				$util->setRemoveNotice();
			}
		}
		return $this->redirect($util->getBackToUrl(self::ROUTER_INDEX));
	}

	/**
	* @Secure(roles="ROLE_ADMIN")
	*/
	public function group_enableAction(Request $request)
	{
    	$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();
		$data_ids = $request->get('data_ids');
		if ($data_ids) {
			$flg_pub = false;
			foreach ($data_ids as $data_id) {
				$data = $em->getRepository(Promotion::class)->find($data_id);
				if ($data) {
					$data->setStatus(1);
					$flg_pub = true;
				}
			}
			try {
				$em->flush();
			} catch(\Exception $e) {
				$util->setCustomeFlashMessage('warning', $msg="Can't enable ");
			}
			if($flg_pub){
				$util->setCustomeFlashMessage('notice', $msg="Published ");
			}
		}
    	return $this->redirect($util->getBackToUrl(self::ROUTER_INDEX));
	}

	/**
	* @Secure(roles="ROLE_ADMIN")
	*/
	public function group_disableAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();
		$data_ids = $request->get('data_ids');
		if ($data_ids) {
			$flg_pub = false;
			foreach ($data_ids as $data_id) {
				$data = $em->getRepository(Promotion::class)->find($data_id);
				if ($data) {
					$data->setStatus(0);
					$flg_pub = true;
				}
			}
			try {
				$em->flush();
			} catch(\Exception $e) {
				$util->setCustomeFlashMessage('warning', $msg="Can't disable ");
			}
			if ($flg_pub) {
				$util->setCustomeFlashMessage('notice', $msg="Unpublished ");
			}
		}
	    return $this->redirect($util->getBackToUrl(self::ROUTER_INDEX));
	}

	/**
	* @Secure(roles="ROLE_EDITOR")
	*/
	public function downloadAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();

		$id = $request->get('id');
		$data = $em->getRepository(Promotion::class)->find($request->get('id'));
		if (!$data) { throw $this->createNotFoundException('No data found'); }

		$ip_address = $request->getClientIp();

		$counter = $data->getDownloadCount()+1;
		$data->setDownloadCount($counter);

		$current_browser = $util->getBrowserCap();
		$dc = new PromotionDownloadCounter();
	    $dc->setIpAddress($ip_address);
	    $dc->setBrowserName($current_browser->browser_name);
	    $dc->setPlatform($current_browser->Platform);
		$dc->setBrowser($current_browser->Browser);
		$dc->setVersion($current_browser->Version);
		$dc->setPromotion($data);

		$record = $util->getGeoLite2City();
		if(!empty($record)){
			$dc->setCountryCode($record->country->isoCode);
			$dc->setCountryName($record->country->name);
			$dc->setCityName($record->city->name);
			$dc->setPostalCode($record->postal->code);
			$dc->setLocationLatitude($record->location->latitude);
			$dc->setLocationLongitude($record->location->longitude);
		}

	    $em->persist($dc);
	    $em->flush();

		$file_name = urldecode($data->getFilepath());
		$attach_file = $this->container->getParameter('web_path').$file_name;
		try{
			$response = new Response();
			$response->headers->set('Content-type', mime_content_type($attach_file));
			$response->setContent(file_get_contents($attach_file));
			$response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file_name ));
			return $response;
		}
		catch(\Exception $e){
			throw $this->createNotFoundException('This file is already deleted');
		}
	}
}
