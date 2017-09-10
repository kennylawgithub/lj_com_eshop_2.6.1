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
$orderProducts = $this->order_products;
$orderTotals   = $this->order_total;
?>
<table width="100%">
	<tr>
		<td style="background-color: #CDDDDD; text-align: left">
			<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
		</td>
		<td style="background-color: #CDDDDD; text-align: left">
			<?php echo JText::_('ESHOP_MODEL'); ?>
		</td>
		<td style="background-color: #CDDDDD; text-align: left">
			<?php echo JText::_('ESHOP_QUANTITY'); ?>
		</td>
		<td style="background-color: #CDDDDD; text-align: left">
			<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>
		</td>
		<td style="background-color: #CDDDDD; text-align: left">
			<?php echo JText::_('ESHOP_TOTAL'); ?>
		</td>
	</tr>
	<?php 
	foreach ($orderProducts as $product)
	{
		$options = $product->options;
		?>
		<tr>
			<td>
				<?php
				echo '<b>' . $product->product_name . '</b>';
				for ($i = 0; $n = count($options), $i < $n; $i++)
				{
					echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
				}
				?>
			</td>
			<td>
				<?php echo $product->product_sku; ?>
			</td>
			<td>
				<?php echo $product->quantity; ?>
			</td>
			<td>
				<?php echo $product->price; ?>
			</td>
			<td>
				<?php echo $product->total_price; ?>
			</td>
		</tr>
		<?php 
	}
	foreach ($orderTotals as $orderTotal)
	{
		?>
		<tr>
			<td colspan="4" style="text-align: right;">
				<?php echo JText::_($orderTotal->title); ?>:
			</td>
			<td>
				<strong><?php echo $orderTotal->text; ?></strong>
			</td>
		</tr>
		<?php
	}
	?>
</table>