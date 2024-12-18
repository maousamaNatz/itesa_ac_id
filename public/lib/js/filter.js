// Filter untuk Category
document.addEventListener('DOMContentLoaded', function() {
    // Cek keberadaan elemen sebelum menambahkan event listener
    const searchInput = document.querySelector('input[placeholder="Cari kategori..."]');
    const sortSelect = document.querySelector('select');
    const tableBody = document.querySelector('tbody');

    // Pastikan semua elemen yang dibutuhkan ada
    if (searchInput && sortSelect && tableBody) {
        const originalRows = Array.from(tableBody.querySelectorAll('tr'));

        // Fungsi untuk filter dan sort
        function filterAndSortTable() {
            let filteredRows = originalRows.filter(row => {
                const categoryName = row.querySelector('td:first-child')?.textContent.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();
                return categoryName?.includes(searchTerm);
            });

            // Sorting
            const sortValue = sortSelect.value;
            filteredRows.sort((a, b) => {
                const aValue = a.querySelector('td:first-child')?.textContent.toLowerCase();
                const bValue = b.querySelector('td:first-child')?.textContent.toLowerCase();

                switch(sortValue) {
                    case 'name_asc':
                        return aValue.localeCompare(bValue);
                    case 'name_desc':
                        return bValue.localeCompare(aValue);
                    case 'newest':
                        const aDate = new Date(a.querySelector('td:nth-child(4)')?.textContent);
                        const bDate = new Date(b.querySelector('td:nth-child(4)')?.textContent);
                        return bDate - aDate;
                    case 'oldest':
                        const aDateOld = new Date(a.querySelector('td:nth-child(4)')?.textContent);
                        const bDateOld = new Date(b.querySelector('td:nth-child(4)')?.textContent);
                        return aDateOld - bDateOld;
                    default:
                        return 0;
                }
            });

            // Update tampilan
            tableBody.innerHTML = '';
            if (filteredRows.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada kategori yang ditemukan
                        </td>
                    </tr>
                `;
            } else {
                filteredRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            }
        }

        // Event listeners
        searchInput.addEventListener('input', filterAndSortTable);
        sortSelect.addEventListener('change', filterAndSortTable);
    } else {
        console.log('Beberapa elemen yang dibutuhkan tidak ditemukan di halaman');
    }
});
