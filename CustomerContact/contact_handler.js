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

function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;

}

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
            html += `
                <div class="contact-item">
                    <div class="contact-name">${escHtml(c.name)}</div>
                    <div class="contact-phone">📞 ${escHtml(c.phone)}</div>
                    ${c.notes ? `<div class="contact-notes">${escHtml(c.notes)}</div>` : ''}
                </div>`;
        });
        html += `</div></div>`;
    }
    listEl.innerHTML = html;
}

function loadContacts(custId) {
    listEl.innerHTML = '<p class="panel-placeholder loading">Loading…</p>';
    fetch(`customer_contact.php?fetch_contacts=1&cust_id=${custId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                renderContacts(data.contacts, data.customer_name);
            } else {
                listEl.innerHTML = `<p class="panel-placeholder error">Failed to load contacts.</p>`;
            }
        })
        .catch(() => {
            listEl.innerHTML = `<p class="panel-placeholder error">Network error.</p>`;
        });
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