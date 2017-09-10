<?php
/**
 * @version        2.6.2
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class EshopCustomer
{

	/**
	 *
	 * Function to get customer group id
	 */
	public function getCustomerGroupId()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('customergroup_id')
			->from('#__eshop_customers')
			->where('customer_id = ' . intval(JFactory::getUser()->get('id')));
		$db->setQuery($query);

		if ($db->loadResult())
		{
			$customerGroupId = $db->loadResult();
		}
		else
		{
			$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
		}

		return $customerGroupId;
	}
}