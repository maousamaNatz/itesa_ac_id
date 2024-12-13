function searchAndScroll() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const sections = document.querySelectorAll("section");
  let page_src = document.getElementById("page-src");
  let ico_src = document.getElementById("ico-src");
  let open_about_src = document.getElementById("open-about-src");

  let found = false;

  // Mendefinisikan kata kunci dan URL terkait
  const redirectLinks = [
    {
      keywords: ["pmb", "itesa pmb", "web itesa pmb", "web pmb"],
      url: "https://pmb.itesa.ac.id/",
    },
    {
      keywords: [
        "career",
        "web itesa career",
        "itesa career",
        "web career",
        "web icc",
        "icc",
      ],
      url: "https://itesacareercenter.itesa.ac.id/",
    },
    {
      keywords: [
        "jurnal",
        "web jurnal",
        "itesa jurnal",
        "web itesa jurnal",
        "jasdm",
        "web jasdm",
        "web itesa jasdm",
        "itesa jasdm",
      ],
      url: "https://journal.itesa.ac.id/index.php/jasdm",
    },
  ];

  // Memeriksa apakah kata kunci yang dimasukkan cocok dengan salah satu kata kunci redirect
  for (let item of redirectLinks) {
    if (item.keywords.includes(keyword)) {
      window.open(item.url, "_blank"); // Arahkan ke URL yang sesuai di tab baru
      return; // Keluar dari fungsi setelah pengalihan
    }
  }

  if (keyword === "close") {
    // Menghapus kelas "native-page" dan mengganti ikon jika kata kunci adalah "close"
    sections.forEach((section) => {
      page_src.classList.remove("native-page");
      ico_src.classList.add("bx-search");
      ico_src.classList.remove("bx-x");
      open_about_src.classList.remove("about-src");
      page_nav.classList.add("slide-nav");
    });
    return; // Keluar dari fungsi jika kata kunci adalah "close"
  }

  sections.forEach((section) => {
    if (section.textContent.toLowerCase().includes(keyword)) {
      section.scrollIntoView({ behavior: "smooth" });
      found = true;
    }
  });

  if (!found) {
    alert("keyword yang anda cari tidak ada.");
  }

  document.getElementById("searchInput").value = "";
}

// Menambahkan event listener untuk memicu pencarian saat tombol Enter ditekan
document
  .getElementById("searchInput")
  .addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      searchAndScroll();
    }
  });

document.addEventListener("keydown", function (event) {
  if (event.ctrlKey && (event.key === "S" || event.key === "s")) {
    event.preventDefault(); // Mencegah aksi default browser (save)
    let page_src = document.getElementById("page-src");
    page_src.classList.add("native-page");
    document.getElementById("searchInput").focus(); // Fokus ke input pencarian
  }
});
