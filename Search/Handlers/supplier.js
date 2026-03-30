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
        // const modifyBtn = document.createElement('button');
        // modifyBtn.textContent = 'Modify';
        // modifyBtn.className = 'action-btn';
        detailsBtn.addEventListener('click', () => {
            const idValue = newRow.getAttribute('data-id');
            supplierDetailRequest(idValue);
        });
        actionCell.appendChild(detailsBtn);
        // actionCell.appendChild(modifyBtn);
        newRow.appendChild(actionCell);
        tblBody.appendChild(newRow);
    });
    table.appendChild(tblHead);
    table.appendChild(tblBody);
    resultsDiv.appendChild(table);
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