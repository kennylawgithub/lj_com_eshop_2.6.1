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
defined ( '_JEXEC' ) or die ();
//Display Shop Instroduction
if (EshopHelper::getMessageValue('shop_introduction') != '' && EshopHelper::getConfigValue('introduction_display_on', 'front_page') == 'front_page')
{
	?>
	<div class="eshop-shop-introduction"><?php echo EshopHelper::getMessageValue('shop_introduction'); ?></div>
	<?php
}
if (count($this->categories)) 
{
	?>
	<div class="eshop-categories-list">
		<?php echo EshopHtmlHelper::loadCommonLayout('common/categories.php', array ('categories' => $this->categories, 'categoriesPerRow' => $this->categoriesPerRow)); ?>
	</div>
	<hr />	
	<?php
}
if (count($this->products))
{
	?>
	<div class="eshop-products-list">
		<?php
		echo EshopHtmlHelper::loadCommonLayout('common/products.php', array(
			'products' => $this->products,
			'tax' => $this->tax,
			'currency' => $this->currency,
			'productsPerRow' => $this->productsPerRow,
			'catId' => 0,
			'showSortOptions' => false
		));
		?>
	</div>
	<?php
}