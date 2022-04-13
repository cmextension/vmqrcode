<?php
/**
 * @package     VMQRCode
 * @subpackage  com_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Main controller of com_vmqrcode.
 *
 * @package     VMQRCode
 * @subpackage  com_vmqrcode
 * @since       1.0.0
 */
class VMQRCodeController extends BaseController
{
	/**
	 * Default view.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $default_view = 'scanner';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  BaseController
	 *
	 * @since   1.0.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		return parent::display();
	}
}
