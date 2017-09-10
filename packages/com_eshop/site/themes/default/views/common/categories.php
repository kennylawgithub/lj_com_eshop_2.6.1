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
$span = intval(12 / $categoriesPerRow);
?>
<div class="row-fluid">
	<?php
	$count = 0;
	foreach ($categories as $category) 
	{
		$categoryUrl = JRoute::_(EshopRoute::getCategoryRoute($category->id));
		?>
		<div class="span<?php echo $span; ?>">
			<div class="eshop-category-wrap">
				<div class="image">
					<a href="<?php echo $categoryUrl; ?>" title="<?php echo $category->category_page_title != '' ? $category->category_page_title : $category->category_name; ?>">
						<img src="<?php echo $category->image; ?>" alt="<?php echo $category->category_page_title != '' ? $category->category_page_title : $category->category_name; ?>" />	            
					</a>
	            </div>
				<div class="eshop-info-block">
					<h5>
						<a href="<?php echo $categoryUrl; ?>" title="<?php echo $category->category_page_title != '' ? $category->category_page_title : $category->category_name; ?>">
							<?php echo $category->category_name; ?>
						</a>
					</h5>
				</div>
			</div>
		</div>
		<?php
		$count++;
		if ($count % $categoriesPerRow == 0 && $count < count($categories))
		{
		?>
			</div><div class="row-fluid">
		<?php
		}
	}
	?>
</div>