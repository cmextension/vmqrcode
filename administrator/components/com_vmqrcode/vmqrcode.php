<?php
/**
 * @package     VMQRCode
 * @subpackage  com_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/vmqrcode.php';

if (!ComponentHelper::isEnabled('com_virtuemart', true) )
{
	return VMQRCodeHelper::throwException(Text::_('COM_VMQRCODE_ERROR_VIRTUEMART_REQUIRED'), 500);
}

if (!Factory::getUser()->authorise('core.manage', 'com_vmqrcode'))
{
	return VMQRCodeHelper::throwException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

$controller = BaseController::getInstance('VMQRCode');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
