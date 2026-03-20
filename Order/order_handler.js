let total = 0;
let elements = [];
document.addEventListener('DOMContentLoaded', () => {

    let addItemBtn = document.getElementById('new-item__button');
    let removeItemBtn = document.getElementById('remove-item__button');

    const container = document.getElementById('items-container');
    const template = document.getElementById('add-new-item__template');

    // Function to calculate and update subtotal for a specific row
    const updateSubtotal = (row) => {
        const select = row.querySelector('.item-select');
        const quantityInput = row.querySelector('.item-quantity');
        const subtotalPara = row.querySelector('.item-subtotal');
        
        let price = 0;
        if (select && select.options[select.selectedIndex]) {
            price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
        }
        
        const quantity = parseInt(quantityInput.value) || 0;
        const subtotal = price * quantity;
        
        if (subtotalPara) {
            subtotalPara.textContent = subtotal.toLocaleString('id-ID'); // Formatting amount nicely
        }
    };

    // Event delegation to capture events from dynamically added elements
    container.addEventListener('change', (e) => {
        if (e.target.classList.contains('item-select')) {
            updateSubtotal(e.target.closest('.item-row'));
        }
    });

    container.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-quantity')) {
            updateSubtotal(e.target.closest('.item-row'));
        }
    });

    addItemBtn.addEventListener('click', () => {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    });

    removeItemBtn.addEventListener('click', () => {
        const currentItems = container.querySelectorAll('.item-row');
        if (currentItems.length > 0) {
            const lastItem = currentItems[currentItems.length - 1];
            container.removeChild(lastItem);
        }
        else { alert("No items to remove!"); }
    });

    addItemBtn.click();
})