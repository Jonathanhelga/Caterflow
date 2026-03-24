// 1. Create a function
// 2. Execute it immediately
// Change your call in DataPullRequest to pass data.results instead of just data:
// customerTableForming(data.results, headers);

function customerTableForming(customerArray, headers){
    let resultsDiv = document.getElementById('results-table-container');
    resultsDiv.innerHTML = '';
    
    const table = document.createElement('table');
    const tblHead = document.createElement('thead');
    const tblBody = document.createElement('tbody');
    const headerRow = document.createElement('tr');
    headers.forEach(head => {
        const headerCell = document.createElement("th");
        headerCell.textContent = head;
        headerRow.appendChild(headerCell);
    });
    tblHead.appendChild(headerRow);

    customerArray.forEach(customer => {
        const newRow = document.createElement("tr");
        Object.entries(customer).forEach(([key, value]) => {
            if(key === 'cust_id'){ 
                newRow.setAttribute('data-id', value);
                return;
            }
            const td = document.createElement('td');
            td.textContent = value;
            newRow.appendChild(td);
        });
        const actionCell = document.createElement('td');
        
        const detailsBtn = document.createElement('button');
        detailsBtn.textContent = 'Details';
        detailsBtn.className = 'action-btn';
        
        const modifyBtn = document.createElement('button');
        modifyBtn.textContent = 'Modify';
        modifyBtn.className = 'action-btn';

        detailsBtn.addEventListener('click', () => {
            const idValue = newRow.getAttribute('data-id');
            customerDetailRequest(idValue);
        });

        actionCell.appendChild(detailsBtn);
        actionCell.appendChild(modifyBtn);
        newRow.appendChild(actionCell);
        tblBody.appendChild(newRow);
    });

    table.appendChild(tblHead);
    table.appendChild(tblBody);
    resultsDiv.appendChild(table);
}

function supplierTableForming(supplierArray, headers){
    let resultsDiv = document.getElementById('results-table-container');
    resultsDiv.innerHTML = '';
    
    const table = document.createElement('table');
    const tblHead = document.createElement('thead');
    const tblBody = document.createElement('tbody');
    const headerRow = document.createElement('tr');
    headers.forEach(head => {
        const headerCell = document.createElement('th');
        headerCell.textContent = head;
        headerRow.appendChild(headerCell);
    });
    tblHead.appendChild(headerRow);
    supplierArray.forEach(supplier => {
        const newRow = document.createElement('tr');
        Object.entries(supplier).forEach(([key, value]) => {
            if(key === 'vendor_id'){ 
                newRow.setAttribute('data-id', value);
                return;
            }
            const td = document.createElement('td');
            td.textContent = value;
            newRow.appendChild(td);
        });
        const actionCell = document.createElement('td');
        const detailsBtn = document.createElement('button');
        detailsBtn.textContent = 'Details';
        detailsBtn.className = 'action-btn';
        const modifyBtn = document.createElement('button');
        modifyBtn.textContent = 'Modify';
        modifyBtn.className = 'action-btn';
        detailsBtn.addEventListener('click', () => {
            const idValue = newRow.getAttribute('data-id');
            supplierDetailRequest(idValue);
        });
        actionCell.appendChild(detailsBtn);
        actionCell.appendChild(modifyBtn);
        newRow.appendChild(actionCell);
        tblBody.appendChild(newRow);
    });
    table.appendChild(tblHead);
    table.appendChild(tblBody);
    resultsDiv.appendChild(table);
}

function productTableForming(productArray, headers){
    let resultsDiv = document.getElementById('results-table-container');
    resultsDiv.innerHTML = '';
    
    const table = document.createElement('table');
    const tblHead = document.createElement('thead');
    const tblBody = document.createElement('tbody');
    const headerRow = document.createElement('tr');
    headers.forEach(head => {
        const headerCell = document.createElement('th');
        headerCell.textContent = head;
        headerRow.appendChild(headerCell);
    });
    tblHead.appendChild(headerRow);
    productArray.forEach(product => {
        const newRow = document.createElement('tr');
        Object.entries(product).forEach(([key, value]) => {
            if(key === 'product_id'){ 
                newRow.setAttribute('data-id', value);
                return;
            }
            const td = document.createElement('td');
            td.textContent = value;
            newRow.appendChild(td);
        });
        
        const actionCell = document.createElement('td');
        const detailsBtn = document.createElement('button');
        detailsBtn.textContent = 'Details';
        detailsBtn.className = 'action-btn';
        const modifyBtn = document.createElement('button');
        modifyBtn.textContent = 'Modify';
        modifyBtn.className = 'action-btn';
        detailsBtn.addEventListener('click', () => {
            const idValue = newRow.getAttribute('data-id');
            productDetailRequest(idValue);
        });
        actionCell.appendChild(detailsBtn);
        actionCell.appendChild(modifyBtn);
        newRow.appendChild(actionCell);
        tblBody.appendChild(newRow);
    });
    table.appendChild(tblHead);
    table.appendChild(tblBody);
    resultsDiv.appendChild(table);
}

function orderTableForming(orderArray, headers){
    let resultsDiv = document.getElementById('results-table-container');
    resultsDiv.innerHTML = '';
    
    const table = document.createElement('table');
    const tblHead = document.createElement('thead');
    const tblBody = document.createElement('tbody');
    const headerRow = document.createElement('tr');
    headers.forEach(head => {
        const headerCell = document.createElement('th');
        headerCell.textContent = head;
        headerRow.appendChild(headerCell);
    });
    tblHead.appendChild(headerRow);
    orderArray.forEach(order => {
        const newRow = document.createElement('tr');
        Object.values(order).forEach(columnValue => {
            const newCell = document.createElement('td');
            newCell.textContent = columnValue;
            newRow.appendChild(newCell);
        });
        const actionCell = document.createElement('td');
        const detailsBtn = document.createElement('button');
        detailsBtn.textContent = 'Details';
        detailsBtn.className = 'action-btn';
        const modifyBtn = document.createElement('button');
        modifyBtn.textContent = 'Modify';
        modifyBtn.className = 'action-btn';
        detailsBtn.addEventListener('click', () => {
            console.log("Details clicked for order: " + order.invoice_number);
            document.getElementById('detail-side-panel').classList.add('active');
        });
        actionCell.appendChild(detailsBtn);
        actionCell.appendChild(modifyBtn);
        newRow.appendChild(actionCell);
        tblBody.appendChild(newRow);
    });
    table.appendChild(tblHead);
    table.appendChild(tblBody);
    resultsDiv.appendChild(table);
}

function installmentTableForming(installmentArray, headers){
    const resultsDiv = document.getElementById('results-table-container');
    resultsDiv.innerHTML = '';

    const table = document.createElement('table');
    const tblHead = document.createElement('thead');
    const tblBody = document.createElement('tbody');

    const colWidths = ['25%', '15%', '15%', '12%', '10%', '23%'];
    const colgroup = document.createElement('colgroup');
    colWidths.forEach(w => {
        const col = document.createElement('col');
        col.style.width = w;
        colgroup.appendChild(col);
    });
    table.style.tableLayout = 'fixed';
    table.appendChild(colgroup);

    // Build header row
    const headerRow = document.createElement('tr');
    headers.forEach(head => {
        const th = document.createElement('th');
        th.textContent = head;
        headerRow.appendChild(th);
    });
    tblHead.appendChild(headerRow);
    table.appendChild(tblHead);

    // Pre-count tenures per invoice (used for rowspan)
    const rowSpanCount = installmentArray.reduce((acc, row) => {
        acc[row.invoice_number] = (acc[row.invoice_number] || 0) + 1;
        return acc;
    }, {});

    // Columns covered by rowspan — skip them in the normal cell loop
    const GROUPED_COLS = new Set(['invoice_number', 'customer_name', 'payment_status']);

    let tracker = null;
    installmentArray.forEach(installment => {
        const newRow = document.createElement('tr');
        const isFirstRow = tracker !== installment.invoice_number;

        if(isFirstRow){
            tracker = installment.invoice_number;
            const span = rowSpanCount[installment.invoice_number];
            const infoCell = document.createElement('td');
            infoCell.rowSpan = span;
            infoCell.innerHTML = `<strong>${installment.invoice_number}</strong><br><span>${installment.customer_name}</span>`;
            newRow.appendChild(infoCell);
        }
        Object.entries(installment).forEach(([key, value]) => {
            if(GROUPED_COLS.has(key)) return;
            const td = document.createElement('td');
            td.textContent = value;
            newRow.appendChild(td);
        });

        if(isFirstRow){
            const span = rowSpanCount[installment.invoice_number];
            const statusCell = document.createElement('td');
            statusCell.rowSpan = span;
            statusCell.innerHTML = `<strong>${installment.payment_status}</strong>`;
            newRow.appendChild(statusCell);
        }

        // Tag every row with its invoice so we can group-highlight on hover
        newRow.dataset.invoice = installment.invoice_number;

        newRow.addEventListener('mouseover', () => {
            tblBody.querySelectorAll(`tr[data-invoice="${installment.invoice_number}"]`)
                   .forEach(r => r.classList.add('row-group-hover'));
        });
        newRow.addEventListener('mouseleave', () => {
            tblBody.querySelectorAll(`tr[data-invoice="${installment.invoice_number}"]`)
                   .forEach(r => r.classList.remove('row-group-hover'));
        });

        tblBody.appendChild(newRow);
    });

    table.appendChild(tblBody);
    resultsDiv.appendChild(table);
}  

async function DataPullRequest(template_cat){
    const url = `Search/search_logic.php?category=${template_cat}`;
    try{
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        console.log(data);
        if(template_cat === "customers"){
            headers = [ 'Category', 'Code', 'Name', 'Email', 'Phone Number', 'Actions'];
            customerTableForming(data.results, headers);
        }
        else if(template_cat === "suppliers"){
            headers = [ 'Vendor Name', 'Product', 'Contact Person', 'Phone Number', 'Actions'];
            supplierTableForming(data.results, headers);
        }
        else if(template_cat === "products"){
            headers = [ 'Product Code', 'Product Name', 'Price', 'Cost', 'Actions'];
            productTableForming(data.results, headers);
        }
        else if(template_cat === "orders"){
            headers = [ 'Invoice Number', 'Customer Name', 'Delivery Date', 'Total Amount', 'Payment Status', 'Actions'];
            orderTableForming(data.results, headers);
        }
        else{
            headers = [ 'Order Information', 'Due Date', 'Total Amount', 'Total Paid','Status', 'Payment Status'];
            installmentTableForming(data.results, headers);
        }
    }
    catch(error){
        console.error('Error fetching data:', error);
    }
}

async function customerDetailRequest(cust_id){
    const url = `Search/search_logic.php?cust_id=${encodeURIComponent(cust_id)}`;
    try {
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if(!data.success) { console.error('Detail fetch failed:', data.error); return; }

        const info = data.info;
        const tenures = data.unpaid_tenures;

        const panel = document.getElementById('detail-side-panel');
        document.getElementById('detail-title').textContent = info.name;
        document.getElementById('detail-subtitle').textContent = `${info.type} · ${info.cust_code}`;

        const totalUnpaid = tenures.reduce((sum, t) => sum + Number(t.amount_due), 0);

        const tenureRows = tenures.length === 0
            ? `<tr class="no-data"><td colspan="4">No pending payments</td></tr>`
            : tenures.map(t => `
                <tr>
                    <td>${t.invoice_number} <span style="color:#9ca3af">#${t.tenure_number}</span></td>
                    <td>${t.due_date}</td>
                    <td>${Number(t.amount_due).toLocaleString()}</td>
                    <td>${Number(t.amount_paid).toLocaleString()}</td>
                </tr>`).join('');

        document.getElementById('detail-form-container').innerHTML = `
            <div class="detail-section">
                <p><strong>Phone:</strong> ${info.phone}</p>
                <p><strong>Email:</strong> ${info.email ?? '-'}</p>
                <p><strong>City:</strong> ${info.city ?? '-'}</p>
                <p><strong>Address:</strong> ${info.address_line ?? '-'}</p>
            </div>
            <div class="detail-section">
                <div class="detail-stats">
                    <div class="stat-card">
                        <span class="stat-value">${info.total_orders}</span>
                        <span class="stat-label">Total Orders</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">Rp. ${totalUnpaid.toLocaleString()}</span>
                        <span class="stat-label">Total Unpaid</span>
                    </div>
                </div>
            </div>
            <div class="detail-section">
                <p><strong>Unpaid Installments</strong></p>
                <div class="tenure-table-wrapper">
                    <table class="detail-table tenure-table">
                        <thead><tr><th>Invoice</th><th>Due Date</th><th>Amount Due</th><th>Paid</th></tr></thead>
                        <tbody>${tenureRows}</tbody>
                    </table>
                </div>
            </div>`;

        panel.classList.add('active');
    } catch (error) {
        console.error('Error fetching customer details:', error);
    }
}

async function supplierDetailRequest(vendor_id){
    const url = `Search/search_logic.php?vendor_id=${encodeURIComponent(vendor_id)}`;
    try {
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if(!data.success) { console.error('Detail fetch failed:', data.error); return; }

        const info = data.info;
        const panel = document.getElementById('detail-side-panel');

        document.getElementById('detail-title').textContent = info.name;
        document.getElementById('detail-subtitle').textContent = `Vendor · ${info.vendor_code}`;

        document.getElementById('detail-form-container').innerHTML = `
            <div class="detail-section">
                <p><strong>Contact Person:</strong> ${info.contact_person ?? '-'}</p>
                <p><strong>Phone:</strong> ${info.phone}</p>
            </div>
            <div class="detail-section">
                <p><strong>Supplied Product</strong></p>
                <div class="detail-stats">
                    <div class="stat-card">
                        <span class="stat-value">${info.product_name}</span>
                        <span class="stat-label">Product</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">Rp. ${Number(info.cost).toLocaleString()}</span>
                        <span class="stat-label">Cost of Goods</span>
                    </div>
                </div>
            </div>`;

        panel.classList.add('active');
    } catch (error) {
        console.error('Error fetching supplier details:', error);
    }
}

async function productDetailRequest(product_id){
    const url = `Search/search_logic.php?product_id=${encodeURIComponent(product_id)}`;
    try {
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if(!data.success) { console.error('Detail fetch failed:', data.error); return; }

        const info = data.info;
        const orders = data.product_sold;
        const panel = document.getElementById('detail-side-panel');

        document.getElementById('detail-title').textContent = info.product_name;
        document.getElementById('detail-subtitle').textContent = `${info.product_code} · ${info.source}${info.category_name ? ' · ' + info.category_name : ''}`;

        const margin = info.price > 0
            ? ((info.price - info.cost) / info.price * 100).toFixed(1)
            : '—';

        const orderRows = orders.length === 0
            ? `<tr class="no-data"><td colspan="4">No orders yet</td></tr>`
            : orders.map(o => `
                <tr>
                    <td>${o.invoice_number}<br><span style="color:#9ca3af;font-size:11px">${o.customer_name}</span></td>
                    <td>${o.order_date.slice(0, 10)}</td>
                    <td style="text-align:center">${o.quantity}</td>
                    <td>Rp. ${Number(o.subtotal).toLocaleString()}</td>
                </tr>`).join('');

        document.getElementById('detail-form-container').innerHTML = `
            <div class="detail-section">
                <div class="detail-stats">
                    <div class="stat-card">
                        <span class="stat-value">Rp. ${Number(info.price).toLocaleString()}</span>
                        <span class="stat-label">Selling Price</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">Rp. ${Number(info.cost).toLocaleString()}</span>
                        <span class="stat-label">Cost of Goods</span>
                    </div>
                </div>
                <div class="detail-stats">
                    <div class="stat-card">
                        <span class="stat-value">${margin}%</span>
                        <span class="stat-label">Margin</span>
                    </div>
                </div>
            </div>
            <div class="detail-section">
                <p><strong>Orders Containing This Product</strong></p>
                <div class="tenure-table-wrapper">
                    <table class="detail-table tenure-table">
                        <thead><tr><th>Invoice</th><th>Date</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>${orderRows}</tbody>
                    </table>
                </div>
            </div>`;

        panel.classList.add('active');
    } catch (error) {
        console.error('Error fetching product details:', error);
    }
}

async function orderDetailRequest(){

}
(function () {
    'use strict';

    const selectedElement = document.getElementById('category-selector');
    DataPullRequest(selectedElement.value);
    selectedElement.addEventListener('change', () => {
        DataPullRequest(selectedElement.value);
    })
    const closeDetailPanel = document.getElementById('close-detail-btn');
    closeDetailPanel.addEventListener('click', () => {
        document.getElementById('detail-side-panel').classList.remove('active');
        document.getElementById('detail-title').textContent = '';
        document.getElementById('detail-subtitle').textContent = '';
        document.getElementById('detail-form-container').innerHTML = '';
    });
}());

