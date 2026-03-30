const picker    = document.getElementById('customer-pick');
const hiddenId  = document.getElementById('form-cust-id');
const submitBtn = document.getElementById('submit-btn');
const listEl    = document.getElementById('contacts-list');
const card      = document.querySelector('.contact-card');
const TYPE_BADGE = {
    'Purchasing': 'badge-purchasing',
    'Payment':    'badge-payment',
    'Receiving':  'badge-receiving'
};


function renderContacts(contacts, customerName) {
    if (!contacts.length) {
        listEl.innerHTML = `<p class="panel-placeholder">No contacts yet for <strong>${customerName}</strong>.</p>`;
        return;
    }

    const grouped = {};
    contacts.forEach(c => {
        if (!grouped[c.type]) grouped[c.type] = [];
        grouped[c.type].push(c);
    });

    let html = '';
    for (const [type, list] of Object.entries(grouped)) {
        html += `<div class="contact-group">
            <span class="type-badge ${TYPE_BADGE[type] || ''}">${type}</span>
            <div class="contact-cards">`;
        list.forEach(c => {
            html += 
                `<div class="contact-item">
                    <div class="contact-name">${(c.name)}</div>
                    <div class="contact-phone">📞 ${(c.phone)}</div>
                    ${c.notes ? `<div class="contact-notes">${(c.notes)}</div>` : ''}
                </div>`;
        });
        html += `</div></div>`;
    }
    listEl.innerHTML = html;
}

async function loadContacts(custId) {
    listEl.innerHTML = '<p class="panel-placeholder loading">Loading…</p>';
    const url = `customer_contact.php?fetch_contacts=1&cust_id=${custId}`;
    try {
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if(!data.success) { listEl.innerHTML = `<p class="panel-placeholder error">Failed to load contacts.</p>`; return; }
        renderContacts(data.contacts, data.customer_name);
    } catch (error) {
        listEl.innerHTML = `<p class="panel-placeholder error">Network error.</p>`;
    }
}

function onCustomerChange(custId) {
    hiddenId.value    = custId;
    submitBtn.disabled = !custId;
    if (custId) {
        loadContacts(custId);
    } else {
        listEl.innerHTML = '<p class="panel-placeholder">Select a customer to view their contacts.</p>';
    }
}

picker.addEventListener('change', () => onCustomerChange(picker.value));

// Auto-load if a customer was pre-selected (after POST redirect)
const preSelected = parseInt(card.dataset.selected, 10);
if (preSelected) {
    submitBtn.disabled = false;
    loadContacts(preSelected);
}