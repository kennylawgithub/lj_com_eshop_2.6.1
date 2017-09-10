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
defined( '_JEXEC' ) or die();
$elementsArr = array('image', 'short_description', 'category', 'manufacturer', 'price', 'availability', 'quantity_box', 'actions');
$countElements = 0;
foreach ($elementsArr as $element)
{
	if (EshopHelper::getConfigValue('table_show_' . $element, 1))
	{
		$countElements++;
	}
}
if ($countElements > 0)
{
	$columnWidth = intval(80 / $countElements);
	$productWidth = 100 - $columnWidth * $countElements;
}
else 
{
	$columnWidth = 0;
	$productWidth = 100;
}
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<table class="table table-striped table-responsive" id="eshop-list">
	<thead>
		<tr>
			<th width="<?php echo $productWidth; ?>%" class="nowrap">
				<?php echo JText::_('ESHOP_HEADER_PRODUCT'); ?>
			</th>
			<?php
			if (EshopHelper::getConfigValue('table_show_image', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_IMAGE'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_short_description', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_SHORT_DESCRIPTION'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_category', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_CATEGORY'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_manufacturer', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_MANUFACTURER'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_price', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_PRICE'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_availability', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_AVAILABILITY'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_quantity_box', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_QUANTITY'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_actions', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap"></th>
				<?php
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 0;
		foreach ($products as $product)
		{
			$productUrl = JRoute::_(EshopRoute::getProductRoute($product->id, ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id)));
			?>
			<tr class="row<?php echo $count % 2; ?>">
				<td>
					<a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?></a>
				</td>
				<?php
				if (EshopHelper::getConfigValue('table_show_image', 1))
				{
					?>
					<td>
						<div class="eshop-image-block">
							<div class="image img-polaroid">
								<a href="<?php echo $productUrl; ?>" class="product-image">
									<?php
									if (count($product->labels))
									{
										for ($i = 0; $n = count($product->labels), $i < $n; $i++)
										{
											$label = $product->labels[$i];
											if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
											{
												?>
												<div class="cut-rotated">
												<?php
											}
											if ($label->enable_image && $label->label_image)
											{
												$imageWidth = $label->label_image_width > 0 ? $label->label_image_width : EshopHelper::getConfigValue('label_image_width');
												if (!$imageWidth)
													$imageWidth = 50;
												$imageHeight = $label->label_image_height > 0 ? $label->label_image_height : EshopHelper::getConfigValue('label_image_height');
												if (!$imageHeight)
													$imageHeight = 50;
												?>
												<span class="horizontal <?php echo $label->label_position; ?> small-db" style="opacity: <?php echo $label->label_opacity; ?>;<?php echo 'background-image: url(' . $label->label_image . ')'; ?>; background-repeat: no-repeat; width: <?php echo $imageWidth; ?>px; height: <?php echo $imageHeight; ?>px; box-shadow: none;"></span>
												<?php
											}
											else 
											{
												?>
												<span class="<?php echo $label->label_style; ?> <?php echo $label->label_position; ?> small-db" style="background-color: <?php echo '#'.$label->label_background_color; ?>; color: <?php echo '#'.$label->label_foreground_color; ?>; opacity: <?php echo $label->label_opacity; ?>;<?php if ($label->label_bold) echo 'font-weight: bold;'; ?>">
													<?php echo $label->label_name; ?>
												</span>
												<?php
											}
											if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
											{
												?>
												</div>
												<?php
											}
										}
									}
									?>
									<img src="<?php echo $product->image; ?>" title="<?php echo $product->product_page_title != '' ? $product->product_page_title : $product->product_name; ?>" alt="<?php echo $product->product_page_title != '' ? $product->product_page_title : $product->product_name; ?>" />
								</a>
							</div>
						</div>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_short_description', 1))
				{
					?>
					<td><?php echo $product->product_short_desc; ?></td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_category', 1))
				{
					?>
					<td>
						<?php
						$categoryId = ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id);
						$category = EshopHelper::getCategory($categoryId);
						$categoryUrl = EshopRoute::getCategoryRoute($categoryId);
						?>
						<a href="<?php echo $categoryUrl; ?>" title="<?php echo $category->category_name; ?>"><?php echo $category->category_name; ?></a>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_manufacturer', 1))
				{
					?>
					<td>
						<?php
						$manufacturer = EshopHelper::getManufacturer($product->manufacturer_id);
						$manufacturerUrl = EshopRoute::getManufacturerRoute($manufacturer->id);
						?>
						<a href="<?php echo $manufacturerUrl?>" title="<?php echo $manufacturer->manufacturer_name; ?>"><?php echo $manufacturer->manufacturer_name; ?></a>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_price', 1))
				{
					?>
					<td>
						<div class="eshop-product-price">
							<?php
							if (EshopHelper::showPrice() && !$product->product_call_for_price)
							{
								?>
								<p>
									<?php
									$productPriceArray = EshopHelper::getProductPriceArray($product->id, $product->product_price);
									if ($productPriceArray['salePrice'])
									{
										?>
										<span class="eshop-base-price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
										<span class="eshop-sale-price"><?php echo $currency->format($tax->calculate($productPriceArray['salePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
										<?php
									}
									else
									{
										?>
										<span class="price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
										<?php
									}
									if (EshopHelper::getConfigValue('tax') && EshopHelper::getConfigValue('display_ex_tax'))
									{
										?>
										<small>
											<?php echo JText::_('ESHOP_EX_TAX'); ?>:
											<?php
											if ($productPriceArray['salePrice'])
											{
												echo $currency->format($productPriceArray['salePrice']);
											}
											else
											{
												echo $currency->format($productPriceArray['basePrice']);
											}
											?>
										</small>
									<?php
									}
									?>
								</p>
								<?php
							}
							if ($product->product_call_for_price)
							{
								?>
								<p><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></p>
								<?php
							}
							?>
						</div>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_availability', 1))
				{
					?>
					<td><?php echo $product->availability; ?></td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_quantity_box', 1))
				{
					?>
					<td>
						<div class="input-append input-prepend">
							<span class="eshop-quantity">
								<a class="btn btn-default button-minus" id="<?php echo $product->id; ?>" data="down">-</a>
								<input type="text" class="eshop-quantity-value" id="quantity_<?php echo $product->id; ?>" name="quantity" value="1" />
								<a class="btn btn-default button-plus" id="<?php echo $product->id; ?>" data="up">+</a>
							</span>
						</div>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_actions', 1))
				{
					?>
					<td>
						<div class="eshop-buttons">                            
							<?php 
							if (EshopHelper::isCartMode($product) || EshopHelper::isQuoteMode($product))
							{
								?>
								<div class="eshop-cart-area">
									<?php
									if (EshopHelper::isCartMode($product))
									{
										?>
										<input id="add-to-cart-<?php echo $product->id; ?>" type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" />
										<?php
									}
									if (EshopHelper::isQuoteMode($product))
									{
										?>
										<input id="add-to-quote-<?php echo $product->id; ?>" type="button" class="btn btn-primary" onclick="addToQuote(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_QUOTE'); ?>" />
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</div>
					</td>
					<?php
				}
				?>
			</tr>
			<?php
			$count++;
		}
		?>
	</tbody>
</table>
<?php	
if (isset($pagination) && ($pagination->total > $pagination->limit))
{
	?>
	<div class="row-fluid">
		<div class="pagination">
			<?php echo $pagination->getPagesLinks(); ?>
		</div>
	</div>
	<?php
}
?>
<div id="eshop-filter-categories-result" style="display: none;">
	<div class="panel-body">
		<ul>
			<?php
			if (!empty($category))
			{
			?>
				<li>
					<a class="eshop-filter-selected-category" href="javascript:eshop_filter_by_category(<?php echo $category->category_parent_id; ?>);"><strong><?php echo $category->category_name; ?></strong></a>
				</li>
			<?php
			}

			for ($i = 0; $n = count($categories), $i < $n; $i++)
			{
				$category = $categories[$i];
				if ($category->number_products > 0)
				{
					?>
					<li>
						<a href="javascript:eshop_filter_by_category(<?php echo $category->id; ?>);"><?php echo $category->category_name; ?><span class="badge badge-info"><?php echo $category->number_products;?></span></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</div>
</div>
<?php

if (count($manufacturers) > 0)
{
?>
	<div id="eshop-filter-manufacturers-result" style="display: none;">
		<div class="panel-body">
			<ul>
				<?php
				if (!empty($filterData['manufacturer_ids']))
				{
					$manufacturerIds = $filterData['manufacturer_ids'];
					$hasSelected = true;
				}
				else
				{
					$manufacturerIds = array();
					$hasSelected = false;
				}

				foreach ($manufacturers as $manufacturer)
				{
					$checked = in_array($manufacturer->id, $manufacturerIds);
				?>
					<li>
						<label class="checkbox">
							<input class="manufacturer" onclick="eshop_ajax_products_filter('manufacturer');" type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer->manufacturer_id; ?>" <?php if ($checked) echo 'checked="checked"'; ?>>
							<?php echo $manufacturer->manufacturer_name; ?>
							<span class="badge badge-info">
								<?php
									if ($hasSelected && !$checked)
									{
										echo '+';
									}

									echo $manufacturer->number_products;
								?>
							</span>
						</label>
					</li>
				<?php
				}
				?>
			</ul>
		</div>
	</div>
<?php
}

foreach ($attributes as $attribute)
{
	if (count($attribute->attributeValues))
	{

		if (!empty($filterData['attribute_' . $attribute->id]))
		{
			$attributeValues = $filterData['attribute_' . $attribute->id];
			$hasSelected = true;
		}
		else
		{
			$attributeValues = array();
			$hasSelected = false;
		}
		?>
		<div id="eshop-filter-attribute-<?php echo $attribute->id; ?>-result" style="display: none;">
			<div class="panel-body">
				<ul>
					<?php
					foreach ($attribute->attributeValues as $attributeValue)
					{
						$checked = in_array($attributeValue->value, $attributeValues);
						?>
						<li>
							<label class="checkbox">
								<input class="eshop-attributes" type="checkbox" name="attribute_<?php echo $attribute->id;?>[]" onclick="eshop_ajax_products_filter('attribute_<?php echo $attribute->id;?>');" value="<?php echo $attributeValue->value; ?>" <?php if ($checked) echo 'checked="checked"'; ?>/>
								<?php echo $attributeValue->value; ?>
								<span class="badge badge-info">
									<?php
										if ($hasSelected && !$checked)
										{
											echo '+';
										}
										echo $attributeValue->number_products;
									?>
								</span>
							</label>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
		<?php
	}
}

foreach ($options as $option)
{
	if (!empty($filterData['option_' . $option->id]))
	{
		$optionValues = $filterData['option_' . $option->id];
	}
	else
	{
		$optionValues = array();
	}
	if (count($option->optionValues))
	{
	?>
		<div id="eshop-filter-option-<?php echo $option->id; ?>-result" style="display: none;">
			<div class="panel-body">
				<ul>
					<li>
						<strong>
							<?php echo $option->option_name; ?>
						</strong>
						<ul>
							<?php
							foreach ($option->optionValues as $optionValue)
							{
								$checked = in_array($optionValue->id, $optionValues);
								?>
								<li>
									<label class="checkbox">
										<input class="eshop-options" type="checkbox" onclick="eshop_ajax_products_filter('option_<?php echo $option->id; ?>')" name="option_<?php echo $option->id; ?>[]" value="<?php echo $optionValue->id; ?>" <?php if ($checked) echo 'checked="checked"'; ?>/>
										<?php echo $optionValue->value; ?>
										<span class="badge badge-info"><?php echo $optionValue->number_products; ?></span>
									</label>
								</li>
								<?php
							}
							?>
						</ul>
					</li>
				</ul>
			</div>
		</div>	
	<?php
	}
}
?>
</div>