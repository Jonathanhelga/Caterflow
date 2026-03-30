// 1. Create a function
// 2. Execute it immediately
// Change your call in DataPullRequest to pass data.results instead of just data:

async function DataPullRequest(template_cat){
    const url = `Search/search_logic.php?category=${template_cat}`;
    try{
        const resultBox = document.getElementById('results-table-container');
        const response = await fetch(url);
        if(!response.ok) { throw new Error('Network response was not ok'); }
        const data = await response.json();
        if (!data.results || data.results.length < 1) {                                                                                       
            resultBox.innerHTML = '<p class="empty-state">Nothing here yet.</p>';
            return;                                                                                                                           
        } 
        console.log(data.results.length);
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

