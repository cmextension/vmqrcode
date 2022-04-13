<?php
/**
 * @package     VMQRCODE
 * @subpackage  com_vmqrcode
 * @copyright   Copyright (C) 2022 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('jquery.framework');

$session = Factory::getSession();
$orders = $session->get('orders', [], 'com_vmqrcode');

$layout = new FileLayout('recent', JPATH_ROOT . '/administrator/components/com_vmqrcode/layouts');
$recent = $layout->render(['orders' => $orders]);

$doc = Factory::getDocument();
$doc->addStyleSheet('components/com_vmqrcode/assets/css/vmqrcode.css');
$doc->addScript('components/com_vmqrcode/assets/js/html5-qrcode.min.js');
$doc->addScript('components/com_vmqrcode/assets/js/scanner.js');
$doc->addScriptDeclaration('var token = "' . $session->getFormToken() . '";');
?>
<div class="vmqrcode">
	<div class="row-fluid">
		<div class="span6">
			<div class="scanner-container">
				<div id="scanner"></div>
			</div>
		</div>

		<div class="span6">
			<div id="recent"><?php echo $recent; ?></div>
		</span>
	</div>

	<div id="loader"><img src="components/com_vmqrcode/assets/img/loading.gif"></div>
</div>

<div id="orderModal" class="modal hide fade jviewport-width80" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
	<div class="modal-body"></div>
</div>

<div id="errorModal" class="modal hide fade jviewport-width40" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="errorModalLabel"><?php echo Text::_('COM_VMQRCODE_ERROR'); ?></h3>
	</div>
	<div class="modal-body"><p></p></div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo Text::_('COM_VMQRCODE_CLOSE'); ?></button>
	</div>
</div>