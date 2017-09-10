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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopViewOrders extends EShopViewList
{
	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.orderstatus_name AS text')
			->from('#__eshop_orderstatuses AS a')
			->innerJoin('#__eshop_orderstatusdetails AS b ON (a.id = b.orderstatus_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ORDERSSTATUS_ALL'));
		$options = array_merge($options, $db->loadObjectList());
		$lists['order_status_id'] = JHtml::_('select.genericlist', $options, 'order_status_id', ' class="inputbox" style="width: 150px;" onchange="this.form.submit();"', 'value', 'text', JRequest::getInt('order_status_id'));
		$db = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$this->nullDate = $nullDate;
		$currency = new EshopCurrency();
		$this->currency = $currency;
	}
	
	/**
	 * Override Build Toolbar function, only need Delete, Edit and Download Invoice
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		$controller = EShopInflector::singularize($this->getName());
		JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName)));
		JToolBarHelper::deleteList(JText::_($this->lang_prefix . '_DELETE_' . strtoupper($this->getName()) . '_CONFIRM'), $controller . '.remove');
		JToolBarHelper::editList($controller.'.edit');
		
		if (EshopHelper::getConfigValue('invoice_enable'))
		{
			JToolBarHelper::custom($controller.'.downloadInvoice', 'print', 'print', JText::_('ESHOP_DOWNLOAD_INVOICE'), true);
		}
		
		JToolbarHelper::custom($controller.'.downloadCSV', 'download', 'download', JText::_('ESHOP_DOWNLOAD_CSV'), true);
	}
}