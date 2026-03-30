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