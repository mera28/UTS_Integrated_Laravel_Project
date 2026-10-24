/**
 * Zahiyyah's Fit - Master Data Client Utilities
 */
document.addEventListener('DOMContentLoaded', function () {
    // Auto dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 5000);
    });
});

/**
 * Confirm deletion modal helper
 */
function confirmDelete(formId, itemName) {
    if (confirm('Apakah Anda yakin ingin menghapus data [' + itemName + ']? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById(formId).submit();
    }
}
