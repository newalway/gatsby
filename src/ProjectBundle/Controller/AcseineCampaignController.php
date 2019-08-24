<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AcseineCampaignController extends Controller
{
	public function indexAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':index.html.twig', array());
    }

	public function moistbalanceAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':moistbalance.html.twig', array());
    }

	public function sunshieldAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':sunshield.html.twig', array());
    }

	public function registerAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':register.html.twig', array());
		// return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':close.html.twig', array());
    }

	public function thanksAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':thanks.html.twig', array());
    }

	public function terms_of_useAction(Request $request)
    {
        return $this->render('ProjectBundle:'.$this->container->getParameter('view_acseine_campaign').':terms_of_use.html.twig', array());
    }
}
