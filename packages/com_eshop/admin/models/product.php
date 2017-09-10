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
defined('_JEXEC') or die();

/**
 * Eshop Component Product Model
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopModelProduct extends EShopModel
{
	/**
	 * Flag to indicate that this model support event triggering
	 *
	 * @var boolean
	 */
	protected $triggerEvents = true;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	public function __construct($config)
	{
		$config['translatable']        = true;
		$config['translatable_fields'] = array(
			'product_name',
			'product_alias',
			'product_desc',
			'product_short_desc',
			'product_page_title',
			'product_page_heading',
			'tab1_title',
			'tab1_content',
			'tab2_title',
			'tab2_content',
			'tab3_title',
			'tab3_content',
			'tab4_title',
			'tab4_content',
			'tab5_title',
			'tab5_content',
			'meta_key',
			'meta_desc');
		parent::__construct($config);
	}

	/**
	 * Function to store product
	 * @see EShopModel::store()
	 */
	function store(&$data)
	{
		$imagePath = JPATH_ROOT . '/media/com_eshop/products/';
		if (JRequest::getInt('remove_image') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_products', 'id', $this->getDbo());
			$row->load($data['id']);

			if (JFile::exists($imagePath . $row->product_image))
				JFile::delete($imagePath . $row->product_image);

			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->product_image) . '-100x100.' . JFile::getExt($row->product_image)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->product_image) . '-100x100.' . JFile::getExt($row->product_image));
			$data['product_image'] = '';
		}

		// Check all of the images before uploading
		$errorUpload = '';
		// Check product main image first
		$productImage = $_FILES['product_image'];
		if ($productImage['name'])
		{
			$checkFileUpload = EshopFile::checkFileUpload($productImage);
			if (is_array($checkFileUpload))
			{
				$errorUpload = sprintf(JText::_('ESHOP_PRODUCT_MAIN_IMAGE_UPLOAD_ERROR'), implode(' / ', $checkFileUpload));
			}
		}
		// Check product additional images
		if ($errorUpload == '')
		{
			if (isset($_FILES['image']))
			{
				$image           = $_FILES['image'];
				$checkFileUpload = EshopFile::checkFileUpload($image);
				if (is_array($checkFileUpload))
				{
					$errorUpload = sprintf(JText::_('ESHOP_PRODUCT_ADDITIONAL_IMAGES_UPLOAD_ERROR'), implode(' / ', $checkFileUpload));
				}
			}
		}
		// Check product attachments
		if ($errorUpload == '')
		{
			if (isset($_FILES['attachment']))
			{
				$attachment      = $_FILES['attachment'];
				$checkFileUpload = EshopFile::checkFileUpload($attachment);
				if (is_array($checkFileUpload))
				{
					$errorUpload = sprintf(JText::_('ESHOP_PRODUCT_ATTACHMENTS_UPLOAD_ERROR'), implode(' / ', $checkFileUpload));
				}
			}
		}
		// Check product options images
		if ($errorUpload == '')
		{
			$productOptionId = JRequest::getVar('productoption_id');
			if (count($productOptionId))
			{
				for ($i = 0; $n = count($productOptionId), $i < $n; $i++)
				{
					$optionId = $productOptionId[$i];
					if (isset($_FILES['optionvalue_' . $optionId . '_image']))
					{
						$optionValueImages = $_FILES['optionvalue_' . $optionId . '_image'];
						$checkFileUpload   = EshopFile::checkFileUpload($optionValueImages);
						if (is_array($checkFileUpload))
						{
							$errorUpload = sprintf(JText::_('ESHOP_PRODUCT_OPTIONS_IMAGES_UPLOAD_ERROR'), implode(' / ', $checkFileUpload));
							break;
						}
					}
				}
			}
		}
		if ($errorUpload != '')
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage($errorUpload, 'error');
			$mainframe->redirect('index.php?option=com_eshop&task=product.edit&cid[]=' . $data['id']);
		}
		//End check images

		// Process main image first
		$productImage = $_FILES['product_image'];
		if (is_uploaded_file($productImage['tmp_name']))
		{
			if ($data['id'])
			{
				// Delete the old image
				$row = new EShopTable('#__eshop_products', 'id', $this->getDbo());
				$row->load($data['id']);
			}
			if (JFile::exists($imagePath . $productImage['name']))
			{
				$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($productImage['name']);
			}
			else
			{
				$imageFileName = JFile::makeSafe($productImage['name']);
			}
			JFile::upload($productImage['tmp_name'], $imagePath . $imageFileName);
			// Resize image
			EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/products/', 100, 100);
			$data['product_image'] = $imageFileName;
		}
		if (count($data['product_customergroups']))
		{
			$data['product_customergroups'] = implode(',', $data['product_customergroups']);
		}
		else
		{
			$data['product_customergroups'] = '';
		}
		if (count($data['product_languages']))
		{
			$data['product_languages'] = implode(',', $data['product_languages']);
		}
		else
		{
			$data['product_languages'] = '';
		}
		if (EshopHelper::getConfigValue('product_custom_fields'))
		{
			if (is_array($data['params']))
			{
				$data['custom_fields'] = json_encode($data['params']);
			}
		}
		parent::store($data);
		$languages    = EshopHelper::getLanguages();
		$translatable = JLanguageMultilang::isEnabled() && count($languages) > 1;
		$db           = $this->getDbo();
		$query        = $db->getQuery(true);
		//Store newsletter integration params
		$row = new EShopTable('#__eshop_products', 'id', $db);
		$row->load($data['id']);
		$params = new JRegistry($row->params);
		if (EshopHelper::getConfigValue('acymailing_integration') && JFile::exists(JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/helper.php'))
		{
			$params->set('acymailing_list_ids', implode(',', JRequest::getVar('acymailing_list_ids')));
		}
		if (EshopHelper::getConfigValue('mailchimp_integration') && EshopHelper::getConfigValue('api_key_mailchimp') != '')
		{
			$params->set('mailchimp_list_ids', implode(',', JRequest::getVar('mailchimp_list_ids')));
		}
		$row->params = $params->toString();
		$row->store();
		$productId = $data['id'];
		$user      = JFactory::getUser();
		//Store product categories
		$query->delete('#__eshop_productcategories')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->execute();
		$row            = new EShopTable('#__eshop_productcategories', 'id', $db);
		$mainCategoryId = $data['main_category_id'];
		if ($mainCategoryId)
		{
			$row->id            = '';
			$row->product_id    = $productId;
			$row->category_id   = $mainCategoryId;
			$row->main_category = 1;
			$row->store();
		}
		$additionalCategories = isset($data['category_id']) ? $data['category_id'] : array();
		for ($i = 0; $n = count($additionalCategories), $i < $n; $i++)
		{
			$categoryId = $additionalCategories[$i];
			if ($categoryId && $categoryId != $mainCategoryId)
			{
				$row->id            = '';
				$row->product_id    = $productId;
				$row->category_id   = $additionalCategories[$i];
				$row->main_category = 0;
				$row->store();
			}

		}
		//Store download products
		$productDownloadsId = JRequest::getVar('product_downloads_id');
		$query->clear();
		$query->delete('#__eshop_productdownloads')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->execute();
		$row = new EShopTable('#__eshop_productdownloads', 'id', $db);
		for ($i = 0; $n = count($productDownloadsId), $i < $n; $i++)
		{
			$row->id          = '';
			$row->product_id  = $productId;
			$row->download_id = $productDownloadsId[$i];
			$row->store();
		}
		//Store related products
		$relatedProductId = JRequest::getVar('related_product_id');
		$query->clear();
		$query->delete('#__eshop_productrelations')
			->where('product_id = ' . intval($productId) . ' OR related_product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->execute();
		$row = new EShopTable('#__eshop_productrelations', 'id', $db);
		for ($i = 0; $n = count($relatedProductId), $i < $n; $i++)
		{
			$row->id                 = '';
			$row->product_id         = $productId;
			$row->related_product_id = $relatedProductId[$i];
			$row->store();
			//And vice versa
			$row->id                 = '';
			$row->product_id         = $relatedProductId[$i];
			$row->related_product_id = $productId;
			$row->store();
		}
		//Relate product to category
		$input                   = JFactory::getApplication()->input;
		$relateProductToCategory = $input->get('relate_product_to_category');
		if ($relateProductToCategory)
		{
			$query->clear();
			$query->delete('#__eshop_productrelations')
				->where('product_id = ' . intval($productId))
				->where('related_product_id IN (SELECT product_id FROM #__eshop_productcategories WHERE category_id IN (SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . $productId . '))');
			$db->setQuery($query);
			$db->execute();
			$query->clear();
			$query->delete('#__eshop_productrelations')
				->where('related_product_id = ' . intval($productId))
				->where('product_id IN (SELECT product_id FROM #__eshop_productcategories WHERE category_id IN (SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . $productId . '))');
			$db->setQuery($query);
			$db->execute();
			$sql = 'INSERT INTO #__eshop_productrelations' .
				' (id, product_id, related_product_id)' .
				' SELECT DISTINCT "", ' . $productId . ', product_id FROM #__eshop_productcategories WHERE category_id IN (SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . $productId . ') AND product_id != ' . $productId;
			$db->setQuery($sql);
			$db->execute();
			$sql = 'INSERT INTO #__eshop_productrelations' .
				' (id, related_product_id, product_id)' .
				' SELECT DISTINCT "", ' . $productId . ', product_id FROM #__eshop_productcategories WHERE category_id IN (SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . $productId . ') AND product_id != ' . $productId;
			$db->setQuery($sql);
			$db->execute();
		}
		//Store product tags
		$productTags = JRequest::getString('product_tags');
		if ($productTags != '')
		{
			$productTagsArr = explode(',', $productTags);
			if (count($productTagsArr))
			{
				$tagIdArr = array();
				foreach ($productTagsArr as $tag)
				{
					$tag = trim($tag);
					$query->clear();
					$query->select('id')
						->from('#__eshop_tags')
						->where('tag_name = ' . $db->quote($tag));
					$db->setQuery($query);
					$tagId = $db->loadResult();
					if (!$tagId)
					{
						$row            = new EShopTable('#__eshop_tags', 'id', $db);
						$row->id        = '';
						$row->tag_name  = $tag;
						$row->hits      = 0;
						$row->published = 1;
						$row->store();
						$tagId = $row->id;
					}
					$tagIdArr[] = $tagId;
					$query->clear();
					$query->select('id')
						->from('#__eshop_producttags')
						->where('product_id = ' . intval($productId))
						->where('tag_id = ' . intval($tagId));
					$db->setQuery($query);
					if (!$db->loadResult())
					{
						$query->clear();
						$query->insert('#__eshop_producttags')
							->columns('id, product_id, tag_id')
							->values("'', $productId, $tagId");
						$db->setQuery($query);
						$db->execute();
					}
				}
				$query->clear();
				$query->delete('#__eshop_producttags')
					->where('product_id = ' . intval($productId))
					->where('tag_id NOT IN (' . implode(',', $tagIdArr) . ')');
				$db->setQuery($query);
				$db->execute();
			}
		}
		else
		{
			$query->clear();
			$query->delete('#__eshop_producttags')
				->where('product_id = ' . intval($productId));
			$db->setQuery($query);
			$db->execute();
		}
		//Store product attributes
		$attributeId        = JRequest::getVar('attribute_id');
		$productAttributeId = JRequest::getVar('productattribute_id');
		$attributePublished = JRequest::getVar('attribute_published');
		//Delete in product attributes
		$query->clear();
		$query->delete('#__eshop_productattributes')
			->where('product_id = ' . intval($productId));
		if (count($productAttributeId))
		{
			$query->where('id NOT IN (' . implode($productAttributeId, ',') . ')');
		}
		$db->setQuery($query);
		$db->execute();
		//Delete in product attribute details
		$query->clear();
		$query->delete('#__eshop_productattributedetails')
			->where('product_id = ' . intval($productId));
		if (count($productAttributeId))
		{
			$query->where('productattribute_id NOT IN (' . implode($productAttributeId, ',') . ')');
		}
		$db->setQuery($query);
		$db->execute();
		if ($translatable)
		{
			for ($i = 0; $n = count($attributePublished), $i < $n; $i++)
			{
				$row               = new EShopTable('#__eshop_productattributes', 'id', $db);
				$row->id           = isset($productAttributeId[$i]) ? $productAttributeId[$i] : '';
				$row->product_id   = $productId;
				$row->attribute_id = $attributeId[$i];
				$row->published    = $attributePublished[$i];
				$row->store();
				foreach ($languages as $language)
				{
					$langCode                        = $language->lang_code;
					$productAttributeDetailsId       = JRequest::getVar('productattributedetails_id_' . $langCode);
					$attributeValue                  = JRequest::getVar('attribute_value_' . $langCode);
					$detailsRow                      = new EShopTable('#__eshop_productattributedetails', 'id', $db);
					$detailsRow->id                  = isset($productAttributeDetailsId[$i]) ? $productAttributeDetailsId[$i] : '';
					$detailsRow->productattribute_id = $row->id;
					$detailsRow->product_id          = $productId;
					$detailsRow->value               = $attributeValue[$i];
					$detailsRow->language            = $langCode;
					$detailsRow->store();
				}
			}
		}
		else
		{
			$productAttributeDetailsId = JRequest::getVar('productattributedetails_id');
			$attributeValue            = JRequest::getVar('attribute_value');
			for ($i = 0; $n = count($attributePublished), $i < $n; $i++)
			{
				$row               = new EShopTable('#__eshop_productattributes', 'id', $db);
				$row->id           = isset($productAttributeId[$i]) ? $productAttributeId[$i] : '';
				$row->product_id   = $productId;
				$row->attribute_id = $attributeId[$i];
				$row->published    = $attributePublished[$i];
				$row->store();
				$detailsRow                      = new EShopTable('#__eshop_productattributedetails', 'id', $db);
				$detailsRow->id                  = isset($productAttributeDetailsId[$i]) ? $productAttributeDetailsId[$i] : '';
				$detailsRow->productattribute_id = $row->id;
				$detailsRow->product_id          = $productId;
				$detailsRow->value               = $attributeValue[$i];
				$detailsRow->language            = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
				$detailsRow->store();
			}
		}
		//Store product options
		$productOptionId = JRequest::getVar('productoption_id');
		//Delete product options
		$query->clear();
		$query->delete('#__eshop_productoptions')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->execute();
		//Delete product option values
		$query->clear();
		$query->delete('#__eshop_productoptionvalues')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->execute();
		if (count($productOptionId))
		{
			$row      = new EShopTable('#__eshop_productoptions', 'id', $db);
			$valueRow = new EShopTable('#__eshop_productoptionvalues', 'id', $db);
			for ($i = 0; $n = count($productOptionId), $i < $n; $i++)
			{
				$optionId = $productOptionId[$i];
				//Store options
				$row->id         = '';
				$row->product_id = $productId;
				$row->option_id  = $optionId;
				$row->required   = JRequest::getVar('required_' . $optionId);
				$row->store();
				//Store options values
				$optionValueId         = JRequest::getVar('optionvalue_' . $optionId . '_id');
				$optionValueSku        = JRequest::getVar('optionvalue_' . $optionId . '_sku');
				$optionValueQuantity   = JRequest::getVar('optionvalue_' . $optionId . '_quantity');
				$optionValuePriceSign  = JRequest::getVar('optionvalue_' . $optionId . '_price_sign');
				$optionValuePriceType  = JRequest::getVar('optionvalue_' . $optionId . '_price_type');
				$optionValuePrice      = JRequest::getVar('optionvalue_' . $optionId . '_price');
				$optionValueWeightSign = JRequest::getVar('optionvalue_' . $optionId . '_weight_sign');
				$optionValueWeight     = JRequest::getVar('optionvalue_' . $optionId . '_weight');
				//Upload images if available
				$optionValueImages = $_FILES['optionvalue_' . $optionId . '_image'];
				$optionValueImage  = JRequest::getVar('optionvalue_' . $optionId . '_imageold');
				if (count($optionValueImages))
				{
					$imageOptionValuePath = JPATH_ROOT . '/media/com_eshop/options/';
					foreach ($optionValueImages['name'] as $index => $value)
					{
						if (is_uploaded_file($optionValueImages['tmp_name'][$index]) && $value != '')
						{
							if (JFile::exists($imageOptionValuePath . $optionValueImages['name'][$index]))
								$imageOptionValueFileName = uniqid('image_') . '_' . JFile::makeSafe($optionValueImages['name'][$index]);
							else
								$imageOptionValueFileName = JFile::makeSafe($optionValueImages['name'][$index]);
							if (JFile::upload($optionValueImages['tmp_name'][$index], $imageOptionValuePath . $imageOptionValueFileName))
							{
								if (JFile::exists($imageOptionValuePath . $optionValueImage[$index]))
									JFile::delete($imageOptionValuePath . $optionValueImage[$index]);
								if (JFile::exists($imageOptionValuePath . 'resized/' . JFile::stripExt($optionValueImage[$index]) . '-100x100.' . JFile::getExt($optionValueImage[$index])))
									JFile::delete($imageOptionValuePath . 'resized/' . JFile::stripExt($optionValueImage[$index]) . '-100x100.' . JFile::getExt($optionValueImage[$index]));
								EshopHelper::resizeImage($imageOptionValueFileName, $imageOptionValuePath, 100, 100);
								$optionValueImage[$index] = $imageOptionValueFileName;
							}
						}
					}
				}
				for ($j = 0; $m = count($optionValueId), $j < $m; $j++)
				{
					$valueRow->id                = '';
					$valueRow->product_option_id = $row->id;
					$valueRow->product_id        = $productId;
					$valueRow->option_id         = $optionId;
					$valueRow->option_value_id   = $optionValueId[$j];
					$valueRow->sku               = $optionValueSku[$j];
					$valueRow->quantity          = $optionValueQuantity[$j];
					$valueRow->price             = $optionValuePrice[$j];
					$valueRow->price_sign        = $optionValuePriceSign[$j];
					$valueRow->price_type        = $optionValuePriceType[$j];
					$valueRow->weight            = $optionValueWeight[$j];
					$valueRow->weight_sign       = $optionValueWeightSign[$j];
					$valueRow->image             = $optionValueImage[$j];
					$valueRow->store();
				}
			}
			if (EshopHelper::getConfigValue('assign_same_options'))
			{
				//Assign options to other products
				$sameOptionsProductsId = JRequest::getVar('same_options_products_id');
				if (count($sameOptionsProductsId))
				{
					if ($sameOptionsProductsId[0] == '-1')
					{
						//Assign to all other products
						$query->clear()
							->select('id')
							->from('#__eshop_products')
							->where('published = 1')
							->where('id != ' . intval($productId));
						$db->setQuery($query);
						$productIds = $db->loadColumn();
					}
					else
					{
						//Only assign to selected products
						$productIds = $sameOptionsProductsId;
					}
					//Delete all of old assigned options for these products
					$query->clear()
						->delete('#__eshop_productoptions')
						->where('product_id IN (' . implode(',', $productIds) . ')');
					$db->setQuery($query);
					$db->execute();
					$query->clear()
						->delete('#__eshop_productoptionvalues')
						->where('product_id IN (' . implode(',', $productIds) . ')');
					$db->setQuery($query);
					$db->execute();
					//Copy data to assign
					foreach ($productIds as $id)
					{
						$sql = 'INSERT INTO #__eshop_productoptions
									(id,
									product_id,
									option_id,
									required
									)
								SELECT
									"",
									' . $id . ',
									option_id,
									required
								FROM #__eshop_productoptions
								WHERE product_id = ' . intval($productId);
						$db->setQuery($sql);
						$db->execute();
						
						$sql = 'INSERT INTO #__eshop_productoptionvalues
									(id,
									product_option_id,
									product_id,
									option_id,
									option_value_id,
									sku,
									quantity,
									price,
									price_sign,
									price_type,
									weight,
									weight_sign,
									image
									)
								SELECT
									"",
									0,
									' . $id . ',
									option_id,
									option_value_id,
									sku,
									quantity,
									price,
									price_sign,
									price_type,
									weight,
									weight_sign,
									image
								FROM #__eshop_productoptionvalues
								WHERE product_id = ' . intval($productId);
						$db->setQuery($sql);
						$db->execute();
					}
					$sql = 'UPDATE #__eshop_productoptionvalues AS pov
							INNER JOIN #__eshop_productoptions AS po ON (pov.product_id = po.product_id AND pov.option_id = po.option_id)
							SET pov.product_option_id = po.id';
					$db->setQuery($sql);
					$db->execute();
				}
			}
		}
		
		//Store product discounts
		$productDiscountId       = JRequest::getVar('productdiscount_id');
		$discountCustomerGroupId = JRequest::getVar('discount_customergroup_id');
		$discountQuantity        = JRequest::getVar('discount_quantity');
		$discountPriority        = JRequest::getVar('discount_priority');
		$discountPrice           = JRequest::getVar('discount_price');
		$discountDateStart       = JRequest::getVar('discount_date_start');
		$discountDateEnd         = JRequest::getVar('discount_date_end');
		$discountPublished       = JRequest::getVar('discount_published');
		//Remove removed discounts first
		$query->clear();
		$query->delete('#__eshop_productdiscounts')
			->where('product_id = ' . intval($productId));
		if (count($productDiscountId))
		{
			$query->where('id NOT IN (' . implode($productDiscountId, ',') . ')');
		}
		$db->setQuery($query);
		$db->execute();
		$row = new EShopTable('#__eshop_productdiscounts', 'id', $db);
		for ($i = 0; $n = count($discountCustomerGroupId), $i < $n; $i++)
		{
			$row->id               = isset($productDiscountId[$i]) ? $productDiscountId[$i] : '';
			$row->product_id       = $productId;
			$row->customergroup_id = $discountCustomerGroupId[$i];
			$row->quantity         = $discountQuantity[$i];
			$row->priority         = $discountPriority[$i];
			$row->price            = $discountPrice[$i];
			$row->date_start       = $discountDateStart[$i];
			$row->date_end         = $discountDateEnd[$i];
			$row->published        = $discountPublished[$i];
			$row->store();
		}
		//Store product specials
		$productSpecialId       = JRequest::getVar('productspecial_id');
		$specialCustomerGroupId = JRequest::getVar('special_customergroup_id');
		$specialPriority        = JRequest::getVar('special_priority');
		$specialPrice           = JRequest::getVar('special_price');
		$specialDateStart       = JRequest::getVar('special_date_start');
		$specialDateEnd         = JRequest::getVar('special_date_end');
		$specialPublished       = JRequest::getVar('special_published');
		//Remove removed specials first
		$query->clear();
		$query->delete('#__eshop_productspecials')
			->where('product_id = ' . intval($productId));
		if (count($productSpecialId))
		{
			$query->where('id NOT IN (' . implode($productSpecialId, ',') . ')');
		}
		$db->setQuery($query);
		$db->execute();
		$row = new EShopTable('#__eshop_productspecials', 'id', $db);
		for ($i = 0; $n = count($specialCustomerGroupId), $i < $n; $i++)
		{
			$row->id               = isset($productSpecialId[$i]) ? $productSpecialId[$i] : '';
			$row->product_id       = $productId;
			$row->customergroup_id = $specialCustomerGroupId[$i];
			$row->priority         = $specialPriority[$i];
			$row->price            = $specialPrice[$i];
			$row->date_start       = $specialDateStart[$i];
			$row->date_end         = $specialDateEnd[$i];
			$row->published        = $specialPublished[$i];
			$row->store();
		}
		//Images process
		//Old images
		$productImageId        = JRequest::getVar('productimage_id');
		$productImageOrdering  = JRequest::getVar('productimage_ordering');
		$productImagePublished = JRequest::getVar('productimage_published');
		// Delete image files first
		$query->clear();
		$query->select('image')
			->from('#__eshop_productimages')
			->where('product_id = ' . intval($productId));
		if (count($productImageId))
		{
			$query->where('id NOT IN (' . implode($productImageId, ',') . ')');
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		// Then delete data
		$query->clear();
		$query->delete('#__eshop_productimages')
			->where('product_id = ' . intval($productId));
		if (count($productImageId))
		{
			$query->where('id NOT IN (' . implode($productImageId, ',') . ')');
		}
		$db->setQuery($query);
		$db->execute();
		$row = new EShopTable('#__eshop_productimages', 'id', $db);
		for ($i = 0; $n = count($productImageId), $i < $n; $i++)
		{
			$row->id               = $productImageId[$i];
			$row->product_id       = $productId;
			$row->published        = $productImagePublished[$i];
			$row->ordering         = $productImageOrdering[$i];
			$row->modified_date    = date('Y-m-d H:i:s');
			$row->modified_by      = $user->get('id');
			$row->checked_out      = 0;
			$row->checked_out_time = '0000-00-00 00:00:00';
			$row->store();
		}
		// New images
		if (isset($_FILES['image']))
		{
			$image          = $_FILES['image'];
			$imageOrdering  = JRequest::getVar('image_ordering');
			$imagePublished = JRequest::getVar('image_published');
			for ($i = 0; $n = count($image['name']), $i < $n; $i++)
			{
				if (is_uploaded_file($image['tmp_name'][$i]))
				{
					if (JFile::exists($imagePath . $image['name'][$i]))
					{
						$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($image['name'][$i]);
					}
					else
					{
						$imageFileName = JFile::makeSafe($image['name'][$i]);
					}
					JFile::upload($image['tmp_name'][$i], $imagePath . $imageFileName);
					//Resize image
					EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/products/', 100, 100);

					$row->id               = '';
					$row->product_id       = $productId;
					$row->image            = $imageFileName;
					$row->published        = $imagePublished[$i];
					$row->ordering         = $imageOrdering[$i];
					$row->created_date     = date('Y-m-d H:i:s');
					$row->created_by       = $user->get('id');
					$row->modified_date    = date('Y-m-d H:i:s');
					$row->modified_by      = $user->get('id');
					$row->checked_out      = 0;
					$row->checked_out_time = '0000-00-00 00:00:00';
					$row->store();
				}
			}
		}
		//Attachments process
		//Old attachments
		$productAttachmentId        = JRequest::getVar('productattachment_id');
		$productAttachmentOrdering  = JRequest::getVar('productattachment_ordering');
		$productAttachmentPublished = JRequest::getVar('productattachment_published');
		// Delete attachments files first
		$query->clear();
		$query->select('file_name')
			->from('#__eshop_productattachments')
			->where('product_id = ' . intval($productId));
		if (count($productAttachmentId))
		{
			$query->where('id NOT IN (' . implode($productAttachmentId, ',') . ')');
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		// Then delete data
		$query->clear();
		$query->delete('#__eshop_productattachments')
			->where('product_id = ' . intval($productId));
		if (count($productAttachmentId))
		{
			$query->where('id NOT IN (' . implode($productAttachmentId, ',') . ')');
		}
		$db->setQuery($query);
		$db->execute();
		$row = new EShopTable('#__eshop_productattachments', 'id', $db);
		for ($i = 0; $n = count($productAttachmentId), $i < $n; $i++)
		{
			$row->id         = $productAttachmentId[$i];
			$row->product_id = $productId;
			$row->published  = $productAttachmentPublished[$i];
			$row->ordering   = $productAttachmentOrdering[$i];
			$row->store();
		}
		// New attachments
		$attachmentPath = JPATH_ROOT . '/media/com_eshop/attachments/';
		if (isset($_FILES['attachment']))
		{
			$attachment          = $_FILES['attachment'];
			$attachmentOrdering  = JRequest::getVar('attachment_ordering');
			$attachmentPublished = JRequest::getVar('attachment_published');
			for ($i = 0; $n = count($attachment['name']), $i < $n; $i++)
			{
				if (is_uploaded_file($attachment['tmp_name'][$i]))
				{
					if (JFile::exists($attachmentPath . JFile::makeSafe($attachment['name'][$i])))
					{
						$attachmentFileName = uniqid('attachment_') . '_' . JFile::makeSafe($attachment['name'][$i]);
					}
					else
					{
						$attachmentFileName = JFile::makeSafe($attachment['name'][$i]);
					}
					JFile::upload($attachment['tmp_name'][$i], $attachmentPath . $attachmentFileName);
					$row->id         = '';
					$row->product_id = $productId;
					$row->file_name  = $attachmentFileName;
					$row->published  = $attachmentPublished[$i];
					$row->ordering   = $attachmentOrdering[$i];
					$row->store();
				}
			}
		}

		return true;
	}

	/**
	 * Method to remove products
	 *
	 * @access    public
	 * @return boolean True on success
	 * @since     1.5
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$db    = $this->getDbo();
			$cids  = implode(',', $cid);
			$query = $db->getQuery(true);
			//Delete images of products from server
			$imageThumbWidth       = EshopHelper::getConfigValue('image_thumb_width');
			$imageThumbHeight      = EshopHelper::getConfigValue('image_thumb_height');
			$imagePopupWidth       = EshopHelper::getConfigValue('image_popup_width');
			$imagePopupHeight      = EshopHelper::getConfigValue('image_popup_height');
			$imageListWidth        = EshopHelper::getConfigValue('image_list_width');
			$imageListHeight       = EshopHelper::getConfigValue('image_list_height');
			$imageCompareWidth     = EshopHelper::getConfigValue('image_compare_width');
			$imageCompareHeight    = EshopHelper::getConfigValue('image_compare_height');
			$imageWishlistWidth    = EshopHelper::getConfigValue('image_wishlist_width');
			$imageWishlistHeight   = EshopHelper::getConfigValue('image_wishlist_height');
			$imageCartWidth        = EshopHelper::getConfigValue('image_cart_width');
			$imageCartHeight       = EshopHelper::getConfigValue('image_cart_height');
			$imageAdditionalWidth  = EshopHelper::getConfigValue('image_additional_width');
			$imageAdditionalHeight = EshopHelper::getConfigValue('image_additional_height');
			$imagePath             = JPATH_ROOT . '/media/com_eshop/products/';
			//Delete main images first
			$query->select('product_image')
				->from('#__eshop_products')
				->where('id IN (' . implode(',', $cid) . ')')
				->where('product_image != ""');
			$db->setQuery($query);
			$productImages = $db->loadColumn();
			if (count($productImages))
			{
				$imageSizesArr = array('100x100', $imageThumbWidth . 'x' . $imageThumbHeight, $imagePopupWidth . 'x' . $imagePopupHeight, $imageListWidth . 'x' . $imageListHeight, $imageCompareWidth . 'x' . $imageCompareHeight, $imageWishlistWidth . 'x' . $imageWishlistHeight, $imageCartWidth . 'x' . $imageCartHeight, $imageAdditionalWidth . 'x' . $imageAdditionalHeight);
				foreach ($productImages as $image)
				{
					//Delete orginal image
					if (JFile::exists($imagePath . $image))
					{
						JFile::delete($imagePath . $image);
					}
					//Delete resized images
					foreach ($imageSizesArr as $size)
					{
						if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($image) . '-' . $size . '.' . JFile::getExt($image)))
							JFile::delete($imagePath . 'resized/' . JFile::stripExt($image) . '-' . $size . '.' . JFile::getExt($image));
					}
				}
			}
			//Delete related images
			$query->clear();
			$query->select('image')
				->from('#__eshop_productimages')
				->where('product_id IN (' . implode(',', $cid) . ')')
				->where('image != ""');
			$db->setQuery($query);
			$images = $db->loadColumn();
			if (count($images))
			{
				$imageSizesArr = array('100x100', $imageAdditionalWidth . 'x' . $imageAdditionalHeight, $imageThumbWidth . 'x' . $imageThumbHeight, $imagePopupWidth . 'x' . $imagePopupHeight);
				foreach ($images as $image)
				{
					//Delete orginal image
					if (JFile::exists($imagePath . $image))
					{
						JFile::delete($imagePath . $image);
					}
					//Delete resized images
					foreach ($imageSizesArr as $size)
					{
						if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($image) . '-' . $size . '.' . JFile::getExt($image)))
							JFile::delete($imagePath . 'resized/' . JFile::stripExt($image) . '-' . $size . '.' . JFile::getExt($image));
					}
				}
			}
			$query->clear();
			$query->delete('#__eshop_products')
				->where('id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			// Delete details records
			$query->clear();
			$query->delete('#__eshop_productdetails')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product attributes
			$query->clear();
			$query->delete('#__eshop_productattributes')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product attribute details
			$query->clear();
			$query->delete('#__eshop_productattributedetails')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product categories
			$query->clear();
			$query->delete('#__eshop_productcategories')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product discounts
			$query->clear();
			$query->delete('#__eshop_productdiscounts')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product images
			$query->clear();
			$query->delete('#__eshop_productimages')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product options
			$query->clear();
			$query->delete('#__eshop_productoptions')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product option values
			$query->clear();
			$query->delete('#__eshop_productoptionvalues')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product relations
			$query->clear();
			$query->delete('#__eshop_productrelations')
				->where('product_id IN (' . implode(',', $cid) . ') OR related_product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product specials
			$query->clear();
			$query->delete('#__eshop_productspecials')
				->where('product_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			//Delete product labels
			$query->clear();
			$query = $db->getQuery(true);
			$query->delete('#__eshop_labelelements')
				->where('element_id IN (' . implode(',', $cid) . ')')
				->where('element_type = "product"');
			$db->setQuery($query);
			$db->execute();
			//Delete SEF urls to products
			for ($i = 0; $n = count($cid), $i < $n; $i++)
			{
				$query->clear();
				$query->delete('#__eshop_urls')
					->where('query LIKE "view=product&id=' . $cid[$i] . '&catid=%"');
				$db->setQuery($query);
				$db->execute();
			}
		}

		//Removed success
		return 1;
	}

	/**
	 *
	 * Function to featured products
	 *
	 * @param array $cid
	 *
	 * @return boolean
	 */
	function featured($cid)
	{
		if (count($cid))
		{
			$db    = $this->getDbo();
			$cids  = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->update('#__eshop_products')
				->set('product_featured = 1')
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->execute())
				return false;
		}

		return true;
	}

	/**
	 *
	 * Function to unfeatured products
	 *
	 * @param array $cid
	 *
	 * @return boolean
	 */
	function unfeatured($cid)
	{
		if (count($cid))
		{
			$db    = $this->getDbo();
			$cids  = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->update('#__eshop_products')
				->set('product_featured = 0')
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->execute())
				return false;
		}

		return true;
	}

	/**
	 * Function to copy product and related data
	 * @see EShopModel::copy()
	 */
	function copy($id)
	{
		$copiedProductId = parent::copy($id);
		$db              = $this->getDbo();
		$query           = $db->getQuery(true);
		//Categories
		$query->select('COUNT(*)')
			->from('#__eshop_productcategories')
			->where('product_id = ' . intval($id));
		$db->setQuery($query);
		if ($db->loadResult())
		{
			$sql = 'INSERT INTO #__eshop_productcategories'
				. ' (product_id, category_id, main_category)'
				. ' SELECT ' . $copiedProductId . ', category_id, main_category'		
				. ' FROM #__eshop_productcategories'
				. ' WHERE product_id = ' . intval($id);
			$db->setQuery($sql);
			$db->execute();
		}
		//Additional images
		$query->clear();
		$query->select('*')
			->from('#__eshop_productimages')
			->where('product_id = ' . intval($id));
		$db->setQuery($query);
		$additionalImages = $db->loadObjectList();
		for ($i = 0; $n = count($additionalImages), $i < $n; $i++)
		{
			$additionalImage = $additionalImages[$i];
			$oldImage        = $additionalImage->image;
			if ($additionalImage->image != '' && JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $oldImage))
			{
				$newImage = JFile::stripExt($oldImage) . ' ' . JText::_('ESHOP_COPY') . '.' . JFile::getExt($oldImage);
				if (JFile::copy(JPATH_ROOT . '/media/com_eshop/products/' . $oldImage, JPATH_ROOT . '/media/com_eshop/products/' . $newImage))
				{
					//resize copied image
					EshopHelper::resizeImage($newImage, JPATH_ROOT . '/media/com_eshop/products/', 100, 100);
				}
				else
				{
					$newImage = $oldImage;
				}
			}
			$row                   = new EShopTable('#__eshop_productimages', 'id', $db);
			$row->id               = '';
			$row->product_id       = $copiedProductId;
			$row->image            = $newImage;
			$row->published        = $additionalImage->published;
			$row->ordering         = $additionalImage->ordering;
			$row->modified_date    = $additionalImage->modified_date;
			$row->modified_by      = $additionalImage->modified_by;
			$row->checked_out      = 0;
			$row->checked_out_time = '0000-00-00 00:00:00';
			$row->store();
		}
		//Attributes
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_productattributes')
			->where('product_id = ' . intval($id));
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i = 0; $n = count($rows), $i < $n; $i++)
		{
			$row                                = $rows[$i];
			$productAttributesRow               = new EShopTable('#__eshop_productattributes', 'id', $db);
			$productAttributesRow->id           = '';
			$productAttributesRow->product_id   = $copiedProductId;
			$productAttributesRow->attribute_id = $row->attribute_id;
			$productAttributesRow->published    = $row->published;
			$productAttributesRow->store();
			$productAttributesId = $productAttributesRow->id;
			$sql                 = 'INSERT INTO #__eshop_productattributedetails'
				. ' (productattribute_id, product_id, value, language)'
				. ' SELECT ' . $productAttributesId . ', ' . $copiedProductId . ', value, language'
				. ' FROM #__eshop_productattributedetails'
				. ' WHERE productattribute_id = ' . intval($row->id);
			$db->setQuery($sql);
			$db->execute();
		}
		//Discounts
		$query->clear();
		$query->select('COUNT(*)')
			->from('#__eshop_productdiscounts')
			->where('product_id = ' . intval($id));
		$db->setQuery($query);
		if ($db->loadResult())
		{
			$sql = 'INSERT INTO #__eshop_productdiscounts'
				. ' (product_id, customergroup_id, quantity, priority, price, date_start, date_end, published)'
				. ' SELECT ' . $copiedProductId . ', customergroup_id, quantity, priority, price, date_start, date_end, published'
				. ' FROM #__eshop_productdiscounts'
				. ' WHERE product_id = ' . intval($id);
			$db->setQuery($sql);
			$db->execute();
		}
		//Specials
		$query->clear();
		$query->select('COUNT(*)')
			->from('#__eshop_productspecials')
			->where('product_id = ' . intval($id));
		$db->setQuery($query);
		if ($db->loadResult())
		{
			$sql = 'INSERT INTO #__eshop_productspecials'
				. ' (product_id, customergroup_id, priority, price, date_start, date_end, published)'
				. ' SELECT ' . $copiedProductId . ', customergroup_id, priority, price, date_start, date_end, published'
				. ' FROM #__eshop_productspecials'
				. ' WHERE product_id = ' . intval($id);
			$db->setQuery($sql);
			$db->execute();
		}
		//Options
		$query->clear();
		$query->select('*')
			->from('#__eshop_productoptions')
			->where('product_id = ' . intval($id))
			->order('id');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i = 0; $n = count($rows), $i < $n; $i++)
		{
			$row                           = $rows[$i];
			$productOptionsRow             = new EShopTable('#__eshop_productoptions', 'id', $db);
			$productOptionsRow->id         = '';
			$productOptionsRow->product_id = $copiedProductId;
			$productOptionsRow->option_id  = $row->option_id;
			$productOptionsRow->required   = $row->required;
			$productOptionsRow->store();
			$productOptionsId = $productOptionsRow->id;
			$sql              = 'INSERT INTO #__eshop_productoptionvalues'
				. ' (product_option_id, product_id, option_id, option_value_id, sku, quantity, price, price_sign, price_type, weight, weight_sign, image)'
				. ' SELECT ' . $productOptionsId . ', ' . $copiedProductId . ', option_id, option_value_id, sku, quantity, price, price_sign, price_type, weight, weight_sign, image'
				. ' FROM #__eshop_productoptionvalues'
				. ' WHERE product_option_id = ' . intval($row->id)
				. ' ORDER BY id';
			$db->setQuery($sql);
			$db->execute();
		}

		return $copiedProductId;
	}
}