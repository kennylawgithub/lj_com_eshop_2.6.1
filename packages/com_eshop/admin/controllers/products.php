<?php
/**
 * @version		2.6.2
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerProducts extends JControllerLegacy
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	
	}

	/**
	 * 
	 * Function to process batch products
	 */
	function batch()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);
		$model = $this->getModel('products');
		$ret = $model->batch($post);
		if ($ret)
		{
			$msg = JText::_('ESHOP_BATCH_PRODUCT_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('ESHOP_BATCH_PRODUCT_ERROR');
		}
		$this->setRedirect('index.php?option=com_eshop&view=products', $msg);
	}

	/**
	 * 
	 * Cancel function
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=products');
	}
}