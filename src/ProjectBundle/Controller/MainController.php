<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ProjectBundle\Entity\User;
use ProjectBundle\Entity\Blog;
use ProjectBundle\Entity\BlogImage;
use ProjectBundle\Entity\BrandType;
use ProjectBundle\Entity\Brand;
use ProjectBundle\Entity\Product;
use ProjectBundle\Entity\Promotion;
use ProjectBundle\Entity\Banner;
use ProjectBundle\Entity\Contact;
use ProjectBundle\Entity\SettingOption;
use ProjectBundle\Entity\Authentication;
use ProjectBundle\Entity\Pages;
use ProjectBundle\Entity\Showroom;
use ProjectBundle\Entity\OurClient;
use ProjectBundle\Entity\CustomerPaymentBankTransfer;
use ProjectBundle\Entity\CustomerOrder;
use ProjectBundle\Entity\CustomerOrderDelivery;
use ProjectBundle\Entity\BankAccount;
use ProjectBundle\Entity\CustomerOrderItem;
use ProjectBundle\Entity\History;
use ProjectBundle\Entity\Location;
use ProjectBundle\Entity\ProductCategory;
use ProjectBundle\Entity\TrackingNumber;
use ProjectBundle\Entity\Series;
use ProjectBundle\Entity\Styling;
use ProjectBundle\Entity\Movie;


use ProjectBundle\Form\Type\CustomerRegisterType;
use ProjectBundle\Form\Type\CustomerPaymentBankTransferType;
use ProjectBundle\Form\Type\ContactType;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


use ProjectBundle\Form\Type\PaymentBankTransferType;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

class MainController extends Controller
{
    public function _footerAction(Request $request)
    {
        $em = $this->getDoctrine();
        $news_recents  = $em->getRepository(Blog::class)->findAllActiveData()->setMaxResults(3)->getQuery()->getResult();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':_footer.html.twig', array(
            'news_recents'=>$news_recents
        ));
    }
    public function _menuProductsAction(Request $request)
    {

        $em = $this->getDoctrine();
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAllCategory()->getQuery()->getResult();
        //$categories_new_products = $this->getDoctrine()->getRepository(Series::class)->findNewProductInSeries()->getQuery()->getResult();

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_layout').':_munu_products.html.twig', array(
            'categories'=>$categories

        ));
    }

    public function _menuDesktopAction(Request $request)
    {

        $em = $this->getDoctrine();
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAllCategory()->getQuery()->getResult();
        $categories_new_products = $this->getDoctrine()->getRepository(Series::class)->findNewProductInSeries()->getQuery()->getResult();

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_layout').':_desktop_menu.html.twig', array(
            'categories'=>$categories,'new_products'=>$categories_new_products

        ));
    }

    public function _menuMobileAction(Request $request)
    {

        $em = $this->getDoctrine();
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAllCategory()->getQuery()->getResult();
        $categories_new_products = $this->getDoctrine()->getRepository(Series::class)->findNewProductInSeries()->getQuery()->getResult();

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_layout').':_mobile_menu.html.twig', array(
            'categories'=>$categories,'new_products'=>$categories_new_products

        ));
    }


    public function indexAction(Request $request)
    {
        $banners = $this->getDoctrine()->getRepository(Banner::class)->findAllActiveData($request->getLocale())->getQuery()->getResult();
        $product_categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAllCategory()->getQuery()->getResult();

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':index.html.twig', array(
            'banners'=>$banners,'product_categories'=>$product_categories,
        ));
    }

    public function aboutAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':about.html.twig', array(

        ));
    }
    public function sitemapAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAllCategory()->getQuery()->getResult();

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':sitemap.html.twig', array(
                'categories'=>$categories
        ));
    }
    public function stylingAction(Request $request)
    {
        $product_categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findPublicDataJoinedStyling()->getQuery()->getResult();

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':styling.html.twig', array(
            'product_categories' =>$product_categories,
        ));
    }

    public function moviesAction(Request $request)
    {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAllActiveData($request->getLocale())->getQuery()->getResult();


        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':movies.html.twig', array(
            'movies'=>$movies
        ));
    }

    public function new_productsAction(Request $request)
    {
        //$form = $this->createForm(ContactType::class, new Contact());
        $em = $this->getDoctrine();

        $categories_new_products = $this->getDoctrine()->getRepository(Series::class)->findNewProductInSeries()->getQuery()->getResult();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':new_products.html.twig', array(
            'new_products'=>$categories_new_products
        ));
    }

    public function productsAction(Request $request)
    {
        //$form = $this->createForm(ContactType::class, new Contact());
        $product_categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAllCategory()->getQuery()->getResult();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':products.html.twig', array(
            'product_categories'=>$product_categories,

        ));
    }

    public function seriesAction(Request $request)
    {
        //$form = $this->createForm(ContactType::class, new Contact());
        $category_id = $request->get("id");
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findCategoryByID($category_id)->getQuery()->getResult();
        $products = $this->getDoctrine()->getRepository(Series::class)->findSeriesDataJoinedByID($category_id)->getQuery()->getResult();

        // dump($series);
        // exit;
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':series.html.twig', array(
            'products'=>$products,'categories'=>$categories

        ));
    }

    public function product_series_listAction(Request $request)
    {
        //$form = $this->createForm(ContactType::class, new Contact());
        $category_id = $request->get("id");
        $series = $this->getDoctrine()->getRepository(Series::class)->findSeriesByID($category_id)->getQuery()->getResult();
        $products = $this->getDoctrine()->getRepository(Product::class)->findActiveDataBySeries($category_id)->getQuery()->getResult();

        $series_related = $this->getDoctrine()->getRepository(Series::class)->findOnePublicDataJoinedProductRelated($category_id)->setMaxResults(3)->getQuery()->getResult();


        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':product_series_list.html.twig', array(
            'products'=>$products,'series'=>$series,'series_related' =>$series_related,
        ));
    }
    public function style_guideAction(Request $request)
    {
        $data = $this->getDoctrine()->getRepository(Pages::class)->getActiveDataByName('style_guide','th');
        if (!$data) { throw $this->createNotFoundException('No data found'); }
// dump($data);
// exit;

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':style_guide.html.twig', array(
            'data'=>$data
        ));
    }



    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class, new Contact());
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':contact.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function contact_createAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->submit($request->request->all());
        $data = $form->getData();


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $subject = 'You have a new message(s) in your contact';
            $urls = $this->generateUrl('admin_contact_view',array('id'=>$data->getId()), UrlGeneratorInterface::ABSOLUTE_URL);
            $response = $this->sendmail($urls,$subject,$data);
            return new JsonResponse($response);
        }else{
            // $errors = $this->getFormErrorMessage($form);
            $errors = $util->getFormErrorMessage($form);
            $response['errors'] = $errors;
            $response['success'] = false;
            return new JsonResponse($response);
        }
    }

    public function blogAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $session = $request->getSession();
        $repository = $this->getDoctrine()->getRepository(Blog::class);
        $query = $repository->findAllActiveData();
        $paginated = $util->setPaginatedOnPagerfanta($query,10);
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':blog.html.twig', array(
            'paginated' =>$paginated
        ));
    }

    public function blog_detailAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine();
        $data = $em->getRepository(Blog::class)->getActiveDataById($request->get('id'))->getQuery()->getSingleResult();
        if (!$data) { throw $this->createNotFoundException('No data found'); }
        $data_image = $em->getRepository(BlogImage::class)->findBy(array('blog' => $request->get('id')), array('id' => 'ASC'));
        $recent_news  = $em->getRepository(Blog::class)->getActiveDataByRecent($request->get('id'))->setMaxResults(5)->getQuery()->getResult();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':blog_detail.html.twig', array(
            'data'=>$data,'data_image'=>$data_image,'recent_news'=>$recent_news
        ));
    }

    public function showroomAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $session = $request->getSession();
        $em = $this->getDoctrine();
        $query = $em->getRepository(Showroom::class)->findAllActiveData($request->getLocale());
        $paginated = $util->setPaginatedOnPagerfanta($query,12);
        $google_maps_api_key = $em->getRepository(Authentication::class)->findOneBy(['name'=>'google_maps_api_key']);
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':showroom.html.twig', array(
            'paginated' =>$paginated,
            'google_maps_api_key'=>$google_maps_api_key
        ));
    }

    public function searchAction(Request $request)
    {
        $session = $request->getSession();
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':search.html.twig', array());
    }


    /**
    * @Secure(roles="ROLE_CLIENT, ROLE_CUSTOMER, ROLE_USER, ROLE_EDITOR, ROLE_ADMIN")
    */
    public function default_target_loginAction(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $auth_checker = $this->get('security.authorization_checker');
        $util = $this->container->get('utilities');

        $util->destroySessionAfterLogin($request);

        if( $auth_checker->isGranted('ROLE_EDITOR') || $auth_checker->isGranted('ROLE_ADMIN') ){
            return $this->redirect($this->generateUrl('admin'));

        }elseif( $auth_checker->isGranted('ROLE_CLIENT') || $auth_checker->isGranted('ROLE_CUSTOMER') ){
            //check valid token for only customer is_set_password
            $is_set_pwd = $user->getIsSetPassword();
            if($is_set_pwd){
                try {
                    $acctoken = $util->getAccessToken();
                    //check token expire here
                } catch(\Exception $e) {
                	//no access token or expire redirect to generate_token
                    return $this->redirectToRoute('member_generate_token');
                }
            }

            if($auth_checker->isGranted('ROLE_CLIENT')){
                return $this->redirect($this->generateUrl('designer'));
            }else{
                return $this->redirect($this->generateUrl('fos_user_profile_show'));
            }

        }else{
            return $this->redirect($this->generateUrl('homepage'));
        }
    }



    public function default_target_logoutAction(Request $request)
    {
        $util = $this->container->get('utilities');

        $util->destroySessionAfterLogout($request);
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
    * @Secure(roles="ROLE_ADMIN, ROLE_EDITOR, ROLE_CUSTOMER, ROLE_CLIENT")
    */
    public function default_target_password_resettingAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $session = $request->getSession();
        $user = $this->getUser();

        if($session->has('tmp_password_resetting')){
            //get password
            $new_pwd = $session->get('tmp_password_resetting');
            //get email
            $email = $user->getEmail();

            //get user scope
            $user_roles = $user->getRoles();
            if( in_array("ROLE_CLIENT",$user_roles) ){
                $scope = $this->container->getparameter('access_token_client_scope');
            }else{
                $scope = $this->container->getparameter('access_token_customer_scope');
            }

            //set oauth token
            $util->setAccessToken($email, $new_pwd, $scope);
            //reset session access token
            $token = $util->getAccessTokenFromDB();

            //remove session tmp_password_resetting
            $session->remove('tmp_password_resetting');
        }
        //return $this->redirect($this->generateUrl('fos_user_profile_show'));
        return $this->redirect($this->generateUrl('default_target_login'));
    }

    public function customer_registerAction(Request $request)
    {
        /*
        $session = $request->getSession();
        $user = $this->getUser();
        if($user){
          throw $this->createNotFoundException('You are not permitted to use that link to directly access that page');
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm(CustomerRegisterType::class, $user);
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':customer_register.html.twig', array('form' =>$form->createView()));
        */
    }

    public function customer_register_createAction(Request $request)
    {
        /*
        $util = $this->container->get('utilities');
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $user = $this->getUser();
        if($user){
          throw $this->createNotFoundException('You are not permitted to use that link to directly access that page');
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $form = $this->createForm(CustomerRegisterType::class, $user);
        $form->handleRequest($request);
        $datas = $form->getData();
        $email = $datas->getEmail();

        $chk_email = $em->getRepository(User::class)->findByEmailCanonical($email);
        if(count($chk_email)>0){
          $form->get('email')->addError(new FormError('The email is already used'));//already exists email
        }

        if ($form->isSubmitted() && $form->isValid())
        {
          $plainpass = $datas->getPlainPassword();
          $roles = array('ROLE_CUSTOMER');

          // we set username in user entity
          // $user->setUsername($email);
          // $user->setUsernameCanonical($email);

          $user->setRoles($roles);

          $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
          if($confirmationEnabled){
            // register with email comfirmation
            $mailer = $this->container->get('fos_user.mailer');
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            //save confirmation token
            $user->setConfirmationToken($tokenGenerator->generateToken());
            //send confirmation email
            $mailer->sendConfirmationEmailMessage($user);
            //save user data
            $userManager->updateUser($user);
            //set oauth token
            $scope = $this->container->getparameter('access_token_customer_scope');
            $util->setAccessToken($email, $plainpass, $scope);
            $this->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
            $route = 'fos_user_registration_check_email';
            $this->get('session')->getFlashBag()->add('fos_user_success', 'registration.flash.user_created');
            return $this->redirect($this->generateUrl('fos_user_registration_check_email'));

          }else{
            // register with non comfirmation
            //enable customer status
            $user->setEnabled(1);
            //save user data
            $userManager->updateUser($user, true);
            //set oauth token
            $scope = $this->container->getparameter('access_token_customer_scope');
            $util->setAccessToken($email, $plainpass, $scope);
            return $this->redirect($this->generateUrl('customer_register_complete'));
          }
        }
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':customer_register.html.twig', array('form'=>$form->createview()));
        */
    }

    public function customer_register_completeAction(Request $request)
    {
        /*
        // $locale = $request->getLocale();
        // $translated = $this->get('translator')->trans('customer.registration');

        $session = $request->getSession();
        $flashBag = $this->get('session')->getFlashBag();
        foreach ($session->getFlashBag()->get('login_at_checkout', array()) as $val){
          if($val){
            //change flash login_at_checkout to register_at_checkout
            $flashBag->add('register_at_checkout', true);
          }
        }
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_main').':customer_register_complete.html.twig', array());
        */
    }

    /*
    protected function getFormErrorMessage($form)
    {
        $errors = array();
        if($form){
            foreach ($form as $fieldName => $formField) {
                foreach ($formField->getErrors(true) as $error) {
                    $errors[$fieldName] = $error->getMessage();
                }
            }
        }
        return $errors;
    }
    */

    protected function sendmail($urls,$subject,$data)
    {
        $em = $this->getDoctrine()->getManager()->getRepository(SettingOption::class);
        //Recipients
        $setting_option_email = $em->findOneByOptionName('contact_mail_address');
        $arr_contact_mail_address = explode(",", $setting_option_email->getOptionValue());
        //Sender name
        $setting_option_name = $em->findOneByOptionName('contact_mail_name');
        $contact_mail_name = $setting_option_name->getOptionValue();
        //Default email
        $sender_mail_address = $this->container->getParameter('default_sender_mail_address') ;

        $message = (new \Swift_Message($subject))
        ->setFrom(array($sender_mail_address => $contact_mail_name))
        ->setTo($arr_contact_mail_address)
        ->setBody(
            $this->renderView(
                'ProjectBundle:'.$this->container->getParameter('view_main').':_email_contact.html.twig',
                array('urls'=> $urls,'subject'=>$subject,'data'=>$data)
            ),
            'text/html'
        );

        try{
            $this->get('mailer')->send($message);
            $response['success'] = true;
            $response['message'] = $this->get('translator')->trans('contact.send.thank');
        }catch(\Exception $e){
            #Do nothing
            $response['success'] = false;
            $response['message'] = $this->get('translator')->trans('contact.cannot.send');
        }

        return $response;
    }

}
