import './bootstrap';

document.addEventListener('tax-rows-updated', (event) => {
    // Check if taxRows exists and is an array
    if (event.detail && Array.isArray(event.detail.taxRows)) {
        // Reinitialize Select2 for all remaining rows
        event.detail.taxRows.forEach((row, index) => {
            // Use try-catch to prevent a single failure from stopping the entire process
            try {
                $(`#tax_type_select_${index}`).select2('destroy').select2();
                $(`#atc_select_${index}`).select2('destroy').select2();
                $(`#coa_select_${index}`).select2('destroy').select2();
            } catch (error) {
                console.error(`Error reinitializing Select2 for row ${index}:`, error);
            }
        });
    } else {
        console.error('Invalid tax rows data:', event.detail);
    }
});