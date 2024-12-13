let btn_src = document.getElementById("btn-src");
let ico_src = document.getElementById("ico-src");
let page_src = document.getElementById("page-src");
let btn_wel = document.getElementById("btn-wel");
let page_wel = document.getElementById("page-wel");
let btn_open_org = document.getElementById("btn-open-org");
let btn_close_org = document.getElementById("btn-close-org");
let page_org = document.getElementById("page-org");
let page_nav = document.getElementById("nav-tag");
let header = document.getElementById("header");
let open_about_src = document.getElementById("open-about-src");

function Open_Search() {
  ico_src.classList.toggle("bx-search");
  ico_src.classList.toggle("bx-x");
  page_nav.classList.remove("slide-nav");
  page_src.classList.toggle("native-page");
  open_about_src.classList.remove("about-src");
  document.getElementById("searchInput").value = "";
}

function Open_menu_Nav() {
  page_nav.classList.toggle("slide-nav");
}

function Open_about_SRC() {
  open_about_src.classList.toggle("about-src");
}

function Open_welcome() {
  page_wel.classList.add("pop-native");
}
function Close_welcome() {
  page_wel.classList.remove("pop-native");
}
function Open_org() {
  page_org.classList.add("box-org");
}
function Close_org() {
  page_org.classList.remove("box-org");
}

function Read_more() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("btn-more");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more";
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less";
    moreText.style.display = "flex";
  }
}
