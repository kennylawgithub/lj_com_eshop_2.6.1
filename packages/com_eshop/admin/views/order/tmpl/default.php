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
//EshopHelper::chosen();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'order.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			Joomla.submitform(pressbutton, form);
		}
	}

	if (typeof(Eshop) === 'undefined') {
	    var Eshop = {};
	}
	Eshop.jQuery = jQuery.noConflict();
	Eshop.jQuery(document).ready(function($){
		$('#payment_country_id').change(function(){
			$.ajax({
				url: 'index.php?option=com_eshop&task=customer.country&country_id=' + this.value,
				dataType: 'json',
				beforeSend: function() {
					$('#payment_country_id').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('.wait').remove();
				},
				success: function(json) {
					html = '<option value="0"><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
					if (json['zones'] != '') {
						for (i = 0; i < json['zones'].length; i++) {
		        			html += '<option value="' + json['zones'][i]['id'] + '"';
							if (json['zones'][i]['id'] == '<?php echo $this->item->payment_zone_id; ?>') {
			      				html += ' selected="selected"';
			    			}
			    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
						}
					}
					$('#payment_zone_id').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			})
		});
	})
	Eshop.jQuery(document).ready(function($){
		$('#shipping_country_id').change(function(){
			$.ajax({
				url: 'index.php?option=com_eshop&task=customer.country&country_id=' + this.value,
				dataType: 'json',
				beforeSend: function() {
					jQuery('select[name=\'shipping_country_id\']').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					jQuery('.wait').remove();
				},
				success: function(json) {
					html = '<option value="0"><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
					if (json['zones'] != '') {
						for (i = 0; i < json['zones'].length; i++) {
		        			html += '<option value="' + json['zones'][i]['id'] + '"';
							if (json['zones'][i]['id'] == '<?php echo $this->item->shipping_zone_id; ?>') {
			      				html += ' selected="selected"';
			    			}
			    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
						}
					}
					jQuery('select[name=\'shipping_zone_id\']').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			})
		});
	})
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_GENERAL'); ?></a></li>
			<li><a href="#customer-details-page" data-toggle="tab"><?php echo JText::_('ESHOP_ORDER_CUSTOMER_DETAILS'); ?></a></li>
			<li><a href="#payment-details-page" data-toggle="tab"><?php echo JText::_('ESHOP_ORDER_PAYMENT_DETAILS'); ?></a></li>
			<li><a href="#shipping-details-page" data-toggle="tab"><?php echo JText::_('ESHOP_ORDER_SHIPPING_DETAILS'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general-page">
				<div class="span12">
					<table class="adminlist table table-bordered" style="text-align: center;">
						<thead>
							<tr>
								<th class="text_left"><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
								<th class="text_left"><?php echo JText::_('ESHOP_MODEL'); ?></th>
								<th class="text_right"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
								<th class="text_right"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
								<th class="text_right"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach ($this->lists['order_products'] as $product)
						{
							$options = $product->options;
							?>
							<tr>
								<td class="text_left">
									<?php
									echo '<b>' . $product->product_name . '</b>';
									for ($i = 0; $n = count($options), $i < $n; $i++)
									{
										if ($options[$i]->option_type == 'File' && $options[$i]->option_value != '')
										{
											echo '<br />- ' . $options[$i]->option_name . ': <a href="index.php?option=com_eshop&task=order.downloadFile&id=' . $options[$i]->id . '">' . $options[$i]->option_value . '</a>';
										}
										else
										{
											echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
										}
									}
									?>
								</td>
								<td class="text_left"><?php echo $product->product_sku; ?></td>
								<td class="text_right"><?php echo $product->quantity; ?></td>
								<td class="text_right">
									<?php echo $this->currency->format($product->price, $this->item->currency_code, $this->item->currency_exchanged_value); ?>
								</td>
								<td class="text_right">
									<?php echo $this->currency->format($product->total_price, $this->item->currency_code, $this->item->currency_exchanged_value); ?>
								</td>
							</tr>
							<?php
						}
						foreach ($this->lists['order_totals'] as $total)
						{
							?>
							<tr>
								<td colspan="4" class="text_right"><?php echo $total->title; ?>:</td>
								<td class="text_right"><?php echo $total->text; ?></td>
							</tr>
							<?php	
						}
						?>
						</tbody>
					</table>
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ORDER_PAYMENT_METHOD'); ?>
							</td>
							<td>
								<?php echo JText::_($this->item->payment_method_title); ?>
							</td>
						</tr>
						<?php
						if ($this->item->payment_method == "os_creditcard")
						{
							$params = new JRegistry($this->item->params);
							?>
								<tr>
									<td class="key">
										<?php echo JText::_('ESHOP_FIRST_PART_CREDIT_OF_CARD_NUMBER'); ?>
									</td>
									<td>
										<?php echo $params->get('card_number'); ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_('ESHOP_CARD_EXPIRATION_DATE'); ?>
									</td>
									<td>
										<?php echo $params->get('exp_date'); ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_('ESHOP_CARD_CVV_CODE'); ?>
									</td>
									<td>
										<?php echo $params->get('cvv'); ?>
									</td>
								</tr>
							<?php
							}
						?>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ORDER_SHIPPING_METHOD'); ?>
							</td>
							<td>
								<?php echo $this->item->shipping_method_title; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ORDER_STATUS'); ?>
							</td>
							<td>
								<?php echo $this->lists['order_status_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_SEND_NOTIFICATION_EMAIL'); ?>
							</td>
							<td>
								<label class="checkbox">
									<input type="checkbox" name="send_notification_email" value="1" checked="checked" /><span class="help">(<?php echo JText::_('ESHOP_SEND_NOTIFICATION_EMAIL_HELP'); ?>)</span>
								</label>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_SHIPPING_TRACKING_NUMBER'); ?>
							</td>
							<td>
								<input class="input-large" type="text" name="shipping_tracking_number" id="shipping_tracking_number" value="<?php echo $this->item->shipping_tracking_number; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_SHIPPING_TRACKING_URL'); ?>
							</td>
							<td>
								<input class="input-xxlarge" type="text" name="shipping_tracking_url" id="shipping_tracking_url" value="<?php echo $this->item->shipping_tracking_url; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_SEND_SHIPPING_NOTIFICATION_EMAIL'); ?>
							</td>
							<td>
								<label class="checkbox">
									<input type="checkbox" name="send_shipping_notification_email" value="1" checked="checked" /><span class="help">(<?php echo JText::_('ESHOP_SEND_SHIPPING_NOTIFICATION_EMAIL_HELP'); ?>)</span>
								</label>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_COMMENT'); ?>
							</td>
							<td>
								<textarea name="comment" cols="30" rows="5"><?php echo $this->item->comment; ?></textarea>
							</td>
						</tr>
						<?php
						if (EshopHelper::getConfigValue('delivery_date'))
						{
							?>
							<tr>
								<td class="key">
									<?php echo JText::_('ESHOP_DELIVERY_DATE'); ?>
								</td>
								<td>
									<?php echo JHtml::_('date', $this->item->delivery_date, 'm/d/Y'); ?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="customer-details-page">
				<div class="span6">
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_CUSTOMER'); ?>
							</td>
							<td>
								<?php
								if ($this->item->customer_id)
								{
									echo $this->lists['customer_id'];
								}
								else 
								{
									echo $this->item->firstname . ' ' . $this->item->lastname;
								}
								?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_CUSTOMERGROUP'); ?>
							</td>
							<td>
								<?php echo $this->lists['customergroup_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_FIRST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="firstname" id="firstname" maxlength="32" value="<?php echo $this->item->firstname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_LAST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="lastname" id="lastname" maxlength="32" value="<?php echo $this->item->lastname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_EMAIL'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="email" id="email" maxlength="96" value="<?php echo $this->item->email; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_TELEPHONE'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="telephone" id="telephone" maxlength="32" value="<?php echo $this->item->telephone; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_FAX'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="fax" id="fax" maxlength="32" value="<?php echo $this->item->fax; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="payment-details-page">
				<div class="span6">
					<table class="admintable adminform" style="width: 100%;">
						<?php 
							echo $this->billingForm->render(false);
						?>						
					</table>
				</div>
			</div>
			<div class="tab-pane" id="shipping-details-page">
				<div class="span6">
					<table class="admintable adminform" style="width: 100%;">
						<?php 
							echo $this->shippingForm->render(false);
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>