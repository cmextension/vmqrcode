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
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Controller class for VirtueMart order.
 *
 * @package     VMQRCODE
 * @subpackage  com_vmqrcode
 * @since       1.0.0
 */
class VMQRCodeControllerOrder extends BaseController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Order', $prefix = 'VMQRCodeModel', $config = ['ignore_request' => true])
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Find VirtueMart order by order number.
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function find()
	{
		$app = Factory::getApplication();

		if (!Session::checkToken('get'))
		{
			echo new JsonResponse(null, Text::_('JINVALID_TOKEN'), true);

			return true;
		}

		$orderNumber = $app->input->get('order_number');

		if (!$orderNumber)
		{
			echo new JsonResponse(null, Text::_('COM_VMQRCODE_ERROR_ORDER_NUMBER_EMPTY'), true);

			return true;
		}

		$orderId = $this->getModel()->getOrderId($orderNumber);

		if ($orderId)
		{
			$session = Factory::getSession();
			$orders = $session->get('orders', [], 'com_vmqrcode');

			if (!isset($orders[$orderNumber]))
			{
				$orders = array_merge([$orderNumber => [
					'id'	=> $orderId,
					'date'	=> Factory::getDate()->toSql()
				]], $orders);

				$max = ComponentHelper::getParams('com_vmqrcode')->get('recent_order_quantity', 10);

				if (count($orders) > $max)
				{
					array_pop($orders);
				}

				$session->set('orders', $orders, 'com_vmqrcode');
			}

			$layout = new FileLayout('recent', JPATH_ROOT . '/administrator/components/com_vmqrcode/layouts');
			$recent = $layout->render(['orders' => $orders]);

			echo new JsonResponse(['id' => $orderId, 'recent' => $recent]);
		}
		else
		{
			echo new JsonResponse(null, Text::sprintf('COM_VMQRCODE_ERROR_ORDER_NOT_FOUND', $orderNumber, $orderNumber), true);
		}

		return true;
	}
}