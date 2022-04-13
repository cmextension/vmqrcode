<?php
/**
 * @package     VMQRCODE
 * @subpackage  com_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * Main view.
 *
 * @package     VMQRCODE
 * @subpackage  com_vmqrcode
 * @since       1.0.0
 */
class VMQRCodeViewScanner extends HtmlView
{
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_VMQRCODE_MANAGER_SCANNER'), 'eye');

		if (Factory::getUser()->authorise('core.admin', 'com_vmqrcode'))
		{
			ToolbarHelper::preferences('com_vmqrcode');
		}
	}
}
