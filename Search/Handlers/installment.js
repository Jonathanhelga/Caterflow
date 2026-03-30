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