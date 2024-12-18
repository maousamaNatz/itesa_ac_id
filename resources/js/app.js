import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

import tinymce from "tinymce";
import Swal from "sweetalert2";
// Import tema dan plugin yang diperlukan
import "tinymce/themes/silver";
import "tinymce/icons/default";
import "tinymce/models/dom";

// Import CSS yang dibutuhkan
import "tinymce/skins/ui/oxide/skin.min.css";
import "tinymce/skins/ui/oxide/content.min.css";
import "tinymce/skins/content/default/content.min.css";

// Import plugins
import "tinymce/plugins/advlist";
import "tinymce/plugins/autolink";
import "tinymce/plugins/lists";
import "tinymce/plugins/link";
import "tinymce/plugins/image";
import "tinymce/plugins/charmap";
import "tinymce/plugins/preview";
import "tinymce/plugins/anchor";
import "tinymce/plugins/searchreplace";
import "tinymce/plugins/visualblocks";
import "tinymce/plugins/code";
import "tinymce/plugins/fullscreen";
import "tinymce/plugins/insertdatetime";
import "tinymce/plugins/media";
import "tinymce/plugins/table";
import "tinymce/plugins/wordcount";

Alpine.plugin(persist);

window.Alpine = Alpine;

// Komponen notifikasi
Alpine.data("notifications", () => ({
    notifications: [],

    add(notification) {
        this.notifications.push({
            ...notification,
            id: Date.now(),
        });

        setTimeout(() => {
            this.remove(notification.id);
        }, 5000);
    },

    remove(id) {
        this.notifications = this.notifications.filter(
            (notification) => notification.id !== id
        );
    },
}));

// Komponen thumbnail preview
Alpine.data("thumbnailPreview", () => ({
    isLoading: false,
    hasError: false,
    errorMessage: "",
    previewUrl: "",

    init() {
        const form = document.querySelector('form');
        const thumbnailInput = document.getElementById('thumbnailInput');

        // Hapus atribut required dari input file
        thumbnailInput.removeAttribute('required');

        form.addEventListener('submit', (e) => {
            // Validasi thumbnail saat submit
            if (!thumbnailInput.files || !thumbnailInput.files[0]) {
                e.preventDefault();
                this.handleError("Thumbnail harus diisi");
                showNotification('error', 'Error!', 'Thumbnail harus diisi');
                return false;
            }
        });

        thumbnailInput.addEventListener("change", async (e) => {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    this.handleError("Ukuran gambar maksimal 2MB");
                    showNotification('error', 'Error!', 'Ukuran gambar maksimal 2MB');
                    thumbnailInput.value = '';
                    return;
                }

                // Validasi tipe file
                if (!["image/jpeg", "image/png", "image/jpg"].includes(file.type)) {
                    this.handleError("Format gambar harus JPG, JPEG, atau PNG");
                    showNotification('error', 'Error!', 'Format gambar harus JPG, JPEG, atau PNG');
                    thumbnailInput.value = '';
                    return;
                }

                this.isLoading = true;
                this.hasError = false;
                this.errorMessage = "";

                try {
                    this.previewUrl = URL.createObjectURL(file);
                    const previewContainer = document.getElementById('previewContainer');
                    const uploadContainer = document.getElementById('uploadContainer');
                    const thumbnailPreview = document.getElementById('thumbnailPreview');

                    thumbnailPreview.src = this.previewUrl;
                    previewContainer.classList.remove("hidden");
                    uploadContainer.classList.add("hidden");
                } catch (error) {
                    this.handleError("Terjadi kesalahan saat memproses gambar");
                    showNotification('error', 'Error!', 'Terjadi kesalahan saat memproses gambar');
                } finally {
                    this.isLoading = false;
                }
            }
        });
    },

    handleError(message) {
        this.hasError = true;
        this.errorMessage = message;
        this.previewUrl = "";

        const thumbnailInput = document.getElementById('thumbnailInput');
        const previewContainer = document.getElementById('previewContainer');
        const uploadContainer = document.getElementById('uploadContainer');
        const dropZone = document.getElementById('dropZone');

        thumbnailInput.value = "";
        previewContainer.classList.add("hidden");
        uploadContainer.classList.remove("hidden");
        dropZone.classList.add("border-red-500");
    }
}));

// Komponen kategori
Alpine.data("categorySelector", () => ({
    isOpen: false,
    selectedCategories: new Set(),

    init() {
        // Inisialisasi komponen
        this.$watch("selectedCategories", (value) => {
            this.updateHiddenInputs();
        });
    },

    toggleDropdown() {
        this.isOpen = !this.isOpen;
    },

    selectCategory(id, name) {
        if (!this.selectedCategories.has(id)) {
            this.selectedCategories.add(id);
            this.addCategoryTag(id, name);
            this.updateHiddenInputs();
        }
        this.isOpen = false;
    },

    removeCategory(id) {
        this.selectedCategories.delete(id);
        document.querySelector(`[data-tag-id="${id}"]`)?.remove();
        document.querySelector(`input[value="${id}"]`)?.remove();
    },

    addCategoryTag(id, name) {
        const tagsContainer = document.getElementById("selected_category_tags");
        const tag = document.createElement("div");
        tag.setAttribute("data-tag-id", id);
        tag.className =
            "bg-gray-200 rounded-full px-3 py-1 text-sm flex items-center gap-2";
        tag.innerHTML = `
                ${name}
                <button type="button"
                        class="text-gray-500 hover:text-gray-700"
                        @click="removeCategory(${id})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
        tagsContainer.appendChild(tag);
    },

    updateHiddenInputs() {
        const container = document.getElementById("selected_categories");
        container.innerHTML = "";
        this.selectedCategories.forEach((id) => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "category_ids[]";
            input.value = id;
            container.appendChild(input);
        });
    },

    filterCategories(event) {
        const searchTerm = event.target.value.toLowerCase();
        document.querySelectorAll(".category-list > div").forEach((item) => {
            const categoryName = item.getAttribute("data-name").toLowerCase();
            item.style.display = categoryName.includes(searchTerm)
                ? "block"
                : "none";
        });
    },
}));

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    const categorySelector = document.getElementById("categorySelector");
    if (!categorySelector) return;

    const toggleDropdown = document.getElementById("toggleDropdown");
    const categoryDropdown = document.getElementById("categoryDropdown");
    const categorySearch = document.getElementById("categorySearch");
    const selectedCategoryTags = document.getElementById(
        "selected_category_tags"
    );
    const selectedCategories = document.getElementById("selected_categories");
    const categoryItems = document.querySelectorAll(".category-item");

    let selectedCategorySet = new Set();

    // Toggle dropdown
    toggleDropdown.addEventListener("click", function (e) {
        e.stopPropagation();
        categoryDropdown.classList.toggle("hidden");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (!categorySelector.contains(e.target)) {
            categoryDropdown.classList.add("hidden");
        }
    });

    // Search functionality
    categorySearch.addEventListener("input", function (e) {
        const searchTerm = e.target.value.toLowerCase();
        categoryItems.forEach((item) => {
            const categoryName = item.getAttribute("data-name").toLowerCase();
            item.style.display = categoryName.includes(searchTerm)
                ? "block"
                : "none";
        });
    });

    // Select category
    categoryItems.forEach((item) => {
        item.addEventListener("click", function () {
            const categoryId = this.getAttribute("data-value");
            const categoryName = this.getAttribute("data-name");

            if (!selectedCategorySet.has(categoryId)) {
                selectedCategorySet.add(categoryId);
                addCategoryTag(categoryId, categoryName);
                addHiddenInput(categoryId);
            }

            categoryDropdown.classList.add("hidden");
        });
    });

    function addCategoryTag(categoryId, categoryName) {
        const tag = document.createElement("div");
        tag.className =
            "bg-gray-200 rounded-full px-3 py-1 text-sm flex items-center gap-2";
        tag.setAttribute("data-tag-id", categoryId);
        tag.innerHTML = `
                ${categoryName}
                <button type="button" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;

        tag.querySelector("button").addEventListener("click", function () {
            removeCategory(categoryId);
        });

        selectedCategoryTags.appendChild(tag);
    }

    function addHiddenInput(categoryId) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "category_ids[]";
        input.value = categoryId;
        selectedCategories.appendChild(input);
    }

    function removeCategory(categoryId) {
        selectedCategorySet.delete(categoryId);
        document.querySelector(`[data-tag-id="${categoryId}"]`)?.remove();
        document.querySelector(`input[value="${categoryId}"]`)?.remove();
    }

    // Form validation
    const form = categorySelector.closest("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            if (selectedCategorySet.size === 0) {
                e.preventDefault();
                alert("Pilih minimal satu kategori");
            }
        });
    }
});

// Script untuk kategori
document.addEventListener("DOMContentLoaded", function () {
    const navigationElements = {
        sidebar: document.querySelector(".sidebar"),
        overlay: document.querySelector("#overlay"),
        toggleSidebarBtn: document.querySelector("#toggleSidebar"),
        closeSidebarBtn: document.querySelector("#closeSidebar"),
    };

    function initNavigation() {

        // Pastikan semua elemen ada sebelum menambahkan event listener
        if (navigationElements.toggleSidebarBtn && navigationElements.sidebar) {
            navigationElements.toggleSidebarBtn.addEventListener(
                "click",
                function () {
                    // console.log('Toggle button clicked');
                    toggleSidebar();
                }
            );
        }

        if (navigationElements.closeSidebarBtn) {
            navigationElements.closeSidebarBtn.addEventListener(
                "click",
                function () {
                    closeSidebar();
                }
            );
        }

        if (navigationElements.overlay) {
            navigationElements.overlay.addEventListener("click", function () {
                closeSidebar();
            });
        }
    }

    function toggleSidebar() {
        // console.log('toggleSidebar called');
        if (!navigationElements.sidebar) return;

        const currentClasses = navigationElements.sidebar.className;
        // console.log('Current sidebar classes:', currentClasses);

        if (currentClasses.includes("-left-64")) {
            // Buka sidebar
            navigationElements.sidebar.classList.remove("-left-64");
            navigationElements.sidebar.classList.add("left-0");
            if (navigationElements.overlay) {
                navigationElements.overlay.classList.remove("hidden");
            }
        } else {
            // Tutup sidebar
            navigationElements.sidebar.classList.remove("left-0");
            navigationElements.sidebar.classList.add("-left-64");
            if (navigationElements.overlay) {
                navigationElements.overlay.classList.add("hidden");
            }
        }

        // console.log('Sidebar classes after toggle:', navigationElements.sidebar.className);
    }

    function closeSidebar() {
        if (!navigationElements.sidebar) return;

        navigationElements.sidebar.classList.remove("left-0");
        navigationElements.sidebar.classList.add("-left-64");
        if (navigationElements.overlay) {
            navigationElements.overlay.classList.add("hidden");
        }
    }

    // Inisialisasi navigasi
    initNavigation();
    const editor = document.querySelector("#editor");
    if (editor) {
        console.log("Editor element ditemukan");
        tinymce.init({
            selector: "#editor",
            height: 500,
            plugins: [
                "advlist",
                "autolink",
                "lists",
                "link",
                "image",
                "charmap",
                "preview",
                "anchor",
                "searchreplace",
                "visualblocks",
                "code",
                "fullscreen",
                "insertdatetime",
                "media",
                "table",
                "wordcount",
            ],
            toolbar:
                "undo redo | blocks | " +
                "bold italic backcolor | alignleft aligncenter " +
                "alignright alignjustify | bullist numlist outdent indent | " +
                "removeformat",
            content_style:
                "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
            branding: false,
            promotion: false,
            skin: false,
            content_css: false,
        });
    }

    // Menangani gambar
    const images = document.querySelectorAll("img");
    images.forEach((img) => {
        if (img.complete) {
            handleImageLoaded(img);
        } else {
            img.addEventListener("load", () => handleImageLoaded(img));
        }
    });

    // Menangani font
    if ("fonts" in document) {
        document.fonts.ready.then(() => {
            document.documentElement.classList.add("fonts-loaded");
        });
    }

    function handleImageLoaded(img) {
        // Pastikan gambar memiliki width dan height
        if (!img.hasAttribute("width") && !img.hasAttribute("height")) {
            img.setAttribute("width", img.offsetWidth);
            img.setAttribute("height", img.offsetHeight);
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const dropZone = document.getElementById("dropZone");
    if (!dropZone) return;

    const thumbnailInput = document.getElementById("thumbnailInput");
    const previewContainer = document.getElementById("previewContainer");
    const uploadContainer = document.getElementById("uploadContainer");
    const thumbnailPreview = document.getElementById("thumbnailPreview");
    const removeButton = document.getElementById("removeImage");
    const errorMessage = document.getElementById("errorMessage");

    // Fungsi untuk menangani file
    function handleFile(file) {
        // Reset error
        errorMessage.classList.add("hidden");

        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            showError("Ukuran gambar maksimal 2MB");
            return;
        }

        // Validasi tipe file
        if (!["image/jpeg", "image/png", "image/jpg"].includes(file.type)) {
            showError("Format gambar harus JPG, JPEG, atau PNG");
            return;
        }

        // Buat preview
        const reader = new FileReader();
        reader.onload = function (e) {
            thumbnailPreview.src = e.target.result;
            previewContainer.classList.remove("hidden");
            uploadContainer.classList.add("hidden");
        };
        reader.readAsDataURL(file);

        // Update input file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        thumbnailInput.files = dataTransfer.files;
    }

    // Fungsi untuk menampilkan error
    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.classList.remove("hidden");
        clearPreview();
    }

    // Fungsi untuk membersihkan preview
    function clearPreview() {
        thumbnailPreview.src = "";
        previewContainer.classList.add("hidden");
        uploadContainer.classList.remove("hidden");
        thumbnailInput.value = "";
    }

    // Event Listeners untuk Drag & Drop
    dropZone.addEventListener("dragover", function (e) {
        e.preventDefault();
        dropZone.classList.add("border-[#a10d05]");
    });

    dropZone.addEventListener("dragleave", function (e) {
        e.preventDefault();
        dropZone.classList.remove("border-[#a10d05]");
    });

    dropZone.addEventListener("drop", function (e) {
        e.preventDefault();
        dropZone.classList.remove("border-[#a10d05]");

        const file = e.dataTransfer.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Event Listener untuk klik upload
    dropZone.addEventListener("click", function () {
        thumbnailInput.click();
    });

    // Event Listener untuk file yang dipilih
    thumbnailInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Event Listener untuk tombol hapus
    removeButton.addEventListener("click", function (e) {
        e.stopPropagation(); // Mencegah event click dropZone
        clearPreview();
    });
});

// Komponen notifikasi global
window.notifications = function() {
    return {
        notifications: [],
        add(notification) {
            notification.id = Date.now();
            this.notifications.push(notification);

            // Hapus notifikasi setelah beberapa detik
            setTimeout(() => {
                this.remove(notification.id);
            }, notification.duration || 5000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(notification => notification.id !== id);
        }
    }
}

// Fungsi helper untuk menampilkan notifikasi
window.showNotification = function(type, title, message, duration = 5000) {
    window.dispatchEvent(new CustomEvent('notification', {
        detail: {
            type: type,
            title: title,
            message: message,
            duration: duration
        }
    }));
}

// Inisialisasi notifikasi dari PHP session
document.addEventListener('DOMContentLoaded', function() {
    // Cek notifikasi dari data-attribute
    const notification = document.querySelector('#server-notification');
    if (notification) {
        showNotification(
            notification.dataset.type,
            notification.dataset.title,
            notification.dataset.message
        );
        notification.remove();
    }

    // Cek notifikasi dari session flash message
    if (typeof serverNotification !== 'undefined') {
        showNotification(
            serverNotification.type,
            serverNotification.title,
            serverNotification.message
        );
    }
});

// Template SweetAlert untuk konfirmasi hapus
window.confirmDelete = function(options = {}) {
    const defaultOptions = {
        title: 'Apakah Anda yakin?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#a10d05',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    };

    // Gabungkan opsi default dengan opsi yang diberikan
    const finalOptions = { ...defaultOptions, ...options };

    return Swal.fire(finalOptions);
}

// Template SweetAlert untuk notifikasi sukses
window.showSuccess = function(message, title = 'Berhasil!') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'success',
        confirmButtonColor: '#a10d05',
        timer: 3000,
        timerProgressBar: true
    });
}

// Template SweetAlert untuk notifikasi error
window.showError = function(message, title = 'Error!') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'error',
        confirmButtonColor: '#a10d05'
    });
}

// Template SweetAlert untuk notifikasi warning
window.showWarning = function(message, title = 'Perhatian!') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        confirmButtonColor: '#a10d05'
    });
}

// Template SweetAlert untuk notifikasi info
window.showInfo = function(message, title = 'Informasi') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'info',
        confirmButtonColor: '#a10d05'
    });
}

// Template SweetAlert untuk konfirmasi custom
window.confirmAction = function(options = {}) {
    const defaultOptions = {
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dibatalkan!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#a10d05',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, lanjutkan!',
        cancelButtonText: 'Batal'
    };

    return Swal.fire({ ...defaultOptions, ...options });
}

// Contoh penggunaan pada event handler delete
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            const itemType = this.dataset.type || 'item'; // artikel/komentar/dll

            try {
                const result = await confirmDelete({
                    text: `Anda akan menghapus ${itemType} ini. Tindakan ini tidak dapat dibatalkan!`
                });

                if (result.isConfirmed) {
                    form.submit();
                }
            } catch (error) {
                showError('Terjadi kesalahan saat memproses permintaan.');
            }
        });
    });

    // Handle notifikasi dari server (jika ada)
    const serverNotification = document.querySelector('#server-notification');
    if (serverNotification) {
        const { type, message, title } = serverNotification.dataset;

        switch(type) {
            case 'success':
                showSuccess(message, title);
                break;
            case 'error':
                showError(message, title);
                break;
            case 'warning':
                showWarning(message, title);
                break;
            case 'info':
                showInfo(message, title);
                break;
        }

        serverNotification.remove();
    }
});

// Fungsi untuk membatasi teks
function limitText(selector, options = { wordLimit: null, charLimit: null }) {
    const elements = document.querySelectorAll(selector);

    elements.forEach(el => {
        // Pastikan mengambil teks asli dari data-original-text
        const originalText = el.getAttribute('data-original-text') || el.textContent;

        // Simpan teks asli jika belum ada
        if (!el.getAttribute('data-original-text')) {
            el.setAttribute('data-original-text', originalText);
        }

        let modifiedText = originalText;

        // Batasi berdasarkan jumlah kata
        if (options.wordLimit) {
            const words = originalText.trim().split(/\s+/);
            if (words.length > options.wordLimit) {
                modifiedText = words.slice(0, options.wordLimit).join(' ') + '...';
            }
        }

        // Batasi berdasarkan jumlah karakter
        if (options.charLimit) {
            if (originalText.length > options.charLimit) {
                modifiedText = originalText.slice(0, options.charLimit).trim() + '...';
            }
        }

        el.textContent = modifiedText;
    });
}

// Konfigurasi responsif yang lebih spesifik
const responsiveConfig = [
    {
        maxWidth: 480,
        selector: '.limited-text',
        options: {
            wordLimit: 20,  // Lebih pendek untuk mobile
            charLimit: 300
        }
    },
    {
        maxWidth: 768,
        selector: '.limited-text',
        options: {
            wordLimit: 40,  // Medium untuk tablet
            charLimit: 700
        }
    },
    {
        maxWidth: 1024,
        selector: '.limited-text',
        options: {
            wordLimit: 60, // Lebih panjang untuk desktop
            charLimit: 700
        }
    },
    {
        maxWidth: Infinity,
        selector: '.limited-text',
        options: {
            wordLimit: 100, // Maksimum untuk layar besar
            charLimit: 700
        }
    }
];

// Fungsi untuk menerapkan pembatasan responsif
function applyResponsiveQueries(config) {
    const width = window.innerWidth;

    // Cari konfigurasi yang sesuai dengan lebar layar
    const activeConfig = config.find(query => width <= query.maxWidth);

    if (activeConfig) {
        limitText(activeConfig.selector, activeConfig.options);
    }
}

// Event listener dengan debouncing untuk resize
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        applyResponsiveQueries(responsiveConfig);
    }, 50);
});

// Terapkan saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    applyResponsiveQueries(responsiveConfig);
});

// Terapkan setelah AJAX load jika ada
document.addEventListener('ajax-content-loaded', () => {
    applyResponsiveQueries(responsiveConfig);
});
