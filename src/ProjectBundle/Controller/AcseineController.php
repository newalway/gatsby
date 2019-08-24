<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AcseineController extends Controller
{
	public function indexAction(Request $request)
    {

        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_main').':under_construction.html.twig', array(

        ));
    }
}
