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
        Object.entries(order).forEach(([key, value]) => {
            const td = document.createElement('td');
            if(key === 'order_id'){ 
                newRow.setAttribute('data-id', value);
                return;
            }
            else if(key === 'payment_status'){
                const badge = document.createElement('span');
                badge.textContent = value;
                badge.className = `tenure-status-badge tenure-status-${value}`;
                td.appendChild(badge);
            } 
            else{td.textContent = value;}
            // const td = document.createElement('td');
            // td.textContent = value;
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
            orderDetailRequest(idValue);
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
    const GROUPED_COLS = new Set(['invoice_number', 'customer_name', 'order_id', 'tenure_id']);

    let tracker = null;
    // let activeStatusCell = null;

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
            if(GROUPED_COLS.has(key)) { return; }
            const td = document.createElement('td');
            if(key === 'status'){
                const badge = document.createElement('span');
                badge.textContent = value;
                badge.className = `tenure-status-badge tenure-status-${value}`;
                td.appendChild(badge);
            } 
            else { td.textContent = value; }
            newRow.appendChild(td);
        });

        if(isFirstRow){ 
            const span = rowSpanCount[installment.invoice_number];
            const actionCell = document.createElement('td');
            actionCell.rowSpan = span;
            const updateBtn = document.createElement('button');
            updateBtn.textContent = 'Update';
            updateBtn.className = 'action-btn';
            updateBtn.addEventListener('click', () => { 
                installmentDetailRequest(installment.order_id);
            });
            actionCell.appendChild(updateBtn)
            newRow.appendChild(actionCell);
        }

        newRow.dataset.invoice = installment.invoice_number;
        newRow.dataset.tenureId = installment.tenure_id;
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
            headers = [ 'Order Information', 'Due Date', 'Total Amount', 'Total Paid','Status', 'Action'];
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

async function orderDetailRequest(order_id){
    const url = `Search/search_logic.php?order_id=${encodeURIComponent(order_id)}`;
    try {
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if(!data.success) { console.error('Detail fetch failed:', data.error); return; }

        const info = data.info;
        const products = data.products;
        const tenure = data.tenure_summary;
        const panel = document.getElementById('detail-side-panel');

        document.getElementById('detail-title').textContent = info.invoice_number;
        document.getElementById('detail-subtitle').textContent = `${info.customer_name}`;

        const contactPairs = [
            ['Purchasing Contact', info.purchasing_contact_name, info.purchasing_contact_phone],
            ['Payment Contact',    info.payment_contact_name,    info.payment_contact_phone],
            ['Receiving Contact',  info.receiving_contact_name,  info.receiving_contact_phone],
        ];
        //filter used for filter out if the name is null
        const contactsHtml = contactPairs.filter(([label, name, phone]) => name)
            .map(([label, name, phone]) => `<p><strong>${label}:</strong> ${name} (${phone})</p>`)
            .join('');

        const productRows = products.length === 0
            ? `<tr class="no-data"><td colspan="4">No items</td></tr>`
            : products.map(p => `
                <tr>
                    <td>${p.product_name}</td>
                    <td style="text-align:center">${p.quantity}</td>
                    <td>Rp. ${Number(p.unit_price).toLocaleString()}</td>
                    <td>Rp. ${Number(p.subtotal).toLocaleString()}</td>
                </tr>`).join('');

        document.getElementById('detail-form-container').innerHTML = `
            <div class="detail-section">
                <p><strong>Order Date:</strong> ${info.order_date.slice(0, 10)}</p>
                <p><strong>Delivery Date:</strong> ${info.delivery_date.slice(0, 10)}</p>
                <p><strong>Total Amount:</strong> Rp. ${Number(info.total_amount).toLocaleString()}</p>
                ${contactsHtml ? `<div class="contacts-info">${contactsHtml}</div>` : ''}
                <p><strong>Order Status:</strong></p>
                <div class="status-chips">
                    ${['pending','processing','completed','cancelled'].map(s => `
                        <button class="status-chip status-chip-${s} ${s === info.status ? 'active' : ''}"
                                data-order-id="${order_id}"
                                data-status="${s}">${s}</button>`).join('')}
                </div>
            </div>
            <div class="detail-section">
                <div class="detail-stats">
                    <div class="stat-card stat-card-${info.payment_status}">
                        <span class="stat-value-${info.payment_status}"> ${info.payment_status} </span>
                        <span class="stat-label">Payment Status</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">${tenure.total_tenures}</span>
                        <span class="stat-label">Total Tenures</span>
                    </div>
                </div>
                <div class="detail-stats">
                    <div class="stat-card stat-card-paid">
                        <span class="stat-value-paid">${tenure.paid_tenures}</span>
                        <span class="stat-label">Paid</span>
                    </div>
                    <div class="stat-card stat-card-overdue">
                        <span class="stat-value-overdue">${tenure.unpaid_tenures}</span>
                        <span class="stat-label">Unpaid</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <p><strong>Order Items</strong></p>
                <div class="tenure-table-wrapper">
                    <table class="detail-table tenure-table">
                        <thead><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
                        <tbody>${productRows}</tbody>
                    </table>
                </div>
            </div>`;

        document.querySelectorAll('.status-chip').forEach(chip => {
            chip.addEventListener('click', async () => {
                const newStatus = chip.dataset.status;
                const formData = new FormData();
                formData.append('action', 'update_order_status');
                formData.append('order_id', chip.dataset.orderId);
                formData.append('status', newStatus);

                const res = await fetch('Search/search_logic.php', { method: 'POST', body: formData });
                const result = await res.json();

                if(result.success){
                    document.querySelectorAll('.status-chip').forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    chip.classList.add('flash');
                    setTimeout(() => chip.classList.remove('flash'), 600);
                }
            });
        });

        panel.classList.add('active');
    } catch (error) {
        console.error('Error fetching order details:', error);
    }
}

async function installmentDetailRequest(order_id){
    console.log(order_id);
    const url = `Search/search_logic.php?tenure_order_id=${encodeURIComponent(order_id)}`;
    try {
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if(!data.success) { console.error('Detail fetch failed:', data.error); return; }

        const info = data.info;
        const tenures = data.tenures;
        const panel = document.getElementById('detail-side-panel');

        document.getElementById('detail-title').textContent = info.invoice_number;
        document.getElementById('detail-subtitle').textContent = `${info.customer_name}`;


        const tenureRows = tenures.length === 0
            ? `<div class="no-data">No items</div>`
            : tenures.map(tenure => {
                const statusClass = tenure.status.replace(' ', '-');
                return `
                <div class="tenure-card status-${statusClass}">
                    <div class="tenure-info">
                        <span class="tenure-label">${info.invoice_number}&nbsp;#${tenure.tenure_number}</span>
                        <span class="tenure-meta">Due: ${tenure.due_date}</span>
                        <span class="tenure-amount">Due Amount: ${tenure.amount_due} &nbsp;</span>
                        <span class="tenure-paid"> Paid: <span class="amount-paid">${tenure.amount_paid}</span></span>
                        <span class="tenure-status-badge tenure-status-${statusClass}">${tenure.status}</span>
                    </div>
                    <div class="tenure-input">
                        <label>Amount Received</label>
                        <input type="number" class="tenure-amount-input" data-tenure-id="${tenure.tenure_id}" value="${tenure.amount_paid}" min="0" ${tenure.status === 'paid' ? 'disabled' : ''}>
                    </div>
                    <button class="save-btn" ${tenure.status === 'paid' ? 'disabled' : ''}>${tenure.status === 'paid' ? 'Saved' : 'Save'}</button>
                </div>`;
            }).join('');
    
        document.getElementById('detail-form-container').innerHTML = `<div class="tenure-table-wrapper"> ${tenureRows} </div>`;
        
        document.querySelectorAll('.save-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const card = btn.closest('.tenure-card');
                const input = card.querySelector('.tenure-amount-input');

                const formData = new FormData();
                formData.append('action', 'update_tenure_status');
                formData.append('tenure_id', input.dataset.tenureId);
                formData.append('amount_paid', input.value);

                const res = await fetch('Search/search_logic.php', { method: 'POST', body: formData });
                const result = await res.json();

                if(result.success){
                    card.querySelector('.amount-paid').textContent = input.value;
                    const badge = card.querySelector('.tenure-status-badge');
                    badge.textContent = result.new_status;
                    badge.className = `tenure-status-badge tenure-status-${result.new_status}`;
                    if(result.new_status === 'paid'){
                        input.disabled = true;
                        btn.disabled = true;
                        btn.textContent = 'Saved';
                    }
                    const tableRow = document.querySelector(`tr[data-tenure-id="${input.dataset.tenureId}"]`);
                    if(tableRow){
                        const tableBadge = tableRow.querySelector('.tenure-status-badge');
                        if(tableBadge){
                            tableBadge.textContent = result.new_status;
                            tableBadge.className = `tenure-status-badge tenure-status-${result.new_status}`;
                        }
                    }
                } else {
                    alert(result.error);
                }
            });
        });
        panel.classList.add('active');
    } catch (error) {
        console.error('Error fetching installments details:', error);
    }
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

