<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ProjectBundle\Entity\User;
use ProjectBundle\Entity\Product;
use ProjectBundle\Entity\Showroom;
use ProjectBundle\Entity\ProductCategory;
use ProjectBundle\Entity\Series;
use ProjectBundle\Entity\Pages;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ProjectBundle\Form\Type\Product\ProductSearchType;
use ProjectBundle\Form\Type\Product\AddToCartType;

class ProductController extends Controller
{
	public function indexAction(Request $request)
    {
        $product_categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findPublicDataJoinedProduct()->getQuery()->getResult();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_product').':index.html.twig', array(
            'product_categories'=>$product_categories,
        ));
    }

	public function seriesAction(Request $request, $id)
    {
        $series = $this->getDoctrine()->getRepository(Series::class)->findOnePublicDataJoinedProduct($id)->getQuery()->getOneOrNullResult();
		if (!$series) { throw $this->createNotFoundException('The product series does not exist'); }

		$products = $this->getDoctrine()->getRepository(Product::class)->findActiveDataBySeries($series)->getQuery()->getResult();
		// getArrayResult getResult getOneOrNullResult

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_product').':series.html.twig', array(
            'series'=>$series,
			'products'=>$products,
        ));
    }

	public function detailAction(Request $request, $id)
	{
		$util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');
		$em = $this->getDoctrine();

		$product_rs = $this->getDoctrine()->getRepository(Product::class)->getActiveDataById($id)->getQuery()->getSingleResult();
		if (!$product_rs) { throw $this->createNotFoundException('The product does not exist'); }
		$product = $product_rs[0];
		// getArrayResult getSingleResult

		$series = null;
		$series_id = $request->get('series_id');
		if($series_id){
			$series = $this->getDoctrine()->getRepository(Series::class)->findOnePublicDataJoinedProduct($series_id)->getQuery()->getOneOrNullResult();
		}
		// getArrayResult getOneOrNullResult

		$products_relateds = $this->getDoctrine()->getRepository(Product::class)->getActiveDataByProductsRelated($id, $product)->setMaxResults(4)->getQuery()->getResult();

		//get variant data
		$arr_variant_data = $product_util->setArrProductVariantsData($product);
		$arr_sku_variant_data = $arr_variant_data['arr_sku_variant_data'];
		$arr_variant_option_data = $arr_variant_data['arr_variant_option_data'];
		$is_variant = $product_util->isVariantsFromArrVariantData($arr_sku_variant_data['variant_data']);

		$form = $this->createForm(AddToCartType::class);
		$page_data = $this->getDoctrine()->getRepository(Pages::class)->getActiveDataByName('product_detail', $request->getLocale());

		return $this->render('ProjectBundle:'.$this->container->getParameter('view_product').':detail.html.twig', array(
			'form'=>$form->createView(),
			'series'=>$series,
			'product_rs'=>$product_rs,
			'product'=>$product,
			'arr_sku_variant_data'=>$arr_sku_variant_data,
			'arr_variant_option_data'=>$arr_variant_option_data,
			'is_variant'=>$is_variant,
			'products_relateds'=>$products_relateds,
			'page_data'=>$page_data
        ));
	}

	/*
	public function indexAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$locale = $request->getLocale();
		$session = $request->getSession();
		$form = $this->createForm(ProductSearchType::class);
		$repository = $this->getDoctrine()->getRepository(Product::class);
		$formData = $request->query->get($form->getName('product_search'));

		$form->handleRequest($request);
		$data = $form->getData();

		$limitPages = $this->container->getParameter('max_per_page_latest_product');

		$limitPerPage = (isset($data['limitPerPage'])) ? $data['limitPerPage'] : $limitPages;
		$query = $repository->findAllActiveData($data, $locale);
		$paginated = $util->setPaginatedOnPagerfanta($query,$limitPerPage);

		//dump($form->submit($request->request->get('age_groups')));
		//$formData = $request->query->get($form->getName('product_search'));

		// dump($paginated);
		// exit();

		// $brands = $this->getDoctrine()->getRepository(Brand::class)->findAllActiveByProduct($locale);
		// $equipments = $this->getDoctrine()->getRepository(Equipment::class)->findAllActiveByProduct($locale);
		// $age_groups = $this->getDoctrine()->getRepository(AgeGroup::class)->findAllActiveByProduct($locale);

		return $this->render('ProjectBundle:'.$this->container->getParameter('view_product').':index.html.twig', array(
			'paginated'=>$paginated,
			'form' =>$form->createView(),
			// 'brands'=>$brands,
			// 'equipments'=>$equipments,
			// 'age_groups'=>$age_groups,
		));
	}

	public function detailAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$product_util = $this->container->get('app.product');
		$session = $request->getSession();
		$em = $this->getDoctrine();
		$product = $em->getRepository(Product::class)->getActiveDataById($request->get('id'), $request->getLocale())->getQuery()->getSingleResult();

		if (!$product) { throw $this->createNotFoundException('No data found'); }
		$obj_product = $product[0];

		//get variant data
		$arr_variant_data = $product_util->setArrProductVariantsData($obj_product);
		$arr_sku_variant_data = $arr_variant_data['arr_sku_variant_data'];
		$arr_variant_option_data = $arr_variant_data['arr_variant_option_data'];
		$is_variant = $product_util->isVariantsFromArrVariantData($arr_sku_variant_data['variant_data']);

		$customer_groups = $em->getRepository(CustomerGroup::class)->getCustomerGroupByProduct($obj_product, $request->getLocale());
		$age_groups = $em->getRepository(AgeGroup::class)->getAgeGroupByProduct($obj_product, $request->getLocale());
		$muscles = $em->getRepository(Muscle::class)->getMuscleByProduct($obj_product, $request->getLocale());
		$showrooms = $em->getRepository(Showroom::class)->getShowroomByProduct($obj_product, $request->getLocale());
		$products_relateds = $em->getRepository(Product::class)->getActiveDataByProductsRelated($request->get('id'),$obj_product, $request->getLocale())->setMaxResults(6)->getQuery()->getResult();

		$form = $this->createForm(AddToCartType::class);

		return $this->render('ProjectBundle:'.$this->container->getParameter('view_product').':detail.html.twig', array(
			'form'=>$form->createView(),
			'product'=>$product,
			'arr_sku_variant_data'=>$arr_sku_variant_data,
			'arr_variant_option_data'=>$arr_variant_option_data,
			'is_variant'=>$is_variant,
			'customer_groups'=>$customer_groups,
			'age_groups'=>$age_groups,
			'muscles'=>$muscles,
			'showrooms'=>$showrooms,
			'products_relateds'=>$products_relateds,
		));
	}
	*/

}
