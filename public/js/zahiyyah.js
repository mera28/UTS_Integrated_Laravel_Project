/**
 * Zahiyyah's Fit - Master Data & Transaction Utilities
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

    // Auto initialize calculations if purchase table exists
    if (document.getElementById('purchaseItemsTable')) {
        calculatePurchaseTotals();
    }
});

/**
 * Confirm deletion / cancellation modal helper
 */
function confirmDelete(formId, itemName) {
    if (confirm('Apakah Anda yakin ingin menghapus data [' + itemName + ']? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById(formId).submit();
    }
}

function confirmAction(formId, message) {
    if (confirm(message)) {
        document.getElementById(formId).submit();
    }
}

/**
 * Format currency number into Indonesian Rupiah format
 */
function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

/**
 * Purchase (Stock-In) Dynamic Item Operations
 */
let purchaseRowIndex = 100;

function addPurchaseRow() {
    const tableBody = document.querySelector('#purchaseItemsTable tbody');
    if (!tableBody) return;

    // Get options from existing select or template
    const templateSelect = document.querySelector('.product-select-template');
    if (!templateSelect) return;

    purchaseRowIndex++;
    const tr = document.createElement('tr');
    tr.className = 'purchase-item-row';
    tr.innerHTML = `
        <td class="text-center row-number">${tableBody.children.length + 1}</td>
        <td>
            <select name="items[${purchaseRowIndex}][product_id]" class="form-select form-select-custom product-selector" onchange="onPurchaseProductChange(this)" required>
                ${templateSelect.innerHTML}
            </select>
            <div class="small text-muted current-stock-info mt-1">Pilih pakaian untuk melihat info stok</div>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted">Rp</span>
                <input type="number" step="1000" min="0" name="items[${purchaseRowIndex}][unit_cost]" class="form-control form-control-custom item-unit-cost" value="0" oninput="calculatePurchaseTotals()" required>
            </div>
        </td>
        <td>
            <input type="number" min="1" name="items[${purchaseRowIndex}][quantity]" class="form-control form-control-custom item-quantity text-center" value="1" oninput="calculatePurchaseTotals()" required>
        </td>
        <td class="text-end fw-bold text-dark item-subtotal">Rp 0</td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="removePurchaseRow(this)" title="Hapus Baris">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(tr);
    updateRowNumbers();
    calculatePurchaseTotals();
}

function removePurchaseRow(btn) {
    const tableBody = document.querySelector('#purchaseItemsTable tbody');
    if (tableBody.children.length <= 1) {
        alert('Transaksi pembelian harus memiliki minimal 1 baris produk outfit.');
        return;
    }
    btn.closest('tr').remove();
    updateRowNumbers();
    calculatePurchaseTotals();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#purchaseItemsTable tbody tr');
    rows.forEach((row, idx) => {
        const numCol = row.querySelector('.row-number');
        if (numCol) numCol.textContent = idx + 1;
    });
}

function onPurchaseProductChange(select) {
    const row = select.closest('tr');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price') || 0;
    const stock = selectedOption.getAttribute('data-stock') || 0;
    const sku = selectedOption.getAttribute('data-sku') || '';

    const costInput = row.querySelector('.item-unit-cost');
    const stockInfo = row.querySelector('.current-stock-info');

    if (costInput && (!costInput.value || costInput.value === '0')) {
        costInput.value = price;
    }

    if (stockInfo) {
        if (sku) {
            stockInfo.innerHTML = `<span class="badge bg-light text-dark border me-1">SKU: ${sku}</span> Stok Saat Ini: <strong>${stock} pcs</strong>`;
        } else {
            stockInfo.textContent = 'Pilih pakaian untuk melihat info stok';
        }
    }

    calculatePurchaseTotals();
}

function calculatePurchaseTotals() {
    let grandTotal = 0;
    let totalItems = 0;
    const rows = document.querySelectorAll('#purchaseItemsTable tbody tr');

    rows.forEach(row => {
        const costInput = row.querySelector('.item-unit-cost');
        const qtyInput = row.querySelector('.item-quantity');
        const subtotalEl = row.querySelector('.item-subtotal');

        const cost = parseFloat(costInput ? costInput.value : 0) || 0;
        const qty = parseInt(qtyInput ? qtyInput.value : 0) || 0;
        const subtotal = cost * qty;

        if (subtotalEl) {
            subtotalEl.textContent = formatRupiah(subtotal);
        }

        grandTotal += subtotal;
        totalItems += qty;
    });

    const grandTotalEl = document.getElementById('purchaseGrandTotal');
    const totalItemsEl = document.getElementById('purchaseTotalItems');

    if (grandTotalEl) grandTotalEl.textContent = formatRupiah(grandTotal);
    if (totalItemsEl) totalItemsEl.textContent = totalItems + ' pcs';
}
