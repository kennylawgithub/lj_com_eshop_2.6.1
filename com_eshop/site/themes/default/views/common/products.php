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
$span = intval(12 / $productsPerRow);
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/layout.js" type="text/javascript"></script>
<script>
	Eshop.jQuery(function($){
		$(document).ready(function() {	
			changeLayout('<?php echo EshopHelper::getConfigValue('default_products_layout', 'list'); ?>');
		});
	});
</script>
<div id="products-list-container" class="products-list-container block list">
	<div class="sortPagiBar row-fluid clearfix">
		<div class="span3">
			<div class="btn-group hidden-phone">
				<?php
				if (EshopHelper::getConfigValue('default_products_layout') == 'grid')
				{
					?>
					<a rel="grid" href="#" class="btn"><i class="icon-th-large"></i></a>
					<a rel="list" href="#" class="btn"><i class="icon-th-list"></i></a>
					<?php
				}
				else 
				{
					?>
					<a rel="list" href="#" class="btn"><i class="icon-th-list"></i></a>
					<a rel="grid" href="#" class="btn"><i class="icon-th-large"></i></a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		if ($showSortOptions)
		{
			?>
			<div class="span9">
				<form method="post" name="adminForm" id="adminForm" action="<?php echo $actionUrl; ?>">
					<div class="clearfix">
						<div class="eshop-product-show">
							<b><?php echo JText::_('ESHOP_SHOW'); ?>: </b>
							<?php echo $pagination->getLimitBox(); ?>
						</div>
						<?php
						if ($sort_options)
						{
							?>
							<div class="eshop-product-sorting">
								<b><?php echo JText::_('ESHOP_SORTING_BY'); ?>: </b>
								<?php echo $sort_options; ?>
							</div>
							<?php
						}
	                    ?>
					</div>
				</form> 
			</div>
		<?php					
		}
		?>
	</div>
	<div id="products-list" class="row-fluid clearfix">
		<div class="clearfix">
			<?php
				$count = 0;
				foreach ($products as $product)
				{
					$productUrl = JRoute::_(EshopRoute::getProductRoute($product->id, ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id)));
					?>
					<div class="span<?php echo $span; ?> ajax-block-product spanbox clearfix">
						<div class="eshop-image-block">
							<div class="image img-polaroid">
								<a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>" class="product-image">
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
						<div class="eshop-info-block">
							<h5><a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>"><?php echo $product->product_name;?></a></h5>
							<p class="eshop-product-desc"><?php echo $product->product_short_desc;?></p>
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
						</div>
						<div class="eshop-buttons">                            
							<?php 
							if (EshopHelper::isCartMode($product) || EshopHelper::isQuoteMode($product))
							{
								?>
								<div class="eshop-cart-area">
									<?php
									if (EshopHelper::getConfigValue('show_quantity_box'))
									{
										?>
										<div class="input-append input-prepend">
											<span class="eshop-quantity">
												<a class="btn btn-default button-minus" id="<?php echo $product->id; ?>" data="down">-</a>
												<input type="text" class="eshop-quantity-value" id="quantity_<?php echo $product->id; ?>" name="quantity" value="1" />
												<a class="btn btn-default button-plus" id="<?php echo $product->id; ?>" data="up">+</a>
											</span>
										</div>
										<?php
									}
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
							if (($product->product_quantity <= 0 && EshopHelper::getConfigValue('allow_notify') && !EshopHelper::getConfigValue('stock_checkout')) || EshopHelper::getConfigValue('allow_wishlist') || EshopHelper::getConfigValue('allow_compare'))
							{
								?>
								<p>
									<?php
									if ($product->product_quantity <= 0 && EshopHelper::getConfigValue('allow_notify')  && !EshopHelper::getConfigValue('stock_checkout'))
									{
										?>
										<a class="btn button" onclick="makeNotify(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl();?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" ><?php echo JText::_('ESHOP_PRODUCT_NOTIFY');?></a>									
										<?php
									}
									if (EshopHelper::getConfigValue('allow_wishlist'))
									{
										?>
										<a class="btn button" style="cursor: pointer;" onclick="addToWishList(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?>"><?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?></a>
										<?php
									}
									if (EshopHelper::getConfigValue('allow_compare'))
									{
										?>
										<a class="btn button" style="cursor: pointer;" onclick="addToCompare(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?>"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a>
										<?php
									}
									?>
								</p>
								<?php
							}
							?>
						</div>
					</div>
					<?php
				$count++;
				if ($count % $productsPerRow == 0 && $count < count($products))
				{
					?>
					</div><div class="clearfix">
					<?php
				}
			}
			?>
		</div>
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
	</div>
</div>