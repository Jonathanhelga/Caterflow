// 1. Create a function
// 2. Execute it immediately
(function () {
    'use strict';//Immediately Invoked Function Expression (IIFE)

    const input = document.getElementById('category');
    const datalist = document.getElementById('category-list');
    const saveBtn  = document.getElementById('save-category-btn');
    const msgEl = document.getElementById('category-msg');
    
    if (!input || !datalist || !saveBtn || !msgEl) return;

    function showMsg(text, type) {
        msgEl.textContent  = text;
        msgEl.className    = 'category-msg category-msg--' + type;
        clearTimeout(msgEl._timer);
        msgEl._timer = setTimeout(() => {
            msgEl.textContent = '';
            msgEl.className   = 'category-msg';
        }, 3000);
    }

    function optionExists(name) {
        const lower = name.toLowerCase();
        return Array.from(datalist.options).some(o => o.value.toLowerCase() === lower);
    }

    function addOption(name) {
        const opt   = document.createElement('option');
        opt.value   = name;
        datalist.appendChild(opt);
    }

    saveBtn.addEventListener('click', async function () {
        const name = input.value.trim();

        if (!name) {
            showMsg('Please type a category name first.', 'error');
            input.focus();
            return;
        }

        if (optionExists(name)) {
            showMsg('"' + name + '" is already saved.', 'info');
            return;
        }

        saveBtn.disabled    = true;
        saveBtn.textContent = 'Saving…';

        const formData = new FormData();
        formData.append('name', name); //equivalent to sending POST name = Electronics
        try {
            const response = await fetch('Product/save_category.php', {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error(`Server returned ${response.status}`);
            
            const data = await response.json();
            if(data.success){
                if(!optionExists(name)){ addOption(name); }
                const message = data.already_exists 
                ? `"${data.category.name}" already exists — it has been added to the list.`
                : `"${data.category.name}" saved successfully!`;
                showMsg(message, data.already_exists ? 'info' : 'success');
                input.value = data.category.name;
            }
            else{
                showMsg('Error: ' + (data.error || 'Could not save category.'), 'error');
            }
        } catch (error) {
            showMsg('Network error — please try again.', 'error');
            console.error(err);
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save';
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();   
            saveBtn.click();
        }
    });
}());
