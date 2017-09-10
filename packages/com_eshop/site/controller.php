<?php
/**
 * @version        2.6.2
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopController extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		//By adding this code, the system will first find the model from backend, if not exist, it will use the model class defined in the front-end
		$config['model_path'] = JPATH_ADMINISTRATOR . '/components/com_eshop/models';
		parent::__construct($config);
		$this->addModelPath(JPATH_COMPONENT . '/models', $this->model_prefix);
	}


	function search()
	{
		$currency        = new EshopCurrency();
		$currencyCode    = $currency->getCurrencyCode();
		$db              = JFactory::getDbo();
		$query           = $db->getQuery(true);
		$query->select('left_symbol, right_symbol')
			->from('#__eshop_currencies')
			->where('currency_code = ' . $db->quote($currencyCode));
		$db->setQuery($query);
		$row = $db->loadObject();
		($row->left_symbol) ? $symbol = $row->left_symbol : $symbol = $row->right_symbol;

		// Get weight unit
		$weight     = new EshopWeight();
		$weightId   = EshopHelper::getConfigValue('weight_id');
		$weightUnit = $weight->getUnit($weightId);

		//Get length unit
		$length     = new EshopLength();
		$lengthId   = EshopHelper::getConfigValue('length_id');
		$lengthUnit = $length->getUnit($lengthId);

		// Get submitted values
		$minPrice       = (float) str_replace($symbol, '', JRequest::getVar('min_price'));
		$maxPrice       = (float) str_replace($symbol, '', JRequest::getVar('max_price'));
		$minWeight      = (float) str_replace($weightUnit, '', JRequest::getVar('min_weight'));
		$maxWeight      = (float) str_replace($weightUnit, '', JRequest::getVar('max_weight'));
		$sameWeightUnit = JRequest::getVar('same_weight_unit');
		$minLength      = (float) str_replace($lengthUnit, '', JRequest::getVar('min_length'));
		$maxLength      = (float) str_replace($lengthUnit, '', JRequest::getVar('max_length'));
		$minWidth       = (float) str_replace($lengthUnit, '', JRequest::getVar('min_width'));
		$maxWidth       = (float) str_replace($lengthUnit, '', JRequest::getVar('max_width'));
		$minHeight      = (float) str_replace($lengthUnit, '', JRequest::getVar('min_height'));
		$maxHeight      = (float) str_replace($lengthUnit, '', JRequest::getVar('max_height'));
		$sameLengthUnit = JRequest::getVar('same_length_unit');
		$productInStock = JRequest::getInt('product_in_stock', 0);
		$categoryIds    = JRequest::getVar('category_ids');
		if (!$categoryIds)
		{
			$categoryIds = array();
		}
		$manufacturerIds = JRequest::getVar('manufacturer_ids');
		if (!$manufacturerIds)
		{
			$manufacturerIds = array();
		}
		$attributeIds = JRequest::getVar('attribute_ids');
		if (!$attributeIds)
		{
			$attributeIds = array();
		}
		$optionValueIds = JRequest::getVar('optionvalue_ids');
		if (!$optionValueIds)
		{
			$optionValueIds = array();
		}
		$keyword = JRequest::getString('keyword');
		
		// Build query string
		$query           = array();
		if ($minPrice > 0)
		{
			$query['min_price'] = $minPrice;
		}

		if ($maxPrice > 0)
		{
			$query['max_price'] = $maxPrice;
		}

		if ($minWeight > 0)
		{
			$query['min_weight'] = $minWeight;
		}

		if ($maxWeight)
		{
			$query['max_weight'] = $maxWeight;
		}
		
		if ($minWeight > 0 || $maxWeight > 0)
		{
			$query['same_weight_unit'] = $sameWeightUnit;
		}

		if ($minLength > 0)
		{
			$query['min_length'] = $minLength;
		}

		if ($maxLength)
		{
			$query['max_length'] = $maxLength;
		}

		if ($minWidth > 0)
		{
			$query['min_width'] = $minWidth;
		}

		if ($maxWidth)
		{
			$query['max_width'] = $maxWidth;
		}

		if ($minHeight > 0)
		{
			$query['min_height'] = $minHeight;
		}

		if ($maxHeight)
		{
			$query['max_height'] = $maxHeight;
		}
		
		if ($minLength > 0 || $maxLength > 0 || $minWidth > 0 || $maxWidth > 0 || $minHeight > 0 || $maxHeight > 0)
		{
			$query['same_length_unit'] = $sameLengthUnit;
		}

		if ($productInStock != 0)
		{
			$query['product_in_stock'] = $productInStock;
		}

		if (count($categoryIds))
		{
			$query['category_ids'] = implode(',', $categoryIds);
		}

		if (count($manufacturerIds))
		{
			$query['manufacturer_ids'] = implode(',', $manufacturerIds);
		}

		if (count($attributeIds))
		{
			$query['attribute_ids'] = implode(',', $attributeIds);
		}

		if (count($optionValueIds))
		{
			$query['optionvalue_ids'] = implode(',', $optionValueIds);
		}

		if ($keyword)
		{
			$query['keyword'] = $keyword;
		}
		$uri = JUri::getInstance();
		$uri->setQuery($query);
		$searchQuery = substr($uri->toString(array('query', 'fragment')), 1);
		$this->setRedirect(JRoute::_(EshopRoute::getViewRoute('search').'&'.$searchQuery, false));
	}
	
	/**
	 * 
	 * Function to download option file
	 */
	function downloadOptionFile()
	{
		$id = JRequest::getInt('id');
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('option_value')
			->from('#__eshop_orderoptions')
			->where('id = ' . intval($id));
		$db->setQuery($query);
		$filename = $db->loadResult();
		while (@ob_end_clean());
		EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
		$app->close(0);
	}
}