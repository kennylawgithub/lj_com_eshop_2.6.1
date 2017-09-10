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
class EShopViewExports extends JViewLegacy
{

	function display($tpl = null)
	{
		$lists = array();
		//Export type
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'));
		$options[] = JHtml::_('select.option', 'products', JText::_('ESHOP_PRODUCTS'));
		$options[] = JHtml::_('select.option', 'categories', JText::_('ESHOP_CATEGORIES'));
		$options[] = JHtml::_('select.option', 'manufacturers', JText::_('ESHOP_MANUFACTURERS'));
		$options[] = JHtml::_('select.option', 'customers', JText::_('ESHOP_CUSTOMERS'));
		$options[] = JHtml::_('select.option', 'orders', JText::_('ESHOP_ORDERS'));
		$lists['export_type'] = JHtml::_('select.genericlist', $options, 'export_type', ' class="inputbox" ', 'value', 'text', JRequest::getVar('export_type'));
		
		//Export format
		$options = array();
		$options[] = JHtml::_('select.option', 'csv', JText::_('ESHOP_EXPORT_FORMAT_CSV'));
		$options[] = JHtml::_('select.option', 'xml', JText::_('ESHOP_EXPORT_FORMAT_XML'));
		$lists['export_format'] = JHtml::_('select.genericlist', $options, 'export_format', ' class="inputbox" ', 'value', 'text', JRequest::getVar('export_format'));
		
		//Language
		jimport('joomla.filesystem.folder');
		$path = JPATH_ROOT . '/language';
		$folders = JFolder::folders($path);
		$languages = array();
		foreach ($folders as $folder)
		if ($folder != 'pdf_fonts' && $folder != 'overrides')
			$languages[] = $folder;
		$options = array();
		foreach ($languages as $language)
		{
			$options[] = JHtml::_('select.option', $language, $language);
		}
		$lists['language'] = JHtml::_('select.genericlist', $options, 'language', ' class="inputbox" ', 'value', 'text', '');
		
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
		$lists['order_status_id'] = JHtml::_('select.genericlist', $options, 'order_status_id', ' class="inputbox" style="width: 150px;" ', 'value', 'text', 0);
		
		$this->lists = $lists;
		parent::display($tpl);
	}
}