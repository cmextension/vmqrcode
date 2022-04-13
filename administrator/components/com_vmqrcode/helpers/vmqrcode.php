<?php
/**
 * @package     VMQRCode
 * @subpackage  com_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/**
 * Helper class for com_vmqrcode.
 *
 * @package     VMQRCode
 * @subpackage  com_vmqrcode
 * @since       1.0.0
 */
class VMQRCodeHelper
{
	/**
	 * @param   string  $errorMessage  Error message.
	 * @param   string  $errorCode     Error code.
	 * 
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public static function throwException($errorMessage, $errorCode)
	{
		if (version_compare(JVERSION, '4.0.0-alpha1', '<'))
		{
			JError::raiseError($errorCode, $errorMessage);
		}
		else
		{
			throw new GenericDataException($errorMessage, $errorCode);
		}

		return false;
	}
}