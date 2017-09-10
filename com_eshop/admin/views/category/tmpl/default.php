<?php
/**
 * @version		2.6.2
 * @package		Joomla
 * @subpackage	ESshop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
EshopHelper::chosen();
$editor = JFactory::getEditor();
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'category.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			<?php
			if ($translatable)
			{
				foreach ($this->languages as $language)
				{
					$langId = $language->lang_id;
					?>
					if (document.getElementById('category_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('category_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.category_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.category_name.focus();
					return;
				}
				<?php
			}
			?>
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_GENERAL'); ?></a></li>		
			<li><a href="#data-page" data-toggle="tab"><?php echo JText::_('ESHOP_DATA'); ?></a></li>					
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general-page">
				<div class="span10">
				<?php
				if ($translatable) {
					?>
					<ul class="nav nav-tabs">
						<?php
							$i = 0;
							foreach ($this->languages as $language) {
								$langCode = $language->lang_code;
								?>
								<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#general-page-<?php echo $langCode; ?>" data-toggle="tab"><?php echo $this->languageData['title'][$langCode]; ?>
								<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" /></a></li>
								<?php
								$i++;	
							}
						?>																					
					</ul>
					<div class="tab-content">
					<?php
						$i = 0;
						foreach ($this->languages as $language)
						{
							$langId = $language->lang_id;
							$langCode = $language->lang_code;
						?>
							<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="general-page-<?php echo $langCode; ?>">
								<div class="control-group">
									<div class="control-label">
										<span class="required">*</span>
										<?php echo  JText::_('ESHOP_NAME'); ?>
									</div>
									<div class="controls">
										<input class="input-xlarge" type="text" name="category_name_<?php echo $langCode; ?>" id="category_name_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_name_'.$langCode}) ? $this->item->{'category_name_'.$langCode} : ''; ?>" />
									</div>								
								</div>																		
								<div class="control-group">
									<div class="control-label">
										<?php echo  JText::_('ESHOP_ALIAS'); ?>
									</div>
									<div class="controls">
										<input class="input-xlarge" type="text" name="category_alias_<?php echo $langCode; ?>" id="category_alias_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_alias_'.$langCode}) ? $this->item->{'category_alias_'.$langCode} : ''; ?>" />
									</div>								
								</div>
								<div class="control-group">
									<div class="control-label">
										<?php echo  JText::_('ESHOP_PAGE_TITLE'); ?>
									</div>
									<div class="controls">
										<input class="input-xlarge" type="text" name="category_page_title_<?php echo $langCode; ?>" id="category_page_title_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_page_title_'.$langCode}) ? $this->item->{'category_page_title_'.$langCode} : ''; ?>" />
									</div>								
								</div>
								<div class="control-group">
									<div class="control-label">
										<?php echo  JText::_('ESHOP_PAGE_HEADING'); ?>
									</div>
									<div class="controls">
										<input class="input-xlarge" type="text" name="category_page_heading_<?php echo $langCode; ?>" id="category_page_heading_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_page_heading_'.$langCode}) ? $this->item->{'category_page_heading_'.$langCode} : ''; ?>" />
									</div>								
								</div>
								<div class="control-group">
									<div class="control-label">
										<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
									</div>
									<div class="controls">
										<?php echo $editor->display( 'category_desc_'.$langCode,  isset($this->item->{'category_desc_'.$langCode}) ? $this->item->{'category_desc_'.$langCode} : '' , '100%', '250', '75', '10' ); ?>
									</div>								
								</div>
								<div class="control-group">
									<div class="control-label">
										<?php echo JText::_('ESHOP_META_KEYS'); ?>
									</div>
									<div class="controls">
										<textarea rows="5" cols="30" name="meta_key_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_key_'.$langCode}; ?></textarea>
									</div>
								</div>			
								<div class="control-group">
									<div class="control-label">
										<?php echo JText::_('ESHOP_META_DESC'); ?>
									</div>
									<div class="controls">
										<textarea rows="5" cols="30" name="meta_desc_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_desc_'.$langCode}; ?></textarea>
									</div>
								</div>
							</div>
							<?php
							$i++;
							}
						?>
					</div>
				<?php
				}
				else
				{
					?>
					<div class="control-group">
						<div class="control-label">
							<span class="required">*</span>
							<?php echo  JText::_('ESHOP_NAME'); ?>
						</div>
						<div class="controls">
							<input class="input-xlarge" type="text" name="category_name" id="category_name" size="" maxlength="250" value="<?php echo $this->item->category_name; ?>" />
						</div>								
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_ALIAS'); ?>
						</div>
						<div class="controls">
							<input class="input-xlarge" type="text" name="category_alias" id="category_alias" size="" maxlength="250" value="<?php echo $this->item->category_alias; ?>" />
						</div>								
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_PAGE_TITLE'); ?>
						</div>
						<div class="controls">
							<input class="input-xlarge" type="text" name="category_page_title" id="category_page_title" size="" maxlength="250" value="<?php echo $this->item->category_page_title; ?>" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_PAGE_HEADING'); ?>
						</div>
						<div class="controls">
							<input class="input-xlarge" type="text" name="category_page_heading" id="category_page_heading" size="" maxlength="250" value="<?php echo $this->item->category_page_heading; ?>" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
						</div>
						<div class="controls">
							<?php echo $editor->display( 'category_desc',  $this->item->category_desc , '100%', '250', '75', '10' ); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('ESHOP_META_KEYS'); ?>
						</div>
						<div class="controls">
							<textarea rows="5" cols="30" name="meta_key"><?php echo $this->item->meta_key; ?></textarea>
						</div>
					</div>			
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('ESHOP_META_DESC'); ?>
						</div>
						<div class="controls">
							<textarea rows="5" cols="30" name="meta_desc"><?php echo $this->item->meta_desc; ?></textarea>
						</div>
					</div>
					<?php
				}
				?>
				</div>
			</div>			
			<div class="tab-pane" id="data-page">
				<div class="span8">
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_PARENT_CATEGORY'); ?>
						</div>
						<div class="controls">
							<?php echo $this->lists['category_parent_id']; ?>
						</div>							
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_CATEGORY_IMAGE'); ?>
						</div>
						<div class="controls">
							<input type="file" class="input-large" accept="image/*" name="category_image" />								
							<?php
								if (JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$this->item->category_image))
								{
									$viewImage = JFile::stripExt($this->item->category_image).'-100x100.'.JFile::getExt($this->item->category_image);
									if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/categories/resized/'.$viewImage))
									{
										?>
										<img src="<?php echo JURI::root().'media/com_eshop/categories/resized/'.$viewImage; ?>" />
										<?php
									}
									else 
									{
										?>
										<img src="<?php echo JURI::root().'media/com_eshop/categories/'.$this->item->category_image; ?>" height="100" />
										<?php
									}
									?>
									<input type="checkbox" name="remove_image" value="1" />
									<?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
									<?php
								}
							?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('ESHOP_CATEGORY_LAYOUT'); ?>
						</div>
						<div class="controls">
							<?php echo $this->lists['category_layout']; ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('ESHOP_CUSTOMERGROUPS'); ?>
						</div>
						<div class="controls">
							<?php echo $this->lists['category_customergroups']; ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_PRODUCTS_PER_PAGE'); ?>
						</div>
						<div class="controls">
							<input class="input-small" type="text" name="products_per_page" id="products_per_page" size="" maxlength="250" value="<?php echo $this->item->products_per_page; ?>" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo  JText::_('ESHOP_PRODUCTS_PER_ROW'); ?>
						</div>
						<div class="controls">
							<input class="input-small" type="text" name="products_per_row" id="products_per_row" size="" maxlength="250" value="<?php echo $this->item->products_per_row; ?>" />
						</div>
					</div>						
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('ESHOP_PUBLISHED'); ?>
						</div>
						<div class="controls">
							<?php echo $this->lists['published']; ?>
						</div>							
					</div>
				</div>
			</div>			
		</div>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<?php
	if ($translatable)
	{
		foreach ($this->languages as $language)
		{
			$langCode = $language->lang_code;
			?>
			<input type="hidden" name="details_id_<?php echo $langCode; ?>" value="<?php echo isset($this->item->{'details_id_' . $langCode}) ? $this->item->{'details_id_' . $langCode} : ''; ?>" />
			<?php
		}
	}
	elseif ($this->translatable)
	{
	?>
		<input type="hidden" name="details_id" value="<?php echo isset($this->item->{'details_id'}) ? $this->item->{'details_id'} : ''; ?>" />
		<?php
	}
	?>
	<input type="hidden" name="task" value="" />
</form>