// Filter untuk Article
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('select[name="category"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const searchInput = document.querySelector('input[name="search"]');
    const tableBody = document.querySelector('tbody');
    const originalRows = Array.from(tableBody.querySelectorAll('tr'));

    function filterArticles() {
        let filteredRows = originalRows.filter(row => {
            const category = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase();
            const title = row.querySelector('td:first-child')?.textContent.toLowerCase();

            const categoryMatch = !categorySelect.value || category.includes(categorySelect.value.toLowerCase());
            const statusMatch = !statusSelect.value || status.includes(statusSelect.value.toLowerCase());
            const searchMatch = !searchInput.value || title.includes(searchInput.value.toLowerCase());

            return categoryMatch && statusMatch && searchMatch;
        });

        // Update tampilan
        tableBody.innerHTML = '';
        if (filteredRows.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada artikel yang ditemukan
                    </td>
                </tr>
            `;
        } else {
            filteredRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
        }

        // Update counter jika ada
        updateCounter(filteredRows.length);
    }

    function updateCounter(count) {
        const counter = document.querySelector('.text-gray-500');
        if (counter) {
            counter.textContent = `Menampilkan ${count} artikel`;
        }
    }

    // Debounce function untuk mencegah terlalu banyak request
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Event listeners dengan debounce untuk search
    const debouncedFilter = debounce(filterArticles, 300);

    categorySelect.addEventListener('change', filterArticles);
    statusSelect.addEventListener('change', filterArticles);
    searchInput.addEventListener('input', debouncedFilter);
});
