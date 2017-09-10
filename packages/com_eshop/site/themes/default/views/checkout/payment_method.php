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
<script type="text/javascript">
	Eshop.jQuery(document).ready(function($){			
		$(".colorbox").colorbox({
			overlayClose: true,
			opacity: 0.5,
		});
	});
</script>
<?php
if (count($this->methods))
{
	?>
	<div class="control-group">
		<p><?php echo JText::_('ESHOP_PAYMENT_METHOD_TITLE'); ?></p>
		<?php
		for ($i = 0 , $n = count($this->methods); $i < $n; $i++)
		{
			$checked = '';
			$paymentMethod = $this->methods[$i];
			if ($this->paymentMethod != '')
			{
				if ($paymentMethod->getName() == $this->paymentMethod)
				{
					$checked = ' checked="checked" ';
				}	
			}
			else
			{
				if ($i == 0)
				{
					$checked = ' checked="checked" ';
				}
			}
			?>
			<label class="radio">
				<input type="radio" name="payment_method" value="<?php echo $paymentMethod->getName(); ?>" <?php echo $checked; ?> />
					<?php
					if ($paymentMethod->iconUri != '')
					{
						?>
						<img alt="<?php echo $paymentMethod->title; ?>" src="<?php echo $paymentMethod->iconUri; ?>" />
						<?php
					}
					else 
					{
						echo JText::_($paymentMethod->title);
					}
					?>
				<br />
			</label>
			<?php
		}
		?>
	</div>
	<?php
}
if (EshopHelper::getConfigValue('enable_checkout_donate'))
{
	?>
	<br />
	<div class="control-group">
		<p><?php echo JText::_('ESHOP_CHECKOUT_DONATE_INTRO'); ?></p>
		<?php
		if (EshopHelper::getConfigValue('donate_amounts') != '')
		{
			$donateAmounts = explode("\n", EshopHelper::getConfigValue('donate_amounts'));
			$donateExplanations = explode("\n", EshopHelper::getConfigValue('donate_explanations'));
			for ($i = 0 , $n = count($donateAmounts); $i < $n; $i++)
			{
				?>
				<label class="radio">
					<?php
					if ($donateAmounts[$i] > 0)
					{
						?>
						<input type="radio" name="donate_amount" value="<?php echo trim($donateAmounts[$i]); ?>" /> <?php echo $this->currency->format(trim($donateAmounts[$i])) . (isset($donateExplanations[$i]) && $donateExplanations[$i] != '' ? ' (' . trim($donateExplanations[$i]) . ')' : ''); ?><br />
						<?php
					}
					else 
					{
						?>
						<input type="radio" checked="checked" name="donate_amount" value="<?php echo trim($donateAmounts[$i]); ?>" /> <?php echo (isset($donateExplanations[$i]) && $donateExplanations[$i] != '' ? trim($donateExplanations[$i]) : ''); ?><br />
						<?php
					}
					?>
				</label>
				<?php
			}
			?>
				<label class="radio">
					<input type="radio" name="donate_amount" value="other_amount" /><?php echo JText::_('ESHOP_DONATE_OTHER_AMOUNT'); ?><br />
				</label>
				<input type="text" name="other_amount" id="other_amount" class="input-small" />
			<?php
		}
		else 
		{
			?>
			<label for="other_amount" class="control-label"><?php echo JText::_('ESHOP_DONATE_AMOUNT'); ?></label>
			<input type="text" name="other_amount" id="other_amount" class="input-small" />
			<?php
		}
		?>
	</div>
	<?php
}
if (EshopHelper::getConfigValue('allow_coupon'))
{
	?>
	<br />
	<div class="control-group">
		<label for="coupon_code" class="control-label"><?php echo JText::_('ESHOP_COUPON_TEXT'); ?></label>
		<div class="controls">
			<input type="text" id="coupon_code" name="coupon_code" class="input-large" value="<?php echo htmlspecialchars($this->coupon_code, ENT_COMPAT, 'UTF-8'); ?>">
		</div>
	</div>
	<?php
}
if (EshopHelper::getConfigValue('allow_voucher'))
{
	?>
	<div class="control-group">
		<label for="voucher_code" class="control-label"><?php echo JText::_('ESHOP_VOUCHER_TEXT'); ?></label>
		<div class="controls">
			<input type="text" id="voucher_code" name="voucher_code" class="input-large" value="<?php echo htmlspecialchars($this->voucher_code, ENT_COMPAT, 'UTF-8'); ?>">
		</div>
	</div>
	<?php
}
?>
<br />
<div class="control-group">
	<label for="textarea" class="control-label"><?php echo JText::_('ESHOP_COMMENT_ORDER'); ?></label>
	<div class="controls">
		<textarea rows="8" id="textarea" class="input-xlarge span12" name="comment"><?php echo $this->comment; ?></textarea>
	</div>
</div>
<div class="no_margin_left">
	<?php
	if (isset($this->checkoutTermsLink) && $this->checkoutTermsLink != '')
	{
		?>
		<span class="privacy">
			<input type="checkbox" value="1" name="checkout_terms_agree" <?php echo ($this->checkout_terms_agree) ? $this->checkout_terms_agree : ''; ?>/>
			&nbsp;<?php echo JText::_('ESHOP_CHECKOUT_TERMS_AGREE'); ?>&nbsp;<a class="colorbox cboxElement" href="<?php echo $this->checkoutTermsLink; ?>"><?php echo JText::_('ESHOP_CHECKOUT_TERMS_AGREE_TITLE'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</span>	
		<?php
	}
	if (EshopHelper::getConfigValue('acymailing_integration') || EshopHelper::getConfigValue('mailchimp_integration'))
	{
		?>
		<br /><span class="not-newsletter-interest">
			<input type="checkbox" value="1" name="not_newsletter_interest" /><?php echo JText::_('ESHOP_NOT_NEWSLETTER_INTEREST'); ?>
		</span>	
		<?php
	}
	?>
	<input type="button" class="btn btn-primary pull-right" id="button-payment-method" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>
<script type="text/javascript">
	Eshop.jQuery(function($){
		// Payment Method
		$('#button-payment-method').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.processPaymentMethod<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'text\'],  #payment-method textarea'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-payment-method').attr('disabled', true);
					$('#button-payment-method').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},	
				complete: function() {
					$('#button-payment-method').attr('disabled', false);
					$('.wait').remove();
				},			
				success: function(json) {
					$('.warning, .error').remove();
					
					if (json['return']) {
						window.location.href = json['return'];
					} else if (json['error']) {
						if (json['error']['warning']) {
							$('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
							$('.warning').fadeIn('slow');
						}
					} else {
						var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
							dataType: 'html',
							success: function(html) {
								$('#confirm .checkout-content').html(html);
								if ($('#confirm .eshop-squareup-information').length)
								{
									$('#confirm .eshop-squareup-information').css('display', '');
								}
								if ($('#confirm .eshop-payment-information').length)
								{
									$('#confirm .eshop-squareup-information').css('display', 'none');
								}
								$('#payment-method .checkout-content').slideUp('slow');
								$('#confirm .checkout-content').slideDown('slow');
								$('#payment-method .checkout-heading a').remove();
								$('#payment-method .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	})
</script>