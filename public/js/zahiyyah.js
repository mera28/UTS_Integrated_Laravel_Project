/**
 * Zahiyyah's Fit - Master Data, POS Kasir, E-Commerce & Payment Gateway Utilities
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

    // Auto initialize calculations if sales POS table exists
    if (document.getElementById('saleItemsTable')) {
        calculateSaleTotals();
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
 * Copy text to clipboard helper
 */
function copyToClipboard(text, successMessage) {
    navigator.clipboard.writeText(text).then(function () {
        alert(successMessage || 'Berhasil disalin ke clipboard: ' + text);
    }).catch(function (err) {
        console.error('Gagal menyalin:', err);
    });
}

/**
 * Countdown timer for Payment Gateway page
 */
function startPaymentCountdown(durationSeconds, displayId) {
    let timer = durationSeconds;
    const display = document.getElementById(displayId);
    if (!display) return;

    const interval = setInterval(function () {
        const hours = parseInt(timer / 3600, 10);
        const minutes = parseInt((timer % 3600) / 60, 10);
        const seconds = parseInt(timer % 60, 10);

        const hStr = hours < 10 ? '0' + hours : hours;
        const mStr = minutes < 10 ? '0' + minutes : minutes;
        const sStr = seconds < 10 ? '0' + seconds : seconds;

        display.textContent = hStr + ':' + mStr + ':' + sStr;

        if (--timer < 0) {
            clearInterval(interval);
            display.textContent = '00:00:00 (Kedaluwarsa)';
            display.classList.add('text-danger');
        }
    }, 1000);
}

/**
 * Purchase (Stock-In) Dynamic Item Operations
 */
let purchaseRowIndex = 100;

function addPurchaseRow() {
    const tableBody = document.querySelector('#purchaseItemsTable tbody');
    if (!tableBody) return;

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

/**
 * Sales POS (Point of Sale) Dynamic Item Operations
 */
let saleRowIndex = 100;

function addSaleRow() {
    const tableBody = document.querySelector('#saleItemsTable tbody');
    if (!tableBody) return;

    const templateSelect = document.querySelector('.pos-product-template');
    if (!templateSelect) return;

    saleRowIndex++;
    const tr = document.createElement('tr');
    tr.className = 'sale-item-row';
    tr.innerHTML = `
        <td class="text-center pos-row-number">${tableBody.children.length + 1}</td>
        <td>
            <select name="items[${saleRowIndex}][product_id]" class="form-select form-select-custom pos-product-selector" onchange="onSaleProductChange(this)" required>
                ${templateSelect.innerHTML}
            </select>
            <div class="d-flex align-items-center gap-2 mt-1 small">
                <span class="text-muted pos-stock-info">Pilih produk pakaian</span>
                <span class="text-danger fw-semibold d-none pos-stock-alert">Stok tidak cukup!</span>
            </div>
        </td>
        <td style="width: 140px;">
            <input type="text" name="items[${saleRowIndex}][selected_size]" class="form-control form-control-custom pos-item-size" placeholder="S / M / L / XL">
        </td>
        <td style="width: 140px;">
            <input type="text" name="items[${saleRowIndex}][selected_color]" class="form-control form-control-custom pos-item-color" placeholder="Warna">
        </td>
        <td class="text-end fw-bold text-dark pos-item-price" style="width: 140px;">Rp 0</td>
        <td style="width: 100px;">
            <input type="number" min="1" name="items[${saleRowIndex}][quantity]" class="form-control form-control-custom pos-item-qty text-center" value="1" oninput="calculateSaleTotals()" required>
        </td>
        <td class="text-end fw-extrabold text-danger pos-item-subtotal" style="width: 150px;">Rp 0</td>
        <td class="text-center" style="width: 50px;">
            <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="removeSaleRow(this)" title="Hapus">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(tr);
    updateSaleRowNumbers();
    calculateSaleTotals();
}

function removeSaleRow(btn) {
    const tableBody = document.querySelector('#saleItemsTable tbody');
    if (tableBody.children.length <= 1) {
        alert('Transaksi kasir harus memiliki minimal 1 item busana outfit.');
        return;
    }
    btn.closest('tr').remove();
    updateSaleRowNumbers();
    calculateSaleTotals();
}

function updateSaleRowNumbers() {
    const rows = document.querySelectorAll('#saleItemsTable tbody tr');
    rows.forEach((row, idx) => {
        const numCol = row.querySelector('.pos-row-number');
        if (numCol) numCol.textContent = idx + 1;
    });
}

function onSaleProductChange(select) {
    const row = select.closest('tr');
    const selectedOption = select.options[select.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
    const stock = parseInt(selectedOption.getAttribute('data-stock') || 0);
    const sku = selectedOption.getAttribute('data-sku') || '';
    const size = selectedOption.getAttribute('data-size') || '';
    const color = selectedOption.getAttribute('data-color') || '';

    const priceEl = row.querySelector('.pos-item-price');
    const stockInfo = row.querySelector('.pos-stock-info');
    const sizeInput = row.querySelector('.pos-item-size');
    const colorInput = row.querySelector('.pos-item-color');
    const qtyInput = row.querySelector('.pos-item-qty');

    if (priceEl) priceEl.textContent = formatRupiah(price);
    if (sizeInput && !sizeInput.value) sizeInput.value = size;
    if (colorInput && !colorInput.value) colorInput.value = color;

    if (stockInfo) {
        if (sku) {
            stockInfo.innerHTML = `<span class="badge bg-light text-dark border me-1">${sku}</span> Sisa Stok: <strong>${stock} pcs</strong>`;
            if (qtyInput) qtyInput.setAttribute('max', stock);
        } else {
            stockInfo.textContent = 'Pilih produk pakaian';
        }
    }

    calculateSaleTotals();
}

function calculateSaleTotals() {
    let grandTotal = 0;
    let totalItems = 0;
    const rows = document.querySelectorAll('#saleItemsTable tbody tr');

    rows.forEach(row => {
        const select = row.querySelector('.pos-product-selector');
        const qtyInput = row.querySelector('.pos-item-qty');
        const subtotalEl = row.querySelector('.pos-item-subtotal');
        const alertEl = row.querySelector('.pos-stock-alert');

        if (!select || select.selectedIndex < 0) return;
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const stock = parseInt(selectedOption.getAttribute('data-stock') || 0);
        const qty = parseInt(qtyInput ? qtyInput.value : 0) || 0;

        if (stock > 0 && qty > stock) {
            if (alertEl) alertEl.classList.remove('d-none');
        } else {
            if (alertEl) alertEl.classList.add('d-none');
        }

        const subtotal = price * qty;
        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);

        grandTotal += subtotal;
        totalItems += qty;
    });

    const grandTotalEl = document.getElementById('posGrandTotal');
    const totalItemsEl = document.getElementById('posTotalItems');
    const changeAmountEl = document.getElementById('posChangeAmount');
    const cashGivenInput = document.getElementById('posCashGiven');

    if (grandTotalEl) grandTotalEl.textContent = formatRupiah(grandTotal);
    if (totalItemsEl) totalItemsEl.textContent = totalItems + ' pcs';

    if (cashGivenInput && changeAmountEl) {
        const cashGiven = parseFloat(cashGivenInput.value || 0);
        const change = cashGiven - grandTotal;
        if (change >= 0) {
            changeAmountEl.textContent = formatRupiah(change);
            changeAmountEl.classList.remove('text-danger');
            changeAmountEl.classList.add('text-success');
        } else {
            changeAmountEl.textContent = '-' + formatRupiah(Math.abs(change)) + ' (Kurang)';
            changeAmountEl.classList.remove('text-success');
            changeAmountEl.classList.add('text-danger');
        }
    }
}
