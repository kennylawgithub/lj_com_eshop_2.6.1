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
?>
<h1><?php echo $this->manufacturer->manufacturer_page_heading != '' ? $this->manufacturer->manufacturer_page_heading : $this->manufacturer->manufacturer_name; ?></h1>
<div class="row-fluid">
	<div class="span4">
		<img src="<?php echo $this->manufacturer->image; ?>" title="<?php echo $this->manufacturer->manufacturer_page_title != '' ? $this->manufacturer->manufacturer_page_title : $this->manufacturer->manufacturer_name; ?>" alt="<?php echo $this->manufacturer->manufacturer_page_title != '' ? $this->manufacturer->manufacturer_page_title : $this->manufacturer->manufacturer_name; ?>" />
	</div>
	<div class="span8"><?php echo $this->manufacturer->manufacturer_desc; ?></div>		
</div>
<?php
if (count($this->products))
{
	?>
	<div class="eshop-products-list">
		<?php
		echo EshopHtmlHelper::loadCommonLayout ('common/products.php', array (
			'products' => $this->products,
			'pagination' => $this->pagination,
			'sort_options' => $this->sort_options,
			'tax' => $this->tax,
			'currency' => $this->currency,
			'productsPerRow' => $this->productsPerRow,
			'catId' => 0,
			'actionUrl' => $this->actionUrl,
			'showSortOptions' => true
		));
		?>
	</div>
	<?php
}