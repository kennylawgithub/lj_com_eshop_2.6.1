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
<div class="row-fluid">
	<h1 id="make-notify-title"><?php echo JText::_('ESHOP_PRODUCT_NOTIFY_TITLE'); ?></h1>
	<div id="make-notify-area">
		<div class="make-notify-intro"><?php echo sprintf(JText::_('ESHOP_PRODUCT_NOTIFY_DESC'), $this->item->product_name); ?></div>
		<form method="post" name="adminForm" id="adminForm" action="index.php" class="form form-horizontal">
			<div class="control-group">
				<label class="control-label" for="email"><span class="required">*</span><?php echo JText::_('ESHOP_EMAIL'); ?>:</label>
				<div class="controls docs-input-sizes">
					<input type="text" class="input-large" name="notify_email" id="notify_email" value="" />
					<span style="display: none;" class="error email-required"><?php echo JText::_('ESHOP_EMAIL_REQUIRED'); ?></span>
					<span style="display: none;" class="error email-invalid"><?php echo JText::_('ESHOP_EMAIL_INVALID'); ?></span>
				</div>
			</div>			
			<input type="hidden" name="product_id" id="product_id" value="<?php echo JRequest::getInt('id'); ?>" />
			<input type="button" class="btn btn-primary pull-left" id="button-make-notify" value="<?php echo JText::_('ESHOP_SUBMIT'); ?>" />
			<span class="wait"></span>
		</form>
	</div>
</div>
<script type="text/javascript">
	function isValidEmail(emailAddress)
	{
	    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
	    return pattern.test(emailAddress);
	}
	Eshop.jQuery(function($){
		$('#button-make-notify').click(function(){
			var contactEmail = $('#notify_email').val();
			var validated = true;
			if(contactEmail == '')
			{
				validated = false;
				$('.email-required').show();
			}
			else if (!isValidEmail(contactEmail))
			{
				validated = false;
				$('.email-required').hide();
				$('.email-invalid').show();
			}
			else
			{
				$('.email-required').hide();
				$('.email-invalid').hide();
			}
			
			if (validated)
			{
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type :'post',
					url: siteUrl + 'index.php?option=com_eshop&task=product.processNotify<?php echo EshopHelper::getAttachedLangLink(); ?>',
					data: $('#make-notify-area input[type=\'text\'], #make-notify-area input[type=\'hidden\']'),
					beforeSend: function() {
						$('.wait').html('<img src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/images/loading.gif" alt="" />');
					},
					success : function(html) {
						$('#make-notify-area').html('<div>'+html+'</div>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});
	});
</script>
