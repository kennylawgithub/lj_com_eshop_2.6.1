<?php
/**
 * @version        2.6.2
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewCheckout extends EShopView
{
	public function display($tpl = null)
	{
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
			JHtml::_('calendar', '', 'id', 'name');
		}
		else
		{
			JHtml::_('behavior.calendar');
		}
		JHtml::_('behavior.tooltip');

		$app = JFactory::getApplication();

		if (EshopHelper::getConfigValue('catalog_mode'))
		{
			JFactory::getSession()->set('warning', JText::_('ESHOP_CATALOG_MODE_ON'));
			$app->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else
		{
			$menu     = $app->getMenu();
			$menuItem = $menu->getActive();

			if ($menuItem)
			{
				if (isset($menuItem->query['view']) && ($menuItem->query['view'] == 'frontpage'))
				{
					$pathway = $app->getPathway();
					$pathUrl = EshopRoute::getViewRoute('frontpage');
					$pathway->addItem(JText::_('ESHOP_CHECKOUT'), $pathUrl);
				}
			}

			if ($this->getLayout() == 'complete')
			{
				$this->displayComplete($tpl);
			}
			elseif ($this->getLayout() == 'cancel')
			{
				$this->displayCancel($tpl);
			}
			else
			{
				$cart = new EshopCart();

				// Check if cart has products or not
				if (!$cart->hasProducts() || $cart->getStockWarning() != '' || $cart->getMinSubTotalWarning() != '' || $cart->getMinQuantityWarning() != '' || $cart->getMinProductQuantityWarning() != '' || $cart->getMaxProductQuantityWarning() != '')
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('cart')));
				}

				JFactory::getDocument()->addStyleSheet(JUri::base(true) . '/components/com_eshop/assets/colorbox/colorbox.css');

				$this->setPageTitle(JText::_('ESHOP_CHECKOUT'));

				$user                    = JFactory::getUser();
				$this->user              = $user;
				$this->shipping_required = $cart->hasShipping();

				if (EshopHelper::getConfigValue('enable_checkout_captcha'))
				{
					$captchaPlugin = $app->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));

					if ($captchaPlugin == 'recaptcha')
					{
						JCaptcha::getInstance($captchaPlugin)->initialise('dynamic_recaptcha_1');
					}
				}
				
				parent::display($tpl);
			}
		}
	}

	/**
	 *
	 * Function to display complete layout
	 *
	 * @param string $tpl
	 */
	protected function displayComplete($tpl)
	{
		$cart       = new EshopCart();
		$session    = JFactory::getSession();
		$orderId    = $session->get('order_id');
		$orderInfor = EshopHelper::getOrder($orderId);

		if (is_object($orderInfor))
		{
			if ($orderInfor->payment_method == 'os_ideal' && $orderInfor->order_status_id != EshopHelper::getConfigValue('complete_status_id'))
			{
				JFactory::getApplication()->redirect('index.php?option=com_eshop&view=checkout&layout=cancel&id=' . $orderInfor->id);
			}

			$db            = JFactory::getDbo();
			$query         = $db->getQuery(true);
			$tax           = new EshopTax(EshopHelper::getConfig());
			$currency      = new EshopCurrency();
			$orderProducts = EshopHelper::getOrderProducts($orderId);

			for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
			{
				$query->clear()
					->select('*')
					->from('#__eshop_orderoptions')
					->where('order_product_id = ' . intval($orderProducts[$i]->id));
				$db->setQuery($query);
				$orderProducts[$i]->options = $db->loadObjectList();
			}

			$orderTotals = EshopHelper::getOrderTotals($orderId);

			//Payment custom fields here
			$form                = new RADForm(EshopHelper::getFormFields('B'));
			$this->paymentFields = $form->getFields();

			//Shipping custom fields here
			$form                 = new RADForm(EshopHelper::getFormFields('S'));
			$this->shippingFields = $form->getFields();
			$this->orderProducts  = $orderProducts;
			$this->orderTotals    = $orderTotals;
			$this->tax            = $tax;
			$this->currency       = $currency;

			// Clear cart and session
			if ($session->get('order_id'))
			{
				$cart->clear();
				$session->clear('shipping_method');
				$session->clear('shipping_methods');
				$session->clear('payment_method');
				$session->clear('guest');
				$session->clear('comment');
				$session->clear('order_id');
				$session->clear('coupon_code');
				$session->clear('voucher_code');
			}
		}

		$this->orderInfor = $orderInfor;

		if (EshopHelper::getConfigValue('completed_url') != '')
		{
			JFactory::getApplication()->redirect(EshopHelper::getConfigValue('completed_url'));
		}
		else
		{
			parent::display($tpl);
		}
	}


	/**
	 *
	 * Function to display cancel layout
	 *
	 * @param string $tpl
	 */
	protected function displayCancel($tpl)
	{
		parent::display($tpl);
	}
}