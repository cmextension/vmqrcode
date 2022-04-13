<?php
defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

extract($displayData);
?>
<?php if (count($orders)) : ?>
	<h3><?php echo Text::_('COM_VMQRCODE_RECENT_SCANNED_ORDERS'); ?></h3>
	<table class="table table-stripe">
		<thead>
			<tr>
				<th><?php echo Text::_('COM_VMQRCODE_ORDER_NUMBER'); ?></th>
				<th><?php echo Text::_('COM_VMQRCODE_SCANNED_TIME'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $orderNumber => $data) : ?>
			<tr>
				<td><a href="#" class="scanned-order" data-order-number="<?php echo $orderNumber; ?>" data-id="<?php echo $data['id']; ?>"><?php echo $orderNumber; ?></td>
				<td><?php echo HTMLHelper::_('date', $data['date'], 'H:i'); ?></td>
				<td><?php echo \JHtmlDate::relative($data['date']); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>