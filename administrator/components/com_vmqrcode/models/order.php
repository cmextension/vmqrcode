<?php
/**
 * @package     VMQRCODE
 * @subpackage  com_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\Filter\InputFilter;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Model class for for VirtueMart order.
 *
 * @package     VMQRCODE
 * @subpackage  com_vmqrcode
 * @since       1.0.0
 */
class VMQRCodeModelOrder extends BaseDatabaseModel
{
	/**
	 * Get ID of Virtuemart order from its order number.
	 *
	 * @param   string  $orderNumber Order number.
	 *
	 * @return  integer
	 *
	 * @since   1.0.0
	 */
	public function getOrderId($orderNumber)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('virtuemart_order_id'))
			->from($db->qn('#__virtuemart_orders'))
			->where($db->qn('order_number') . ' = ' . $db->q($orderNumber));

		$orderId = $db->setQuery($query)->loadResult();

		return $orderId;
	}
}