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
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelMessage extends EShopModel
{
	function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('message_value');
		parent::__construct($config);
	}
}