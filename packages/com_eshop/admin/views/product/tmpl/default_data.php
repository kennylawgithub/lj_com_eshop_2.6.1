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

$rootUri = JUri::root();
?>
<div class="span12">
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo JText::_('ESHOP_PRODUCT_SKU'); ?>
		</div>
		<div class="controls">
			<input class="input-medium" type="text" name="product_sku" id="product_sku" size="" maxlength="250" value="<?php echo $this->item->product_sku; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_MANUFACTURER'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['manufacturer']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_IMAGE'); ?>
		</div>
		<div class="controls">
			<input type="file" class="input-large" accept="image/*" name="product_image" />
			<?php
			if (JFile::exists(JPATH_ROOT.'/media/com_eshop/products/'.$this->item->product_image))
			{
				$viewImage = JFile::stripExt($this->item->product_image).'-100x100.'.JFile::getExt($this->item->product_image);

				if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/products/resized/'.$viewImage))
				{
				?>
					<img src="<?php echo $rootUri.'media/com_eshop/products/resized/'.$viewImage; ?>" />
				<?php
				}
				else
				{
				?>
					<img src="<?php echo $rootUri.'media/com_eshop/products/'.$this->item->product_image; ?>" height="100" />
				<?php
				}
				?>
				<input type="checkbox" name="remove_image" value="1" />
				<?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
				<?php
			}
			?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_MAIN_CATEGORY'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['main_category_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_ADDITIONAL_CATEGORIES'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['category_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_PRICE'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_price" id="product_price" size="" maxlength="250" value="<?php echo $this->item->product_price; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_CALL_FOR_PRICE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_call_for_price']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_LENGTH'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_length" id="product_length" size="" maxlength="250" value="<?php echo $this->item->product_length; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_WIDTH'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_width" id="product_width" size="" maxlength="250" value="<?php echo $this->item->product_width; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_HEIGHT'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_height" id="product_height" size="" maxlength="250" value="<?php echo $this->item->product_height; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_LENGTH_UNIT'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_length_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_WEIGHT'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_weight" id="product_weight" size="" maxlength="250" value="<?php echo $this->item->product_weight; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_WEIGHT_UNIT'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_weight_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_TAX'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['taxclasses']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_QUANTITY'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_quantity" id="product_quantity" size="" maxlength="250" value="<?php echo $this->item->product_quantity; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_THRESHOLD'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_threshold" id="product_threshold" size="" maxlength="250" value="<?php echo $this->item->product_threshold; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_STOCK_CHECKOUT'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_stock_checkout']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_MINIMUM_QUANTITY'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_minimum_quantity" id="product_minimum_quantity" size="" maxlength="250" value="<?php echo $this->item->product_minimum_quantity; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_MAXIMUM_QUANTITY'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_maximum_quantity" id="product_maximum_quantity" size="" maxlength="250" value="<?php echo $this->item->product_maximum_quantity; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_DOWNLOADS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_downloads']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_REQUIRE_SHIPPING'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_shipping']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_SHIPPING_COST'); ?>
			<span class="help"><?php echo JText::_('ESHOP_PRODUCT_SHIPPING_COST_HELP'); ?></span>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="product_shipping_cost" id="product_shipping_cost" size="" maxlength="250" value="<?php echo $this->item->product_shipping_cost; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_SHIPPING_COST_GEOZONES'); ?>
			<span class="help"><?php echo JText::_('ESHOP_PRODUCT_SHIPPING_COST_GEOZONES_HELP'); ?></span>
		</div>
		<div class="controls">
			<input class="input-xlarge" type="text" name="product_shipping_cost_geozones" id="product_shipping_cost_geozones" size="" maxlength="250" value="<?php echo $this->item->product_shipping_cost_geozones; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_RELATED_PRODUCTS'); ?>
			<span class="help"><?php echo JText::_('ESHOP_RELATED_PRODUCTS_HELP'); ?></span>
		</div>
		<div class="controls">
			<?php echo $this->lists['related_products']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_RELATE_PRODUCT_TO_CATEGORY'); ?>
			<span class="help"><?php echo JText::_('ESHOP_RELATE_PRODUCT_TO_CATEGORY_HELP'); ?></span>
		</div>
		<div class="controls">
			<?php echo $this->lists['relate_product_to_category']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_AVAILABLE_DATE'); ?>
		</div>
		<div class="controls">
			<?php echo JHtml::_('calendar', (($this->item->product_available_date == $this->nullDate) || !$this->item->product_available_date) ? '' : JHtml::_('date', $this->item->product_available_date, 'Y-m-d', null), 'product_available_date', 'product_available_date', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_FEATURED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['featured']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_CUSTOMERGROUPS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_customergroups']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_OUT_OF_STOCK_STATUS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_stock_status_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_CART_MODE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_cart_mode']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT_QUOTE_MODE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_quote_mode']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('product_languages', JText::_('ESHOP_PRODUCT_LANGUAGES'), JText::_('ESHOP_PRODUCT_LANGUAGES_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_languages']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_PRODUCT_TAGS'); ?>
		</div>
		<div class="controls">
			<input class="inputbox" type="text" name="product_tags" id="product_tags" size="50" value="<?php echo $this->item->product_tags; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PUBLISHED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['published']; ?>
		</div>
	</div>
</div>
