<?php

namespace ProjectBundle\Twig;
use ProjectBundle\Utils\Collections;
use ProjectBundle\Utils\Products;

class AppExtension extends \Twig_Extension
{
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('getCustomerPlan', [$this, 'getCustomerPlan']),
			new \Twig_SimpleFunction('getPriceData', [$this, 'getPriceData']),
			new \Twig_SimpleFunction('getTrackingURL', [$this, 'getTrackingURL']),
		);
	}

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('slug', array($this, 'slugFilter')),
			new \Twig_SimpleFilter('gender', array($this, 'genderFilter')),
			new \Twig_SimpleFilter('status', array($this, 'statusFilter')),
			new \Twig_SimpleFilter('statusAvailable', array($this, 'statusAvailableFilter')),
			new \Twig_SimpleFilter('couponStatusText', array($this, 'couponStatusText')),
			new \Twig_SimpleFilter('getPercentProductDiscount', array($this, 'getPercentProductDiscount')),
			new \Twig_SimpleFilter('paymentstatus', array($this, 'paymentstatusFilter')),
			new \Twig_SimpleFilter('gender', array($this, 'templateFilter')),

		);
	}

	public function slugFilter($slug)
	{
		// Remove HTML tags
		$slug = preg_replace('/<(.*?)>/u', '', $slug);
		// Remove inner-word punctuation.
		$slug = preg_replace('/[\'"‘’“”]/u', '', $slug);
		// Make it lowercase
		$slug = mb_strtolower($slug, 'UTF-8');
		// replace space
		$slug = preg_replace(array('/\s{2,}/', '/[\t\n]/', '/\s+/'), '-', $slug);
		$slug = preg_replace('/[.,]/', '', $slug);
		return $slug;
	}

	public function getCustomerPlan($member)
	{
		return Collections::getCustomerPlan($member);
	}

	public function genderFilter($gender)
	{
		return Collections::wordGender($gender);
	}
	
	public function templateFilter($template)
	{
		return Collections::wordTemplate($template);
	}

	public function statusFilter($status)
	{
		return Collections::wordStatus($status);
	}

	public function statusAvailableFilter($product)
	{
		return Collections::wordStatusAvailable($product);
	}

	public function paymentstatusFilter($status)
	{
		return Collections::wordPaymentStatus($status);
	}

	public function couponStatusText($coupon)
	{
		return Collections::couponStatusText($coupon);
	}

	public function getPriceData($rs)
	{
		return Products::getPriceData($rs);
	}

	public function getPercentProductDiscount($rs)
	{
		return Products::getPercentProductDiscount($rs);
	}

	public function getTrackingURL($tracking_url, $tracking_number)
	{
		return Collections::getTrackingURL($tracking_url, $tracking_number);
	}

	public function getName()
	{
		return 'app_extension';
	}

}
