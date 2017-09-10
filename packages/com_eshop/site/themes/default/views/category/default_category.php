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
if (EshopHelper::getConfigValue('show_categories_nav') && (is_object($this->categoriesNavigation[0]) || is_object($this->categoriesNavigation[1])))
{
	?>
	<div class="row-fluid">
		<div class="span6 eshop-pre-nav">
			<?php
			if (is_object($this->categoriesNavigation[0]))
			{
				?>
				<a class="pull-left" href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($this->categoriesNavigation[0]->id)); ?>" title="<?php echo $this->categoriesNavigation[0]->category_page_title != '' ? $this->categoriesNavigation[0]->category_page_title : $this->categoriesNavigation[0]->category_name; ?>">
					<?php echo $this->categoriesNavigation[0]->category_name; ?>
				</a>
				<?php
			}
			?>
		</div>
		<div class="span6 eshop-next-nav">
			<?php
			if (is_object($this->categoriesNavigation[1]))
			{
				?>
				<a class="pull-right" href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($this->categoriesNavigation[1]->id)); ?>" title="<?php echo $this->categoriesNavigation[1]->category_page_title != '' ? $this->categoriesNavigation[1]->category_page_title : $this->categoriesNavigation[1]->category_name; ?>">
					<?php echo $this->categoriesNavigation[1]->category_name; ?>
				</a>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>
<h1><?php echo $this->category->category_page_heading != '' ? $this->category->category_page_heading : $this->category->category_name; ?></h1>
<?php
if (EshopHelper::getConfigValue('show_category_image') || EshopHelper::getConfigValue('show_category_desc'))
{
	?>
	<div class="row-fluid">
		<?php
		if (EshopHelper::getConfigValue('show_category_image'))
		{
			?>
			<div class="span4">
				<img class="img-polaroid" src="<?php echo $this->category->image; ?>" title="<?php echo $this->category->category_page_title != '' ? $this->category->category_page_title : $this->category->category_name; ?>" alt="<?php echo $this->category->category_page_title != '' ? $this->category->category_page_title : $this->category->category_name; ?>" />
			</div>
			<?php
		}
		if (EshopHelper::getConfigValue('show_category_desc'))
		{
			?>
			<div class="<?php echo (EshopHelper::getConfigValue('show_category_image') ? 'span8' : 'span12'); ?>"><?php echo $this->category->category_desc; ?></div>
			<?php
		}
		?>
	</div>
	<hr />
	<?php
}