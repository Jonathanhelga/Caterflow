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