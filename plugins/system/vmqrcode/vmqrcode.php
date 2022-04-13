<?php
/**
 * @package     VMQRCODE
 * @subpackage  plg_system_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/plugins/system/vmqrcode/vendor/autoload.php';

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * Show QR code in VirtueMart's order.
 *
 * @package     VMQRCODE
 * @subpackage  plg_system_vmqrcode
 * @since       1.0.0
 */
class PlgSystemVMQRCODE extends CMSPlugin
{
	public function __construct(&$subject, $config)
	{
		$this->options = new QROptions([
			'eccLevel'		=> QRCode::ECC_L,
			'outputType'	=> QRCode::OUTPUT_MARKUP_SVG,
			'version'		=> 5]);

		parent::__construct($subject, $config);
	}

	/**
	 * Replace QR code tag in HTML.
	 *
	 * @return   void
	 *
	 * @since    1.0.0
	 */
	public function onAfterRender()
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$option = $input->get('option');

		if ($option !== 'com_virtuemart') return;

		$view = $input->get('view');
		$task = $input->get('task');
		$id = $input->getUint('virtuemart_order_id', 0);

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$orderNumber = null;

		if (!$app->isClient('site'))
		{
			if ($view !== 'orders' || $task !== 'callInvoiceView' || !$id) return;

			$query->select($db->qn('order_number'))
				->from($db->qn('#__virtuemart_orders'))
				->where($db->qn('virtuemart_order_id') . ' = ' . $db->q($id));

			$orderNumber = $db->setQuery($query)->loadResult();

			if (!$orderNumber) return;
		}
		else
		{
			$layout = $input->get('layout');

			if ($view !== 'orders' || $layout !== 'details') return;

			$orderNumber = $input->get('order_number');

			if (!$id && !$orderNumber) return;
		}

		$body = $app->getBody();

		if (strpos($body, '{vm:qrcode}') === false) return;

		$qrcode = (new QRCode($this->options))->render($orderNumber);
		$size = (int) $this->params->get('qr_code_size', 0);
		$style = '';

		if ($size > 0)
		{
			$style = ' style="width: ' . $size . 'px"';
		}

		$body = str_replace('{vm:qrcode}', '<img src="' . $qrcode . '"' . $style . '>', $body);

		$app->setBody($body);
	}

	/**
	 * Replace QR code tag in VirtueMart email.
	 *
	 * @param   object   $view          VirtuemartViewInvoice object
	 * @param   object   $data          Joomla\CMS\Mail\Mail object
	 * @param   boolean  $noVendorMail
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function plgVmOnSendVmEmail($view, $mailer, $noVendorMail)
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$option = $input->get('option');

		if ($option !== 'com_virtuemart') return;

		$view = $input->get('view');
		$task = $input->get('task');
		$id = $input->getUint('virtuemart_order_id', 0);

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$orderNumber = null;

		if (!$app->isClient('site'))
		{
			if ($view !== 'orders' || $task !== 'updatestatus' || !$id) return;

			$query->select($db->qn('order_number'))
				->from($db->qn('#__virtuemart_orders'))
				->where($db->qn('virtuemart_order_id') . ' = ' . $db->q($id));

			$orderNumber = $db->setQuery($query)->loadResult();

			if (!$orderNumber) return;
		}
		else
		{
			$confirm = $input->getUint('confirm', 0);

			if ($view !== 'cart' || $task !== 'updatecart' || $confirm !== 1) return;

			require_once JPATH_ROOT . '/components/com_virtuemart/helpers/cart.php';

			$cart = VirtueMartCart::getCart();
			$orderNumber = isset($cart->order_number) ? $cart->order_number : null;

			if (!$orderNumber) return;
		}

		$body = $mailer->Body;

		if (strpos($body, '{vm:qrcode}') === false) return;

		$qrcode = (new QRCode($this->options))->render($orderNumber);
		$size = (int) $this->params->get('qr_code_size', 0);
		$style = '';

		if ($size > 0)
		{
			$style = ' style="width: ' . $size . 'px"';
		}

		$body = str_replace('{vm:qrcode}', '<img src="' . $qrcode . '"' . $style . '>', $body);

		$mailer->setBody($body);
	}
}