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
JToolBarHelper::title(JText::_('ESHOP_EXPORTS'), 'generic.png');
JToolBarHelper::custom('exports.process', 'download', 'download', Jtext::_('ESHOP_PROCESS'), false);
JToolBarHelper::cancel('exports.cancel');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'exports.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			if (form.export_type.value == '') {
				alert('<?php echo JText::_('ESHOP_EXPORT_TYPE_PROMPT'); ?>');
				form.export_type.focus();
				return;	
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<div class="row-fluid">
		<div class="span8">
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_EXPORT_TYPE'); ?>
				</div>
				<div class="controls">
					<?php echo $this->lists['export_type']; ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_ORDER_STATUS'); ?>
				</div>	
				<div class="controls">
					<?php echo $this->lists['order_status_id']; ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_ORDER_ID'); ?>
				</div>
				<div class="controls">
					<?php echo  JText::_('ESHOP_FROM'); ?>&nbsp;<input class="input-mini" type="text" name="order_id_from" id="order_id_from" value="0" />&nbsp;<?php echo  JText::_('ESHOP_TO'); ?>&nbsp;<input class="input-mini" type="text" name="order_id_to" id="order_id_to" value="0" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_LIST_ORDER_ID'); ?>
				</div>
				<div class="controls">
					<input class="input-xxlarge" type="text" name="list_order_id" id="list_order_id" value="" />
				</div>
				<span class="help"><?php echo  JText::_('ESHOP_LIST_ORDER_ID_HELP'); ?></span>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_FIELD_DELIMITER'); ?>
				</div>
				<div class="controls">
					<input class="input-mini" type="text" name="field_delimiter" id="field_delimiter" maxlength="1" value="," />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_IMAGE_SEPARATOR'); ?>
				</div>
				<div class="controls">
					<input class="input-mini" type="text" name="image_separator" id="image_separator" maxlength="1" value=";" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_LANGUAGE'); ?>
				</div>
				<div class="controls">
					<?php echo $this->lists['language']; ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_EXPORT_FORMAT'); ?>
				</div>
				<div class="controls">
					<?php echo $this->lists['export_format']; ?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<div class="clearfix"></div>
</form>