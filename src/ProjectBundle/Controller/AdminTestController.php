<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ProjectBundle\Entity\Product;
use ProjectBundle\Entity\Category;
use ProjectBundle\Entity\Brand;
use ProjectBundle\Entity\Location;
use ProjectBundle\Entity\Salon;
use ProjectBundle\Entity\ProfessionalVideo;
use ProjectBundle\Entity\VideoCategory;

use ProjectBundle\Entity\Style;
use ProjectBundle\Entity\StyleImage;
use ProjectBundle\Entity\StyleCategory;
use ProjectBundle\Entity\PaymentGateway;
use ProjectBundle\Entity\Holiday;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

class AdminTestController extends Controller
{
	/**
	 * @Secure(roles="ROLE_EDITOR")
	 */
	public function indexAction(Request $request)
	{
		$util = $this->container->get('utilities');
		$em = $this->getDoctrine()->getManager();

		// get holiday
		// $holiday = $em->getRepository(Holiday::class)->findActiveData()->getQuery()->getArrayResult();
		// print_r($holiday);
		// $delivery_date = new \DateTime('+2 days');
		// $delivery_date->setTime(0, 0, 0);
		// $count_holiday = $em->getRepository(Holiday::class)->findPublicHolidayByDate($delivery_date)->getQuery()->getArrayResult();
		// print_r($count_holiday);


		// find one record
		// $pw = $em->getRepository(PaymentGateway::class)->createQueryBuilder('p')->getQuery()->getOneOrNullResult();
		// $a = $pw->getGateway();

		// Save PaymentGateway
		// $pg = new PaymentGateway();
		// $arrGW = array('BT','CRDT','COD');
		// $pg->setGateway($arrGW);
		// $em->persist($pg);
		// $em->flush();
		// Get PaymentGateway
		// $payment_gateway = $this->getDoctrine()->getRepository(PaymentGateway::class)->find(1);
		// $pw = $payment_gateway->getGateway();

		// // Saving Related
		// $category = new Category();
    	// $category->setTitle('Computer Peripherals');
		//
		// $brand = new Brand();
    	// $brand->setTitle('Razer');
		//
		// $product = new Product();
	    // $product->setTitle('Monitor');
	    // $product->setPrice(199.99);
	    // $product->setDescription('Ergonomic and stylish!');
		//
		// // relate this product to the category
    	// $product->setCategory($category);
		// $product->setBrand($brand);
		//
		// $em->persist($category);
    	// $em->persist($brand);
		// $em->persist($product);
    	// $em->flush();
		//
		// #Fetching Related
		// $productId = 4;
		// $product = $this->getDoctrine()
        // ->getRepository(Product::class)
        // ->find($productId);
    	// $category = $product->getCategory();
		// echo $category->getTitle();
		//
		// $categoryId=1;
		// $category = $this->getDoctrine()
        // ->getRepository(Category::class)
        // ->find($categoryId);
    	// $products = $category->getProducts();
		// foreach ($products as $product) {
		// 	echo $product->getTitle();
		// 	echo '<br/>';
		// }
		//
		// // Joining Related Records
		// $productId = 4;
		// $product = $em
		// 	->createQuery(
        // 'SELECT p, c FROM ProjectBundle:Product p
        // JOIN p.category c
        // WHERE p.id = :id')
		// 	->setParameter('id', $productId)
		// 	->getSingleResult();
		// $category = $product->getCategory();
		// echo $category->getTitle();
		//
		// $productId = 4;
		// $product = $this->getDoctrine()
        // ->getRepository(Product::class)
        // ->findOneByIdJoinedToEquipment($productId);
		// if($product){
		// 	$category = $product->getCategory();
		// 	echo $category->getTitle();
		// }else{
		// 	echo 'nodata';
		// }
		//
		// $catId = 2;
		// $category = $this->getDoctrine()
        // ->getRepository(Category::class)
        // ->findOneByIdJoinedToProducts($catId);
		// if($category){
		// 	$category->getId();
		// 	$products = $category->getProducts();
		// 	foreach ($products as $product) {
		// 		echo $product->getId();
		// 		echo '<br/>';
		// 	}
		// }else{
		// 	echo 'nodata';
		// }

		// // ************************************************//
		// // One to Many
		//
		// $location = new Location();
    	// $location->setTitle('Phayathai');
		//
		// $salon = new Salon();
	    // $salon->setTitle('P1');
	    // $salon->setDescription('Salon phayathai station!');
		//
		// // relate this salon to the location
    	// $salon->setLocation($location);
		//
		// $em->persist($location);
		// $em->persist($salon);
    	// $em->flush();
		//
		// // ************************************************//
		// // One to Many
		//
		// $video_category = new VideoCategory();
    	// $video_category->setTitle('Main Video');
		//
		// $professional_video = new ProfessionalVideo();
	    // $professional_video->setTitle('Video 1');
	    // $professional_video->setDescription('Hello world!');
		// $professional_video->setEmbed('<image src="yes"/>');
		//
		// // relate this salon to the location
    	// $professional_video->setVideoCategory($video_category);
		//
		// $em->persist($video_category);
		// $em->persist($professional_video);
    	// $em->flush();
		//
		// // ************************************************//
		// // One to Many
		//
		// $style_category = new StyleCategory();
    	// $style_category->setTitle('Longhair');
		//
		// $style = new Style();
	    // $style->setTitle('Style 1');
	    // $style->setDescription('Hello world!');
		// $style->setImage('<image src="/s1.jpg"/>');
		//
		// $style_image1 = new StyleImage();
    	// $style_image1->setImage('<image src="/i1.jpg"/>');
		// $style_image1->setStyle($style);
		//
		// $style_image2 = new StyleImage();
    	// $style_image2->setImage('<image src="/i2.jpg"/>');
		// $style_image2->setStyle($style);
		//
		// $em->persist($style);
		// $em->persist($style_image1);
		// $em->persist($style_image2);
    	// $em->flush();

		// // ************************************************//
		// // Many to Many
		//
		// // Create Style and Add related
		// $style = new Style();
    	// $style->setTitle('Style 4');
		// // Get style_cat data
		// $style_cat_id = 1;
		// $style_category = $em->getRepository(StyleCategory::class)->find($style_cat_id);
		// // Related
		// $style->addStyleCategories($style_category);
		// $em->persist($style);
    	// $em->flush();
		//
		// // Create StyleCategory and Add related
		// $style_category = new StyleCategory();
	    // $style_category->setTitle('Mama');
	    // $style_category->setDescription('Hi Mama!');
		// // Get style_cat data
		// $style_id = 1;
		// $style = $em->getRepository(Style::class)->find($style_id);
		// // Related
		// $style_category->addStyles($style);
		// $em->persist($style_category);
    	// $em->flush();
		//
		// // Get data and Add related
		// $style_cat_id = 2;
		// $style_category = $em->getRepository(StyleCategory::class)->find($style_cat_id);
		// $style_id = 4;
		// $style = $em->getRepository(Style::class)->find($style_id);
		// // Related
		// $style_category->addStyles($style);
		// $em->persist($style_category);
    	// $em->flush();
		//
		// // Remove related
		// $style_cat_id = 2;
		// $style_category = $em->getRepository(StyleCategory::class)->find($style_cat_id);
		// $style_id = 4;
		// $style = $em->getRepository(Style::class)->find($style_id);
		// // Related
		// $style->removeCategories($style_category); //remove from style
		// //$style_category->removeStyles($style); //remove from style_category
		// $em->persist($style_category);
    	// $em->flush();
		//
		// // Get related data from style_category
		// $style_cat_id = 1;
		// $style_category = $em->getRepository(StyleCategory::class)->find($style_cat_id);
		// if($style_category){
		// 	$styles = $style_category->getStyles();
		// 	if($styles){
		// 		foreach ($styles as $style) {
		// 			echo $style->getTitle();
		// 			echo '<br/>';
		// 		}
		// 	}
		// }
		//
		// // Get related data from style
		// $style_id = 4;
		// $style = $em->getRepository(Style::class)->find($style_id);
		// if($style){
		// 	$style_categories = $style->getStyleCategories();
		// 	if($style_categories){
		// 		foreach ($style_categories as $style_category) {
		// 			echo $style_category->getTitle();
		// 			echo '<br/>';
		// 		}
		// 	}
		// }

		//************************************************//
		// Many to Many

		// Create Product and Add related Salon
		// $product = new Product();
    	// $product->setTitle('New Product 1');
		// // Get style_cat data
		// $salon_id = 1;
		// $salon = $em->getRepository(Salon::class)->find($salon_id);
		// if($salon){
		// 	// Related
		// 	$product->addSalons($salon);
		// }
		// $em->persist($product);
    	// $em->flush();

		// Create Salon and Add related Product
		// $salon = new Salon();
	    // $salon->setTitle('Salon A');
	    // $salon->setDescription('Hello world');
		// // Get style_cat data
		// $product_id = 5;
		// $product = $em->getRepository(Product::class)->find($product_id);
		// if($product){
		// 	// Related
		// 	$salon->addProducts($product);
		// }
		// $em->persist($salon);
		// $em->flush();

		// Get data and Add related
		// $product_id = 6;
		// $product = $em->getRepository(Product::class)->find($product_id);
		// $salon_id = 2;
		// $salon = $em->getRepository(Salon::class)->find($salon_id);
		// // Related
		// $product->addSalons($salon);
		// // $salon->addProducts($product);
		// $em->persist($product);
    	// $em->flush();

		// Remove related
		// $salon_id = 1;
		// $salon = $em->getRepository(Salon::class)->find($salon_id);
		// $product_id = 6;
		// $product = $em->getRepository(Product::class)->find($product_id);
		// // Related
		// $product->removeSalons($salon); //remove from product
		// //$salon->removeProducts($product); //remove from salon
		// $em->persist($product);
    	// $em->flush();

		// Get related data from salon
		// $salon_id = 5;
		// $salon = $em->getRepository(Salon::class)->find($salon_id);
		// if($salon){
		// 	$products = $salon->getProducts();
		// 	if($products){
		// 		foreach ($products as $product) {
		// 			echo $product->getTitle();
		// 			echo '<br/>';
		// 		}
		// 	}
		// }

		// Get related data from product
		// $product_id = 5;
		// $product = $em->getRepository(Product::class)->find($product_id);
		// if($product){
		// 	$salons = $product->getSalons();
		// 	if($salons){
		// 		foreach ($salons as $salon) {
		// 			echo $salon->getTitle();
		// 			echo '<br/>';
		// 		}
		// 	}
		// }

		//************************************************//
    /*
    //insert object
    $product = new Product();
    $product->setName('Keyboard');
    $product->setPrice(19.99);
    $product->setDescription('Ergonomic and stylish!');
    $em->persist($product);
    $em->flush();
    exit;
    */

    /*
    //find object
    $productId =1;
    $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->find($productId);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$productId
        );
    }
    echo $product->getId();
    echo $product->getName();
    echo $product->getPrice();
    echo $product->getDescription();
    */

    /*
    $repository = $this->getDoctrine()->getRepository(Product::class);
    //repository object find all
    $products = $repository->findAll();
    print_r($products);
    */

    /*
    // query for a single product matching the given name and price
    $repository = $this->getDoctrine()->getRepository(Product::class);
    $product = $repository->findOneBy(
        array('name' => 'Keyboard', 'price' => 19.99)
    );
    print_r($product);

    // query for multiple products matching the given name, ordered by price
    $products = $repository->findBy(
        array('name' => 'Keyboard'),
        array('price' => 'ASC')
    );
    print_r($products);
    */

    //updating an object
    /*
    $productId=1;
    $product = $em->getRepository(Product::class)->find($productId);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$productId
        );
    }
    $product->setName('New product name!');
    $em->flush();
    exit;
    */

    //Deleting an Object
    /*
    $productId=3;
    $product = $em->getRepository(Product::class)->find($productId);
    $em->remove($product);
    $em->flush();
    */

    //Querying for Objects
    /*
    $repository = $this->getDoctrine()->getRepository(Product::class);
    $product = $repository->find(1);
    $product = $repository->findOneByName('Keyboard');
    */

    //Querying for Objects with DQL
    /*
    $query = $em->createQuery(
        'SELECT p
        FROM ProjectBundle:Product p
        WHERE p.price > :price
        ORDER BY p.price ASC'
    )->setParameter('price', 10.99);
    $products = $query->getResult();
    //$products = $query->setMaxResults(1)->getOneOrNullResult();
    print_r($products);
    exit;
    */

    //Querying for Objects Using Doctrine's Query Builder
    /*
    $repository = $this->getDoctrine()->getRepository(Product::class);
    $query = $repository->createQueryBuilder('p')
        ->where('p.price > :price')
        ->setParameter('price', '10.99')
        ->orderBy('p.price', 'ASC')
        ->getQuery();
    $products = $query->getResult();
    print_r($products);
    exit;
    */


    //Create custom Repository Classes
    // $products = $this->getDoctrine()
    //     ->getRepository(Product::class)
    //     ->findAllOrderedByName();
    // print_r($products);
    // exit;



    // //** Working with QueryBuilder Expr **//
    // $repository = $this->getDoctrine()->getRepository(User::class);
    // $qb = $repository->createQueryBuilder('u');
    // $qb->where($qb->expr()->andX(
    //        $qb->expr()->notLike('u.roles', ':user_roles')
    //     ))
    //     ->setParameter('user_roles', '%ROLE_CUSTOMER%')
    //     ->orderBy('u.roles', 'ASC');
    // $datatable = $qb->getQuery()->getResult();

		// //** Working with QueryBuilder **//
		// $repository = $this->getDoctrine()->getRepository(User::class);
    // $qb = $repository->createQueryBuilder('u');
    // $qb->where('u.roles NOT LIKE :user_roles')
    //     ->setParameter('user_roles', '%ROLE_CUSTOMER%')
    //     ->orderBy('u.roles', 'ASC');
    // $datatable = $qb->getQuery()->getResult();

		// //** Doctrine Query Language (DQL) **//
    // $em = $this->getDoctrine()->getManager();
    // $query = $em->createQuery(
    //     'SELECT u
    //     FROM ProjectBundle:User u
    //     WHERE u.roles NOT LIKE :user_roles
    //     ORDER BY u.roles ASC'
    // )->setParameter('user_roles', '%ROLE_CUSTOMER%');
    // $datatable = $query->getResult();

		//find by relation user or filed
		// $access_tokens = $em->getRepository(AccessToken::class)->findByUser($user);


		//************************************************//

    /*
    //Creating A Client (Authorization Request)
    $clientManager = $this->get('fos_oauth_server.client_manager.default');
    $client = $clientManager->createClient();
    $client->setRedirectUris(array('http://www.example.com'));
    $client->setAllowedGrantTypes(array('token', 'authorization_code', 'password', 'refresh_token', 'client_credentials'));
    $clientManager->updateClient($client);
    */

    /*
    //Basic Auth
    //User: Bearer
    //Pass: Token (class_key 2)
    //Authorization:Bearer ZDY4MzM3ZmFlMWM4YTdiZjY2ZDVmNTJkMzAwODkwMWRkZjAz...
    //Protected Resource
    $acctoken = '';
    $response = $this->call(
        'GET',
        '/api/v1/users',
        $acctoken,
        json_encode($request->request->all())
    );
    $status = json_decode($response->getBody());
    print_r($status->datas);
    exit;
    */

		/*
		//use http
	    $request = $this->get('request_stack')->getCurrentRequest();
		$shceme_http = $request->getSchemeAndHttpHost();
	    $shceme_http = str_replace("https://", "http://", $shceme_http);
	    $base_uri = $shceme_http.$this->container->get('router')->getContext()->getBaseUrl().'/api/v1/';

	    $method = 'GET';
	    $uri = 'public/users';
	    $postData = '';
	    $client = new Client(['base_uri' => $base_uri]);
	    $response = $client->request(
	           $method,
	           $uri,
	           [
	               'headers' => [
	                   'Content-Type' => 'application/x-www-form-urlencoded',
	                   'Accept' => 'application/json',
	                   #'Authorization' => 'Bearer '.$auth
	               ],
	               'body' => $postData, //'debug' => true,
	               'verify' => false
	           ]
	    );
	    $status = json_decode($response->getBody());
	    if($status->status){
	      $status->status;
	    }
		*/

		// Test send mail
		$message = (new \Swift_Message('Test message 2'))
		->setFrom(array('acseine.th@gmail.com' => 'acseine.th@gmail.com'))
		->setTo(array('num@zap-interactive.com'))
		->setBody(
			$this->renderView(
				'ProjectBundle:'.$this->container->getParameter('view_main').':_email_test.html.twig',
				array()
			),
			'text/html'
		);
		$this->get('mailer')->send($message);


		return $this->render('ProjectBundle:AdminTest:index.html.twig', array());
	}
}
