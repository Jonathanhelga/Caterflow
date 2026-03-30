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
