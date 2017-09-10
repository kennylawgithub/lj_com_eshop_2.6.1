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
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<?php
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_MY_WISHLIST'); ?></h1><br />
<?php
if (!count($this->products))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_WISHLIST_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('ESHOP_IMAGE'); ?></th>
				<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
				<th><?php echo JText::_('ESHOP_MODEL'); ?></th>
				<th><?php echo JText::_('ESHOP_AVAILABILITY'); ?></th>
				<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
				<th><?php echo JText::_('ESHOP_ACTION'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->products as $product)
			{
				$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
				?>
				<tr>
					<td class="muted eshop-center-text">
						<a href="<?php echo $viewProductUrl; ?>">
							<img class="img-polaroid" src="<?php echo $product->image; ?>" />
						</a>
					</td>
					<td>
						<a href="<?php echo $viewProductUrl; ?>">
							<?php echo $product->product_name; ?>
						</a>
					</td>
					<td><?php echo $product->product_sku; ?></td>
					<td>
						<?php echo $product->availability; ?>
					</td>
					<td>
						<?php
						if (EshopHelper::showPrice())
						{
							if (!$product->product_call_for_price)
							{
								if ($product->sale_price)
								{
									?>
									<span class="eshop-base-price"><?php echo $product->base_price; ?></span>&nbsp;
									<span class="eshop-sale-price"><?php echo $product->sale_price; ?></span>
									<?php
								}
								else 
								{
									?>
									<span class="price"><?php echo $product->base_price; ?></span>
									<?php
								}
							}
							else
							{
								?>
								<span class="call-for-price"><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></span>
								<?php
							}
						}
						?>
					</td>
					<td>
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
						<input type="button" class="btn btn-primary" onclick="removeFromWishlist(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>');" value="<?php echo JText::_('ESHOP_REMOVE'); ?>" />
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>	
	<?php
}