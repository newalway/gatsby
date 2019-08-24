<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use ProjectBundle\Entity\ProductTechNique;
use ProjectBundle\Form\Type\AdminProductTechNiqueType;
use ProjectBundle\Form\Type\AdminProductTechNiqueCollationType;


use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use JMS\SecurityExtraBundle\Annotation\Secure;
use GuzzleHttp\Client;

class AdminProductTechniqueController extends Controller
{
    const ROUTER_INDEX = 'admin_product_technique';
    const ROUTER_ADD = self::ROUTER_INDEX.'_new';
    const ROUTER_EDIT = self::ROUTER_INDEX.'_edit';
    const ROUTER_PREFIX = 'productTechnique';
    const ROUTER_CONTROLLER = 'AdminProductTechnique';

    protected function getQuerySearchData($arr_query_data)
    {
        $repository = $this->getDoctrine()->getRepository(ProductTechNique::class);
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


        // $form_search = $this->createForm(AdminSearchProductType::class, null, array('allow_extra_fields'=>true));
        // $form_search->handleRequest($request);


        // $arr_query_data = $request->query->get('admin_search_product');

        $query = $this->getQuerySearchData($arr_query_data);

        $paginated = $util->setPaginatedOnPagerfanta($query);

        $util->setBackToUrl();
        return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':index.html.twig', array(
            'paginated' =>$paginated,
            // 'form_search' => $form_search->createView(),
        ));
    }
    /**
     * @Secure(roles="ROLE_EDITOR")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $util = $this->container->get('utilities');
        $util->setCkAuthorized();
        $acctoken = $util->getAccessToken();

        $data = new ProductTechNique();
        $current_date = new \DateTime();
        //$data->setPublishDate($current_date); //set the default value
        $form = $this->createForm(AdminProductTechNiqueCollationType::class, $data, array('allow_extra_fields'=>true));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            foreach($data->getImage() as $data_technique)
            {
                $em->persist($data_technique);
            }

            $em->flush();

            $util->setCreateNotice();
            $redirect_uri = $util->getRedirectUriSaveBtn($form, $data, self::ROUTER_INDEX, self::ROUTER_ADD, self::ROUTER_EDIT);
            return $this->redirect($redirect_uri);
        }





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

        $data = new ProductTechNique();
        $form = $this->createForm(AdminProductTechNiqueCollationType::class, $data, array('allow_extra_fields'=>true));

        $form->handleRequest($request);
        // dump($data);
        // exit;
    if ($form->isSubmitted() && $form->isValid()) {
            //create product
            // dump($data);
            // exit;
            $em->persist($data);
            $em->flush();

            //product data
            $frm_product = $request->request->get('admin_product');

            //save product image size s,m,l
            //$product_util->saveProductImageSize($data, null);

            //save image_gallery
            //$product_util->saveProductImageGallery($data);

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
        $em = $this->getDoctrine()->getManager();
        $util = $this->container->get('utilities');
        $util->setCkAuthorized();
        $acctoken = $util->getAccessToken();

        $data = $this->getDoctrine()->getRepository(Series::class)->find($request->get('id'));
        if (!$data) { throw $this->createNotFoundException('No data found'); }

        $form = $this->createForm(AdminSeriesType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($request->get('removefileimage')==1){
                $data->removeImage();
            }
            if($request->get('removefileimageBanner')==1){
                $data->removeImageBanner();
            }
            if($request->get('removefileimageBannerMobile')==1){
                $data->removeImageBannerMobile();
            }

            $em->flush();

            $util->setUpdateNotice();
            $redirect_uri = $util->getRedirectUriSaveBtn($form, $data, self::ROUTER_INDEX, self::ROUTER_ADD, self::ROUTER_EDIT);
            return $this->redirect($redirect_uri);
        }

        return $this->render('ProjectBundle:'.self::ROUTER_CONTROLLER.':edit.html.twig', array(
            'form'=>$form->createview()
        ));
    }

    /**
    * @Secure(roles="ROLE_EDITOR")
    */
    public function deleteAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository(Series::class)->find($request->get('id'));
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
                $data = $em->getRepository(Series::class)->find($data_id);
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
    * @Secure(roles="ROLE_EDITOR")
    */
    public function sortAction(Request $request)
    {
        $util = $this->container->get('utilities');
        $arr_query_data = $util->prepare_query_data($request);
        $datas = $this->getQuerySearchData($arr_query_data)->getQuery()->getResult();
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
                $data = $em->getRepository(Series::class)->find($data_id);
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
