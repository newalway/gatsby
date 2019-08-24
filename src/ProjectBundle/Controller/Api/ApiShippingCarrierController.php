<?php

namespace ProjectBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Doctrine\ORM\EntityManagerInterface;
use ProjectBundle\Entity\ShippingCarrier;
use ProjectBundle\Entity\CustomerOrder;
use ProjectBundle\Entity\TrackingNumber;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ProjectBundle\Form\Type\Product\AddToCartType;
use ProjectBundle\Form\Type\Cart\ApplyCouponType;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use ProjectBundle\Utils\Products;

class ApiShippingCarrierController extends FOSRestController
{
	/**
	* List all data.
	*
	* @ApiDoc(
	*  resource=true,
	*  description="List all data",
	*   statusCodes = {
	*     200 = "Returned when successful"
	*   }
	* )
	*
	* @Annotations\View()
	*
	* @param Request               $request      the request object
	*
	* @return array
	*/
	public function getShippingCarriersAction(Request $request)
	{
		$arr_data = $this->getDoctrine()->getRepository(ShippingCarrier::class)->findAllData()
			->getQuery()
			->getArrayResult();

        $response = new JsonResponse();
        $response->setEncodingOptions(JSON_NUMERIC_CHECK);
        $response->setData(array(
            'shipping_carriers'  => $arr_data,
            'time' => date('Y/m/d H:i:s')
        ));
        return $response;
	}

	/**
	* List all data.
	*
	* @ApiDoc(
	*  resource=true,
	*  description="List all shipping and tracking_number",
	*   statusCodes = {
	*     200 = "Returned when successful"
	*   }
	* )
	*
	* @Annotations\View()
	*
	* @param Request               $request      the request object
	*
	* @return array
	*/
	public function getShippingCarriersAndTrackingNumbersAction(Request $request)
	{
		$order_id = $request->get('order_id');
		$arr_data = $this->getDoctrine()->getRepository(ShippingCarrier::class)->findSelectAllData()->getQuery()->getArrayResult();

		$arr_tracking_numbers = array();
		$order = $this->getDoctrine()->getRepository(CustomerOrder::class)->find($order_id);
		if($order){
			$arr_tracking_numbers = $this->getDoctrine()->getRepository(TrackingNumber::class)->findSelectDataByOrder($order)->getQuery()->getArrayResult();
		}

        $response = new JsonResponse();
        $response->setEncodingOptions(JSON_NUMERIC_CHECK);
        $response->setData(array(
			'shipping_carriers'  => $arr_data,
			'tracking_numbers' => $arr_tracking_numbers,
            'time' => date('Y/m/d H:i:s')
        ));
        return $response;
	}
}
