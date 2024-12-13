import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

import tinymce from "tinymce";

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
        sidebar: document.querySelector("#sidebar"),
        overlay: document.querySelector("#overlay"),
        toggleSidebarBtn: document.querySelector("#toggleSidebar"),
        closeSidebarBtn: document.querySelector("#closeSidebar"),
    };

    function initNavigation() {
        // console.log('initNavigation called');
        // console.log(navigationElements);

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
// Fungsi untuk membatasi teks dengan elipsis
function truncateText(elements, maxLength) {
    elements.forEach((element) => {
        const text = element.textContent;
        if (text.length > maxLength) {
            element.textContent = text.substring(0, maxLength) + "...";
        }
    });
}

// Pilih semua elemen yang ingin dibatasi teksnya
const textElements = document.querySelectorAll(".elipsis");
const tagElements = document.querySelectorAll(".jdl-items");

// Batasi teks menjadi 100 karakter
truncateText(textElements, 120);
truncateText(tagElements, 200);

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
