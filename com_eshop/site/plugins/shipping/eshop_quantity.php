<?php
/**
 * @version		2.6.2
 * @package		Joomla
 * @subpackage	EShop - Quantity Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_quantity extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_quantity');
		parent::__construct();
	}
	
	/**
	 * 
	 * Function tet get quote for quantity shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		//Check geozone condition
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if (!$params->get('geozone_id'))
		{
			$status = true;
		}
		else
		{
			$query->select('COUNT(*)')
				->from('#__eshop_geozonezones')
				->where('geozone_id = ' . intval($params->get('geozone_id')))
				->where('country_id = ' . intval($addressData['country_id']))
				->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
			
			//Check geozone postcode status
			if ($status)
			{
				$gzpStatus = EshopHelper::getGzpStatus($params->get('geozone_id'), $addressData['postcode']);
			
				if (!$gzpStatus)
				{
					$status = false;
				}
			}
		}
		//Check customer groups
		$customerGroups = $params->get('customer_groups');
		if (count($customerGroups))
		{
			$user = JFactory::getUser();
			if ($user->get('id'))
			{
				$customer = new EshopCustomer();
				$customerGroupId = $customer->getCustomerGroupId();
			}
			else
			{
				$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
			}
			if (!$customerGroupId)
				$customerGroupId = 0;
			if (!in_array($customerGroupId, $customerGroups))
			{
				$status = false;
			}
		}
		$cart = new EshopCart();
		$total = $cart->getTotal();
		$minTotal = $params->get('min_total', 0);
		if ($minTotal > 0 && $total >= $minTotal)
		{
			$status = false;
		}
		$methodData = array();
		if($status)
		{
			$currency = new EshopCurrency();
			$tax = new EshopTax(EshopHelper::getConfig());
			$rates = explode("|", $params->get('rates'));
			$quantity = $cart->countProducts();
			for ($i = 0; $n = count($rates), $i < $n; $i++)
			{
				$data = explode(";", $rates[$i]);
				if (isset($data[0]) && $data[0] >= $quantity)
				{
					if (isset($data[1]))
					{
						$cost = $data[1];
						break;
					}
				}
			}
			if (isset($cost) && $cost > 0)
			{
				$packageFee = $params->get('package_fee', 0);
				$cost = $cost + $packageFee;
				
				$query->clear();
				$query->select('*')
					->from('#__eshop_shippings')
					->where('name = "eshop_quantity"');
				$db->setQuery($query);
				$row = $db->loadObject();
				$quoteData['quantity'] = array(
						'name'			=> 'eshop_quantity.quantity',
						'title'			=> JText::_('PLG_ESHOP_QUANTITY_DESC'),
						'cost'			=> $cost,
						'taxclass_id' 	=> $params->get('taxclass_id'),
						'text'			=> $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax'))));
				
				$methodData = array(
						'name'		=> 'eshop_quantity',
						'title'		=> JText::_('PLG_ESHOP_QUANTITY_TITLE'),
						'quote'		=> $quoteData,
						'ordering'	=> $row->ordering,
						'error'		=> false);
			}
		}
		return $methodData;
	}
}