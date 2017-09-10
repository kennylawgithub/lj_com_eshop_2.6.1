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
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
if (!EshopHelper::isMobile())
{
	?>
	<div class="cart-info">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
					<th><?php echo JText::_('ESHOP_MODEL'); ?></th>
					<th><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
					<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
					<th><?php echo JText::_('ESHOP_TOTAL'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->cartData as $key => $product)
				{
					$optionData = $product['option_data'];
					$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
					?>
					<tr>
						<td>
							<a href="<?php echo $viewProductUrl; ?>">
								<?php echo $product['product_name']; ?>
							</a><br />	
							<?php
							for ($i = 0; $n = count($optionData), $i < $n; $i++)
							{
								echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . (isset($optionData[$i]['sku']) && $optionData[$i]['sku'] != '' ? ' (' . $optionData[$i]['sku'] . ')' : '') . '<br />';
							}
							?>
						</td>
						<td><?php echo $product['product_sku']; ?></td>
						<td>
							<?php echo $product['quantity']; ?>
						</td>
						<td><?php echo $this->currency->format($product['price']); ?></td>
						<td><?php echo $this->currency->format($product['total_price']); ?></td>
					</tr>
					<?php
				}
				foreach ($this->totalData as $data)
				{
					?>
					<tr>
						<td colspan="4" style="text-align: right;"><?php echo $data['title']; ?>:</td>
						<td><strong><?php echo $data['text']; ?></strong></td>
					</tr>
					<?php	
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}
else
{
	?>
	<div class="cart-info">
		<div class="row-fluid">
			<?php
			foreach ($this->cartData as $key => $product)
			{
				$optionData = $product['option_data'];
				$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
				?>
				<div class="well clearfix">
					<div class="row-fluid">
						<div class="span4">							
							<h5 class="eshop-center-text">
								<a href="<?php echo $viewProductUrl; ?>">
									<?php echo $product['product_name']; ?>
								</a>
							</h5>
							<?php
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
							<strong><?php echo JText::_('ESHOP_QUANTITY'); ?>: </strong>
							<?php echo $product['quantity']; ?>
						</div>
						<div class="span2">
							<strong><?php echo JText::_('ESHOP_UNIT_PRICE'); ?> : </strong>
							<?php echo $this->currency->format($product['price']); ?>
						</div>
						<div class="span2">
							<strong><?php echo JText::_('ESHOP_TOTAL'); ?>: </strong>
							<?php echo $this->currency->format($product['total_price']); ?>
						</div>
					</div>
				</div>
				<?php
			}
			if (EshopHelper::showPrice())
			{
				?>
				<div class="well clearfix">
					<?php
					foreach ($this->totalData as $data)
					{
						echo $data['title']; ?>:<strong><?php echo $data['text']; ?></strong><br />
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
<?php
}

if ($this->total > 0)
{
	
	if ($this->paymentClass->getName() != 'os_squareup')
	{
		?>
		<div class="eshop-payment-information">
			<?php echo $this->paymentClass->renderPaymentInformation(); ?>
		</div>	
		<?php
	}
}
else 
{
	?>
	<form action="<?php echo EshopHelper::getSiteUrl(); ?>index.php?option=com_eshop&task=checkout.processOrder" method="post" name="payment_method_form" id="payment_method_form" class="form form-horizontal">
		<div class="no_margin_left">
			<div class="no_margin_left">
				<input id="button-confirm" type="submit" class="btn btn-primary pull-right" value="<?php echo JText::_('ESHOP_CONFIRM_ORDER'); ?>" />
			</div>
		</div>
	</form>
	<?php	
}