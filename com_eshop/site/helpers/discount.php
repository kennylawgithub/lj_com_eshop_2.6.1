<?php
/**
 * @version        2.6.2
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

class EshopDiscount
{

	/**
	 *
	 * Function to get Costs, passed by reference to update
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getCosts(&$totalData, &$total, &$taxes)
	{
		if (EshopHelper::getConfigValue('enable_checkout_discount'))
		{
			$totalRange       = EshopHelper::getConfigValue('total_range');
			$totalRangeArr    = explode(';', $totalRange);
			$discountRange    = EshopHelper::getConfigValue('discount_range');
			$discountRangeArr = explode(';', $discountRange);
			$cost             = 0;

			if (count($totalRangeArr) && count($discountRangeArr))
			{
				$cart     = new EshopCart();
				$subTotal = $cart->getSubTotal();

				for ($i = 0; $n = count($totalRangeArr), $i < ($n - 1); $i++)
				{
					if ($subTotal >= $totalRangeArr[$i] && $subTotal <= $totalRangeArr[$i + 1])
					{
						if (strpos($discountRangeArr[$i], '%'))
						{
							$percentageCost = str_replace('%', '', $discountRangeArr[$i]);
							$cost           = round($subTotal * $percentageCost / 100, 2);
						}
						else
						{
							$cost = $discountRangeArr[$i];
						}
						break;
					}
					else
					{
						continue;
					}
				}

				if ($i == ($n - 1) && $subTotal > $totalRangeArr[$i])
				{
					if (strpos($discountRangeArr[$i], '%'))
					{
						$percentageCost = str_replace('%', '', $discountRangeArr[$i]);
						$cost           = round($subTotal * $percentageCost / 100, 2);
					}
					else
					{
						$cost = $discountRangeArr[$i];
					}
				}
			}

			if ($cost != 0)
			{
				$currency    = new EshopCurrency();
				$totalData[] = array(
					'name'  => 'checkout_discount',
					'title' => JText::_('ESHOP_CHECKOUT_DISCOUNT'),
					'text'  => $currency->format(-$cost),
					'value' => -$cost);
				$total -= $cost;
			}
			
			//Apply tax for checkout discount
			if (EshopHelper::getConfigValue('tax_class') > 0)
			{
				$tax       = new EshopTax(EshopHelper::getConfig());
				
				$taxRates = $tax->getTaxRates($cost, EshopHelper::getConfigValue('tax_class'));
				
				foreach ($taxRates as $taxRate)
				{
					if (!isset($taxes[$taxRate['tax_rate_id']]))
					{
						$taxes[$taxRate['tax_rate_id']] = -($taxRate['amount']);
					}
					else
					{
						$taxes[$taxRate['tax_rate_id']] -= $taxRate['amount'];
					}
				}
			}
		}
	}
}