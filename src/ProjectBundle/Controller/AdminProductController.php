<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ProjectBundle\Entity\Product;
use ProjectBundle\Entity\Sku;
use ProjectBundle\Entity\Hashtag;
use ProjectBundle\Entity\Variant;
use ProjectBundle\Entity\VariantOption;

use ProjectBundle\Form\Type\AdminProductType;
use ProjectBundle\Form\Type\AdminSearchProductType;
use ProjectBundle\Form\Type\AdminProductTechNiqueCollationType;


use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

class AdminProductController extends Controller
{
	const ROUTER_INDEX = 'admin_product';
	const ROUTER_ADD = self::ROUTER_INDEX.'_new';
	const ROUTER_EDIT = self::ROUTER_INDEX.'_edit';
  	const ROUTER_PREFIX = 'product';
	const ROUTER_CONTROLLER = 'AdminProduct';

	protected function getQuerySearchData($arr_query_data)
	{
		$repository = $this->getDoctrine()->getRepository(Product::class);
    	$query = $repository->findAllDataJoined($arr_query_data);
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
    	// $arr_query_data = $util->prepare_query_data($request);


		$form_search = $this->createForm(AdminSearchProductType::class, null, array('allow_extra_fields'=>true));
		$form_search->handleRequest($request);


		$arr_query_data = $request->query->get('admin_search_product');

		$query = $this->getQuerySearchData($arr_query_data);

		$paginated = $util->setPaginatedOnPagerfanta($query);

	    $util->setBackToUrl();
	    return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':index.html.twig', array(
			'paginated' =>$paginated,
			'form_search' => $form_search->createView(),
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

		$data = new Product();
		$current_date = new \DateTime();
		$data->setPublishDate($current_date); //set the default value
		$form = $this->createForm(AdminProductType::class, $data, array('allow_extra_fields'=>true));


    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':new.html.twig', array(
			'form'=>$form->createView(),
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

		$data = new Product();
	    $form = $this->createForm(AdminProductType::class, $data, array('allow_extra_fields'=>true));

		// dump($form->getData());
		// exit;
		$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
			//create product
			// dump($data);
			// exit;
			$form_data = $form->getData();
			$video = $form_data->getVideo();
			$video_type = $util->videoType($video);
			if($video_type != 'unknown'){
				if($video_type == 'youtube'){
					// Youtube Json format
					$json_url = $util->youtube_json_url($video);
					$youtube_video_id = $util->youtube_video_id($video);
					$oembed_json = json_decode($util->curl_get($json_url));

					if($oembed_json){
						$data->setVideoId($youtube_video_id);
						$data->setVideoEmbed($oembed_json->html);
						$data->setVideoWidth($oembed_json->width);
						$data->getVideoHeight($oembed_json->height);
						$data->setVideoDuration(null);
						$data->setThumbnailUrl($oembed_json->thumbnail_url);
						$data->setThumbnailWidth($oembed_json->thumbnail_width);
						$data->setThumbnailHeight($oembed_json->thumbnail_height);
						$data->setThumbnailUrlPlayButton($oembed_json->thumbnail_url);
						// if(empty($form_data->getTitle())){
						// 	$data->setTitle($oembed_json->title);
						// }
					}else{
						$util->setCustomeFlashMessage('warning', $msg="The URL for this video is not valid.");
						$redirect_uri = $this->generateUrl(self::ROUTER_ADD);
					}
				}
			}

			$em->persist($data);
	    	$em->flush();

			//product data
			$frm_product = $request->request->get('admin_product');

			//save product image size s,m,l
			$product_util->saveProductImageSize($data, null);

			//save image_gallery
			$product_util->saveProductImageGallery($data);

			$product_util->saveProductImageTechnique($data);

			// $product_util->saveProductImageGallery($data);

			//validate inventory with button "add" and "set"
			// $product_util->saveProductInventoryAdjustment($data, $frm_product);

			//weight grams
			// $product_util->saveProductWeightGrams($data, $frm_product);

			//tags
			// $product_util->saveProductHashtags($data, $frm_product);

			//variants
			// $product_util->saveProductVariants($data, $frm_product);

			$util->setCreateNotice();
			$redirect_uri = $util->getRedirectUriSaveBtn($form, $data, self::ROUTER_INDEX, self::ROUTER_ADD, self::ROUTER_EDIT);
			return $this->redirect($redirect_uri);
    	}
		// dump($data);
		// exit;
    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':new.html.twig', array(
			'form'=>$form->createView(),
			'acctoken'=>$acctoken
		));
  	}

	/**
	 * @Secure(roles="ROLE_EDITOR")
	 */
	public function editAction(Request $request)
	{
    	$util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');
		$util->setCkAuthorized();
		$acctoken = $util->getAccessToken();

		$data = $this->getDoctrine()->getRepository(Product::class)->find($request->get('id'));
		if (!$data) { throw $this->createNotFoundException('No data found'); }

		$product_id = $data->getId();
		$form = $this->createForm(AdminProductType::class, $data, array('allow_extra_fields'=>true));

		// $have_variants = $product_util->isVariants($data);

    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array(
			'form'=>$form->createview(),
			'acctoken'=>$acctoken,
			'product'=>$data
		));
  	}

	/**
	 * @Secure(roles="ROLE_EDITOR")
	 */
	public function updateAction(Request $request)
	{
    	$util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');

    	$em = $this->getDoctrine()->getManager();
		$acctoken = $util->getAccessToken();

		$data = $em->getRepository(Product::class)->findOneById($request->get('id'));
		if (!$data) { throw $this->createNotFoundException('No data found'); }
		$current_video = $data->getVideo();
		$product_id = $data->getId();
		$ori_image = $data->getImage();
	    $form = $this->createForm(AdminProductType::class, $data, array('allow_extra_fields'=>true));
	    $form->handleRequest($request);

		$data = $form->getData();
		// dump($form->getData());
		// dump($form->getErrors(false));
		// dump($form->getErrors(true));
		//
		// exit;
	    if ($form->isSubmitted() && $form->isValid())
		{
			if($request->get('removefileimage')==1){
				$data->removeImage();
			}

			if($request->get('removefileImageInnerVideo')==1){
				$data->removeImageInnerVideo();
			}
			if($request->get('removefileImageRadarChart')==1){
				$data->removeImageRadarChart();
			}

			$form_data = $form->getData();
			// $form_data = $form->getData();
			$video = $form_data->getVideo();
			if (strcmp($current_video, $video) !== 0) {
				$video_type = $util->videoType($video);
				if($video_type == 'youtube'){
					// Youtube Json format
					$json_url = $util->youtube_json_url($video);
					$youtube_video_id = $util->youtube_video_id($video);
					$oembed_json = json_decode($util->curl_get($json_url));
					if($oembed_json){
						$data->setVideoId($youtube_video_id);
						$data->setVideoEmbed($oembed_json->html);
						$data->setVideoWidth($oembed_json->width);
						$data->getVideoHeight($oembed_json->height);
						$data->setVideoDuration(null);
						$data->setThumbnailUrl($oembed_json->thumbnail_url);
						$data->setThumbnailWidth($oembed_json->thumbnail_width);
						$data->setThumbnailHeight($oembed_json->thumbnail_height);
						$data->setThumbnailUrlPlayButton($oembed_json->thumbnail_url);

					}else{
						$util->setCustomeFlashMessage('warning', $msg="The URL for this video is not valid.");
						$redirect_uri = $this->generateUrl(self::ROUTER_EDIT,array('id'=>$data->getId()));
						return $this->redirect($redirect_uri);
					}
				}
			}


			// $data = $form->getData();
			// var_dump($data['inventoryPolicyStatus']);
			// exit;

			$em->flush();

			// dump($form->getData());
			// exit;
			// $em->flush();

			//product data
			$frm_product = $request->request->get('admin_product');

			//save product image size s,m,l
			$product_util->saveProductImageSize($data, $ori_image);


			//save image_gallery
			$product_util->saveProductImageGallery($data);


				// dump($data);
				// exit;
			$product_util->saveProductImageTechnique($data);

			// $em->flush();
			//validate inventory with button "add" and "set"
			// $product_util->saveProductInventoryAdjustment($data, $frm_product);

			//weight grams
			//$product_util->saveProductWeightGrams($data, $frm_product);

			//tags
			//$product_util->saveProductHashtags($data, $frm_product);

			//update variants
			//$product_util->updateProductVariants($data);

			//create variants
			//$product_util->saveProductVariants($data, $frm_product);

			//save product inventory status
			//$product_util->skuSaveProductInventoryStatus($data);
			// $data->setproductTechNique($data);
			// dump($data);
			// exit;

			$util->setUpdateNotice();
			$redirect_uri = $util->getRedirectUriSaveBtn($form, $data, self::ROUTER_INDEX, self::ROUTER_ADD, self::ROUTER_EDIT);
			return $this->redirect($redirect_uri);
    	}/*else{
			echo "ssss";
			exit;
		}*/
    	return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array(
			'form'=>$form->createview(),
			'acctoken'=>$acctoken,
			'product'=>$data
		));
  	}

	/**
	 * @Secure(roles="ROLE_EDITOR")
	 */
	public function deleteAction(Request $request)
	{
    	$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();

    	$data = $em->getRepository(Product::class)->find($request->get('id'));
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
				$data = $em->getRepository(Product::class)->find($data_id);
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
				$data = $em->getRepository(Product::class)->find($data_id);
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
				$data = $em->getRepository(Product::class)->find($data_id);
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
	public function sortAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$arr_query_data = $util->prepare_query_data($request);
		$datas = $this->getQuerySearchData($arr_query_data, $request->getLocale())
			->getQuery()
			->getResult();

		return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':sort.html.twig', array('datas' =>$datas));
	}

	/**
	 * @Secure(roles="ROLE_EDITOR")
	 */
	public function sort_prosessAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();

		$i=0;
		$sorted = $request->get('sort');
		if ($sorted) {
			foreach ($sorted as $data_id) {
				$data = $em->getRepository(Product::class)->find($data_id);
				if ($data) {
					$i=$i+1;
					$data->setPosition($i);
				}
			}
			try {
				$em->flush();
				$status='complete';
			} catch(\Exception $e) {
				$status='error';
			}
			return new Response($status);
		}
	}
}
