var html5QrCode;
var loader;
var orderModal;
var errorModal;
const cameraOptions = { facingMode: 'user' };
const scannerOptions = { fps: 10 };

function scannerStart() {
	html5QrCode.start(
		cameraOptions,
		scannerOptions,
		scannerSuccessCallback
	);
}

function scannerPause() {
	html5QrCode.pause();
}

function scannerResume() {
	html5QrCode.resume();
}

function showOrderModal(orderNumber, orderId) {
	orderModal.find('#orderModalLabel').html(orderNumber);
	orderModal.find('.modal-body').html('<iframe src="index.php?option=com_virtuemart&view=orders&task=edit&tmpl=component&virtuemart_order_id=' + orderId + '">');
	orderModal.modal('show');
}

function showErrorModal(error) {
	errorModal.find('.modal-body p').html(error);
	errorModal.modal('show');
}

const scannerSuccessCallback = (decodedText) => {
	loader.show();

	jQuery.ajax({
		cache: false,
		url: 'index.php?option=com_vmqrcode&task=order.find&format=json&' + token + '=1&order_number=' + decodedText,
		type: 'get',
		success: function(r) {
			if (r.success) {
				showOrderModal(decodedText, r.data.id);
				jQuery('#recent').html(r.data.recent);
			} else {
				if (r.message) {
				showErrorModal(r.message);
				}
			}
		},
		complete: function(r) {
			loader.hide();
		},
	});
};

jQuery(document).ready(function() {
	loader = jQuery('#loader');
	orderModal = jQuery('#orderModal');
	errorModal = jQuery('#errorModal');
	html5QrCode = new Html5Qrcode('scanner');

	orderModal.on('show', function () {
		scannerPause();
	});

	orderModal.on('hidden', function () {
		orderModal.find('#orderModalLabel').html('');
		orderModal.find('.modal-body').html('');
		scannerResume();
	});

	errorModal.on('show', function () {
		scannerPause();
	});

	errorModal.on('hidden', function () {
		errorModal.find('.modal-body p').html('');
		scannerResume();
	});

	scannerStart();

	jQuery('html,document').on('click', '.scanned-order', function(e) {
		e.preventDefault();
		var orderNumber = jQuery(this).data('order-number');
		var orderId = jQuery(this).data('id');

		showOrderModal(orderNumber, orderId);
	});
});