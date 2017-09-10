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
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/eshop.js" type="text/javascript"></script>
<h1><?php echo JText::_('ESHOP_QUOTE_CART'); ?></h1>
<?php
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
?>
<?php
if (!count($this->quoteData))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_QUOTE_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<div class="quote-info">
		<?php
		if(!EshopHelper::isMobile())
		{
			?>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="text-align: center;"><?php echo JText::_('ESHOP_REMOVE'); ?></th>
							<th style="text-align: center;"><?php echo JText::_('ESHOP_IMAGE'); ?></th>
						<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
						<th><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
						<?php
						if (EshopHelper::showPrice())
						{
							?>
							<th nowrap="nowrap"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
							<th><?php echo JText::_('ESHOP_TOTAL'); ?></th>
							<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					$totalPrice = 0;
					$countProducts = 0;
					foreach ($this->quoteData as $key => $product)
					{
						$countProducts++;
						$optionData = $product['option_data'];
						$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
						if (EshopHelper::showPrice() && !$product['product_call_for_price'])
						{
							$totalPrice += $product['total_price'];
						}
						?>
						<tr>
							<td class="eshop-center-text" style="vertical-align: middle;">
								<a class="eshop-remove-item-quote" id="<?php echo $key; ?>" style="cursor: pointer;">
									<img alt="<?php echo JText::_('ESHOP_REMOVE'); ?>" title="<?php echo JText::_('ESHOP_REMOVE'); ?>" src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/images/remove.png" />
								</a>
							</td>
							<td class="muted eshop-center-text" style="vertical-align: middle;">
								<a href="<?php echo $viewProductUrl; ?>">
									<img class="img-polaroid" src="<?php echo $product['image']; ?>" />
								</a>
							</td>
							<td style="vertical-align: middle;">
								<a href="<?php echo $viewProductUrl; ?>">
									<?php echo $product['product_name']; ?>
								</a>
								<br />	
								<?php
								for ($i = 0; $n = count($optionData), $i < $n; $i++)
								{
									echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . '<br />';
								}
								?>
							</td>
							<td style="vertical-align: middle;">
								<div class="input-append input-prepend">
									<span class="eshop-quantity">
										<input type="hidden" name="key[]" value="<?php echo $key; ?>" />
										<a class="btn btn-default button-plus" id="popout_<?php echo $countProducts; ?>" data="up">+</a>
											<input type="text" class="eshop-quantity-value" value="<?php echo htmlspecialchars($product['quantity'], ENT_COMPAT, 'UTF-8'); ?>" name="quantity[]" id="quantity_popout_<?php echo $countProducts; ?>" />
										<a class="btn btn-default button-minus" id="popout_<?php echo $countProducts; ?>" data="down">-</a>
									</span>
								</div>
							</td>
							<?php
							if (EshopHelper::showPrice())
							{
								?>
								<td style="vertical-align: middle;">
									<?php
									if (!$product['product_call_for_price'])
									{
										echo $this->currency->format($product['price']);
									}
									?>
								</td>
								<td style="vertical-align: middle;">
									<?php
									if (!$product['product_call_for_price'])
									{
										echo $this->currency->format($product['total_price']);
									}	
									?>
								</td>
								<?php
							}
							?>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<?php
			if (EshopHelper::showPrice())
			{
				?>
				<div class="totals text-center" style="text-align: center">
					<div>
						<?php echo JText::_('ESHOP_TOTAL'); ?>:
						<strong><?php echo $this->currency->format($totalPrice); ?></strong>
					</div>
				</div>
				<?php
			}
		}
		else
		{
			?>
			<div class="row-fluid">
				<?php
				$totalPrice = 0;
				foreach ($this->quoteData as $key => $product)
				{
					if (EshopHelper::showPrice() && !$product['product_call_for_price'])
					{
						$totalPrice += $product['total_price'];
					}
					$optionData = $product['option_data'];
					$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
					?>
					<div class="well clearfix">
						<div class="row-fluid">
							<div class="span2">
								<strong><?php echo JText::_('ESHOP_REMOVE'); ?>: </strong>
								<a class="eshop-remove-item-quote" id="<?php echo $key; ?>" style="cursor: pointer;">
									<img alt="<?php echo JText::_('ESHOP_REMOVE'); ?>" title="<?php echo JText::_('ESHOP_REMOVE'); ?>" src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/images/remove.png" />
								</a>
							</div>
							<div class="span2 eshop-center-text">
								<a href="<?php echo $viewProductUrl; ?>">
									<img class="img-polaroid" src="<?php echo $product['image']; ?>" />
								</a>
							</div>
							<div class="span2">
								<h5 class="eshop-center-text">
									<a href="<?php echo $viewProductUrl; ?>">
										<?php echo $product['product_name']; ?>
									</a>
								</h5>
								<?php
								if (count($optionData))
								{
									?>
									<br />
									<?php
								}
								for ($i = 0; $n = count($optionData), $i < $n; $i++)
								{
									echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . '<br />';
								}
								?>
							</div>
							<div class="span2">
								<strong><?php echo JText::_('ESHOP_MODEL'); ?>: </strong>
								<?php echo $product['product_sku']; ?>
							</div>
							<div class="span2">
								<strong><?php echo JText::_('ESHOP_QUANTITY'); ?></strong>
								<div class="input-append input-prepend">
									<span class="eshop-quantity">
										<input type="hidden" name="key[]" value="<?php echo $key; ?>" />
										<a class="btn btn-default button-plus" id="popout_<?php echo $countProducts; ?>" data="up">+</a>
											<input type="text" class="eshop-quantity-value" value="<?php echo htmlspecialchars($product['quantity'], ENT_COMPAT, 'UTF-8'); ?>" name="quantity[]" id="quantity_popout_<?php echo $countProducts; ?>" />
										<a class="btn btn-default button-minus" id="popout_<?php echo $countProducts; ?>" data="down">-</a>
									</span>
								</div>
							</div>
							<?php
							if (EshopHelper::showPrice() && !$product['product_call_for_price'])
							{
								?>
								<div class="span2">
									<strong><?php echo JText::_('ESHOP_UNIT_PRICE'); ?>: </strong>
									<?php echo $this->currency->format($product['price']); ?>
								</div>
								<div class="span2">
									<strong><?php echo JText::_('ESHOP_TOTAL'); ?>: </strong>
									<?php echo $this->currency->format($product['total_price']); ?>
								</div>
								<?php
							}
							?>
						</div>
					</div>
					<?php
				}
				if (EshopHelper::showPrice())
				{
					?>
					<div class="well clearfix">
						<?php echo JText::_('ESHOP_TOTAL'); ?>: <strong><?php echo $this->currency->format($totalPrice); ?></strong>
					</div>
					<?php
				}
				?>
			</div>
		<?php
	    }
		?>
	</div>
	<div class="control-group" style="text-align: center;">
		<div class="controls">
			<a class="btn btn-danger" href="<?php echo JRoute::_(EshopHelper::getContinueShopingUrl()); ?>"><?php echo JText::_('ESHOP_CONTINUE_SHOPPING'); ?></a>
			<button type="button" class="btn btn-info" onclick="updateQuote();" id="update-quote"><?php echo JText::_('ESHOP_UPDATE_QUOTE'); ?></button>
			<a class="btn btn-success" href="<?php echo JRoute::_(EshopRoute::getViewRoute('quote')); ?>"><?php echo JText::_('ESHOP_QUOTE_FORM'); ?></a>
		</div>
	</div>
	<script type="text/javascript">
		//Function to update quote
		function updateQuote(key)
		{
			Eshop.jQuery(function($){
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type: 'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=quote.updates<?php echo EshopHelper::getAttachedLangLink(); ?>',
					data: $('.quote-info input[type=\'text\'], .quote-info input[type=\'hidden\']'),
					beforeSend: function() {
						$('#update-quote').attr('disabled', true);
						$('#update-quote').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('#update-quote').attr('disabled', false);
						$('.wait').remove();
					},
					success: function() {
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=quote&layout=popout&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
							dataType: 'html',
							success: function(html) {
								$.colorbox({
									overlayClose: true,
									opacity: 0.5,
									href: false,
									html: html
								});
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=quote&layout=mini&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										jQuery('#eshop-quote').html(html);
										jQuery('.eshop-content').hide();
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
				  	}
				});
			})
		}

		Eshop.jQuery(function($) {
			//Ajax remove quote item
			$('.eshop-remove-item-quote').bind('click', function() {
				var aTag = $(this);
				var id = aTag.attr('id');
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type :'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=quote.remove&key=' +  id + '&redirect=1<?php echo EshopHelper::getAttachedLangLink(); ?>',
					beforeSend: function() {
						aTag.attr('disabled', true);
						aTag.after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						aTag.attr('disabled', false);
						$('.wait').remove();
					},
					success : function() {
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=quote&layout=popout&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
							dataType: 'html',
							success: function(html) {
								$.colorbox({
									overlayClose: true,
									opacity: 0.5,
									href: false,
									html: html
								});
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=quote&layout=mini&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										jQuery('#eshop-quote').html(html);
										jQuery('.eshop-content').hide();
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		});
	</script>
	<?php
}