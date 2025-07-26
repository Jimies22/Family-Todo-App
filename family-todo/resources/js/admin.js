// Admin User Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when search input changes (with debounce)
    const searchInput = document.getElementById('search');
    const roleSelect = document.getElementById('role');
    const sortBySelect = document.getElementById('sort_by');
    const sortOrderSelect = document.getElementById('sort_order');
    
    let searchTimeout;

    // Auto-submit on search input change (with 500ms delay)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchInput.closest('form').submit();
            }, 500);
        });
    }

    // Auto-submit on filter changes
    [roleSelect, sortBySelect, sortOrderSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });

    // Highlight search terms in results
    const searchTerm = new URLSearchParams(window.location.search).get('search');
    if (searchTerm) {
        const nameCells = document.querySelectorAll('td:first-child .text-sm.font-medium');
        const emailCells = document.querySelectorAll('td:nth-child(2) .text-sm');
        
        [...nameCells, ...emailCells].forEach(cell => {
            const text = cell.textContent;
            const highlightedText = text.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<mark class="bg-yellow-200 px-1 rounded">${match}</mark>`
            );
            if (highlightedText !== text) {
                cell.innerHTML = highlightedText;
            }
        });
    }

    // Add loading state to form submission
    const form = document.querySelector('form[method="GET"]');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Searching...';
                submitBtn.disabled = true;
            }
        });
    }
}); 