<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('lib/css/style-rpl.css') }}" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <title>Fakultas Management Retail</title>
    <link rel="icon" href="{{ asset('lib/default_media/logos.png') }}" type="image/x-icon">
  </head>
  <body>
    <div id="container" class="container">
      <div class="wrap">
        <nav class="nav-search" id="page-src">
          <div>
            <figure class="src-input">
              <i class="bx bx-search"></i>
              <div class="input-src">
                <input id="searchInput" type="text" placeholder="Search . ." />
                <button onclick="searchAndScroll()">Search</button>
              </div>
            </figure>
            <figure class="card-about-src" id="open-about-src">
              <div>
                <article class="key-src">
                  <div>
                    <h4>close</h4>
                    <h6>command untuk menutup search</h6>
                  </div>
                </article>
                <article class="key-src">
                  <div>
                    <h4>pmb</h4>
                    <h6>command untuk menuju web PMB</h6>
                  </div>
                </article>
                <article class="key-src">
                  <div>
                    <h4>career</h4>
                    <h6>command untuk menuju web CAREER</h6>
                  </div>
                </article>
                <article class="key-src">
                  <div>
                    <h4>jurnal</h4>
                    <h6>command untuk menuju web JASDM</h6>
                  </div>
                </article>
              </div>
            </figure>
          </div>
          <article class="about-search">
            <button onclick="Open_about_SRC()">
              <i class="bx bx-question-mark"></i>
            </button>
          </article>
        </nav>

        <nav class="nav-main" id="nav-tag">
          <div>
            <figure class="tag-nav-title">
              <div>
                <figure class="nav-tag nav-title">
                  <article>
                    <img
                      src="/RPL/src/assets/image/logo-itesa-warna-pmb-itesa-1022x1024.png"
                      alt="logo itesa "
                    />
                    <div class="itesa-title">
                      <div>
                        <h4>itesa muhammadiyah</h4>
                        <h6>semarang</h6>
                      </div>
                    </div>
                  </article>
                </figure>
                <figure class="btn-menu-mbl">
                  <button onclick="Open_menu_Nav()">
                    <i class="bx bx-menu-alt-right"></i>
                  </button>
                </figure>
              </div>
            </figure>
            <nav class="menu-list-nav">
              <main>
                <div>
                  <figure
                    class="nav-tag nav-menu nav-btn-src"
                    onclick="Open_Search()"
                    id="btn-src"
                  >
                    <article>
                      <i class="bx bx-search" id="ico-src"></i>
                    </article>
                  </figure>
                  <figure class="nav-tag nav-menu">
                    <article>
                      <h4>lembaga</h4>
                      <i class="bx bx-chevron-down" id="down"></i>
                    </article>
                    <article class="drop-list">
                      <section>
                        <div>
                          <article>
                            <h5>BPM</h5>
                          </article>
                        </div>
                        <div>
                          <article>
                            <h5>LPPM</h5>
                          </article>
                        </div>
                        <div>
                          <article>
                            <h5>LAIK</h5>
                          </article>
                        </div>
                        <div>
                          <article>
                            <h5>BIRO</h5>
                          </article>
                        </div>
                      </section>
                    </article>
                  </figure>
                  <figure class="nav-tag nav-menu" id="nav-tag">
                    <article>
                      <h4>fakultas</h4>
                      <i class="bx bx-chevron-down" id="down"></i>
                    </article>
                    <article class="drop-list">
                      <section>
                        <div class="nav-drop2">
                          <article>
                            <h5>sains dan teknologi</h5>
                            <i class="bx bx-chevron-down"></i>
                          </article>
                          <article class="drop-list2">
                            <section>
                              <div>
                                <h5>d3 statistika</h5>
                              </div>
                              <div>
                                <h5>s1 sains aktuaria</h5>
                              </div>
                              <div>
                                <h5>s1 rekayasa perangkat lunak</h5>
                              </div>
                            </section>
                          </article>
                        </div>
                        <div class="nav-drop2">
                          <article>
                            <h5>ekonomi dan bisnis</h5>
                            <i class="bx bx-chevron-down"></i>
                          </article>
                          <article class="drop-list2">
                            <section>
                              <div>
                                <h5>s1 manajemen retail</h5>
                              </div>
                            </section>
                          </article>
                        </div>
                      </section>
                    </article>
                  </figure>
                  <figure class="nav-tag nav-menu">
                    <article>
                      <h4>jurnal</h4>
                    </article>
                  </figure>
                  <figure class="nav-tag nav-menu">
                    <article>
                      <h4>berita</h4>
                    </article>
                  </figure>
                  <a href="#section1">
                    <figure class="nav-tag nav-menu">
                      <article>
                        <h4>profile</h4>
                      </article>
                    </figure>
                  </a>
                  <figure class="nav-tag nav-menu">
                    <article>
                      <h4>pmb</h4>
                    </article>
                  </figure>
                </div>
              </main>
            </nav>
          </div>
        </nav>
        <!-- header -->
        <header id="header" class="header">
          <div id="prodi-head" class="prodi-head">
            <div id="head-title" class="head-title">
              <div class="title-prodi">
                <div>
                  <div id="title-content" class="title-content">
                    <div>
                      <h1 id="S1" class="">Fakultas</h1>
                      <div id="sub-judul" class="sub-judul">
                        <h1 id="rekayasa" class="">Manajemen</h1>
                        <h1 id="rpl" class="">retail</h1>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="image-content" class="image-content">
              <img
                src="/RPL/src/assets/image/Kampus-1-ITESA-Muhammadiyah-Semarang-pmb-itesa.jpeg"
                alt=""
              />
            </div>
          </div>
        </header>
        <!-- content -->
        <main id="content" class="content">
          <div id="main-content" class="main-content">
            <article id="content-desc" class="content-desc">
              <div id="second-txt" class="second-txt">
                <div id="card-visi" class="visi">
                  <div>
                    <h1 id="visi-id">Visi</h1>
                    <p id="visi-desc">
                      Menjadi Program Studi yang Unggul, Inovatif, dan
                      Profesional bidang retail Berdasarkan Nilai-Nilai Islam
                      yang Berkemajuan.
                    </p>
                  </div>
                </div>
                <div id="card-content" class="card-content fade-in">
                  <div>
                    <div id="card-1" class="card-1 fade-in">
                      <h1>Misi</h1>
                      <div class="list-txt fade-in">
                        <ul>
                          <li>
                            Menyelenggarakan pendidikan sarjana yang
                            mengedepankan perkembangan IPTEKS untuk menghasilkan
                            lulusan yang berkompetensi dalam bidang aktuaria
                            lunak berdasarkan nilai-nilai Islam serta berjiwa
                            technopreneur.
                          </li>
                          <li>
                            Melaksanakan riset untuk pengembangan keilmuan
                            Manajemen Retail dan pemenuhan kebutuhan lokal dan
                            nasional.
                          </li>
                          <li>
                            Menerapkan ilmu Manajemen Retail untuk mendukung
                            pembangunan nasional dan peningkatan taraf hidup
                            masyarakat.
                          </li>
                          <li>
                            Membangun kerja sama produktif dengan berbagai pihak
                            untuk mendukung peningkatan mutu akademik,
                            kompetensi lulusan, dan penguatan sumber daya.
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div id="card-2" class="card-2 fade-in">
                      <div class="list-card-txt fade-in">
                        <h1>Tujuan</h1>
                        <ol>
                          <li>
                            Untuk menghasilkan lulusan Sarjana Manajemen Retail
                            yang terampil dan profesional yang memiliki moral,
                            etika, dan tata nilai serta memiliki semangat untuk
                            mendahulukan kepentingan bangsa dan masyarakat luas.
                          </li>
                          <li>
                            Untuk mengembangkan dan menyebarluaskan IPTEKS dalam
                            keilmuan Manajemen Retail yang sesuai dengan
                            kebutuhan masyarakat dan perkembangan teknologi
                            terkini dengan menghasilkan karya yang dapat diakui
                            baik secara nasional maupun internasional.
                          </li>
                          <li>
                            Untuk menciptakan organisasi dengan tata kelola
                            institusi yang sehat, efektif dan efisien yang mampu
                            menjadi mitra kerja pemerintah, industri,
                            masyarakat, dan pihak yang terkait lainnya dalam
                            pengembangan keilmuan Manajemen Retail.
                          </li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </article>

            <!-- gallery/photo -->
            <div id="gallery-container" class="gallery-container fade-in">
              <div id="video-itesa" class="video-itesa fade-in">
                <div id="video">
                  <iframe
                    width="560"
                    height="315"
                    src="https://www.youtube.com/embed/ft30zcMlFao"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  >
                  </iframe>
                </div>
              </div>
              <div class="gallery-wrap fade-in">
                <div id="photo-column" class="photo-column fade-in">
                  <div id="image-column" class="image-column fade-in">
                    <img src="/RPL/src/assets/image/_ੈ✩‧₊˚.jpeg" alt="" />
                  </div>
                  <div id="image-column" class="image-column fade-in">
                    <img src="/RPL/src/assets/image/_ੈ✩‧₊˚.jpeg" alt="" />
                  </div>
                  <div id="image-column" class="image-column fade-in">
                    <img src="/RPL/src/assets/image/_ੈ✩‧₊˚.jpeg" alt="" />
                  </div>
                  <div id="image-column" class="image-column fade-in">
                    <img src="/RPL/src/assets/image/_ੈ✩‧₊˚.jpeg" alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
        <!-- berita -->
        <article id="news" class="news fade-in">
          <div id="article-wrap" class="article-wrap fade-in">
            <div id="article-content" class="article-content fade-in">
              <div class="head-content-text fade-in">
                <h1>Berita</h1>
                <h1 id="terkini" class="fade-in">ITESA</h1>
              </div>
              <div id="content" class="news-content fade-in">
                <div>
                  <div id="news-image" class="news-image fade-in">
                    <img
                      src="/RPL/src/assets/image/Morning Sky _ Minecraft Wallpaper (1).jpeg"
                      alt=""
                    />
                  </div>
                  <div id="news-title " class="news-title fade-in">
                    <div class="news-h1 fade-in">
                      <h1>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua
                      </h1>
                    </div>
                    <div id="date" class="date fade-in">
                      <p>12-10-2024</p>
                    </div>
                    <div id="btn  " class="btn fade-in">
                      <button>
                        Baca Selengkapnya
                        <i id="arrow" class="bx bx-right-arrow-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div id="content" class="news-content fade-in">
                <div>
                  <div class="news-image fade-in">
                    <img
                      src="/RPL/src/assets/image/Morning Sky _ Minecraft Wallpaper (1).jpeg"
                      alt=""
                    />
                  </div>
                  <div id="news-title" class="news-title fade-in">
                    <div class="news-h1 fade-in">
                      <h1>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua
                      </h1>
                    </div>
                    <div id="date" class="date fade-in">
                      <p>12-10-2024</p>
                    </div>
                    <div class="btn fade-in">
                      <button>
                        Baca Selengkapnya
                        <i id="arrow" class="bx bx-right-arrow-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div id="content" class="news-content fade-in">
                <div>
                  <div class="news-image fade-in">
                    <img
                      src="/RPL/src/assets/image/Morning Sky _ Minecraft Wallpaper (1).jpeg"
                      alt=""
                    />
                  </div>
                  <div id="news-title" class="news-title fade-in">
                    <div class="news-h1 fade-in">
                      <h1>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua
                      </h1>
                    </div>
                    <div id="date" class="date fade-in">
                      <p>12-10-2024</p>
                    </div>
                    <div class="btn fade-in">
                      <button>
                        Baca Selengkapnya
                        <i id="arrow" class="bx bx-right-arrow-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </article>
        <div class="btn-up fade-in" id="btnUp">
          <div class="btn-icon fade-in" onclick="scrollToTop()">
            <i class="bx bx-chevron-up"></i>
          </div>
        </div>
        <!-- footer -->
        <footer class="footer-main main">
          <div>
            <figure class="title-footer">
              <div>
                <h2>itesa</h2>
                <h3>muhammadiyah</h3>
                <h4>
                  <hr />
                  semarang
                </h4>
              </div>
              <div class="icon-cmty-footer">
                <hr />
                <article>
                  <i class="bx bxl-instagram"></i>
                </article>
                <article>
                  <i class="bx bxl-instagram"></i>
                </article>
                <article>
                  <i class="bx bxl-instagram"></i>
                </article>
                <hr />
              </div>
            </figure>
            <figure class="list-footer">
              <div>
                <ul>
                  <h3>sistem informasi</h3>
                  <li>portal umum</li>
                  <li>portal umum</li>
                  <li>portal umum</li>
                  <li>portal umum</li>
                  <li>portal umum</li>
                </ul>
                <ul>
                  <h3>sistem informasi</h3>
                  <li>portal umum</li>
                  <li>portal umum</li>
                  <li>portal umum</li>
                  <li>portal umum</li>
                  <li>portal umum</li>
                </ul>
              </div>
            </figure>
          </div>
        </footer>
        <footer class="footer-desaign">
          <div>
            <h5>© 2024 ITESA Semarang. All Rights Reserved</h5>
          </div>
        </footer>
      </div>
    </div>
    <script>
      window.onscroll = function () {
        const btnUp = document.getElementById("btnUp");
        const scrollPosition =
          document.documentElement.scrollTop || document.body.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        // Menampilkan tombol jika scroll mencapai setengah halaman atau lebih
        if (scrollPosition > (documentHeight - windowHeight) / 2) {
          btnUp.classList.add("show"); // Tambahkan kelas untuk menampilkan tombol
        } else {
          btnUp.classList.remove("show"); // Hapus kelas untuk menyembunyikan tombol
        }
      };

      function scrollToTop() {
        window.scrollTo({ top: 0, behavior: "smooth" }); // Scroll ke atas
      }
    </script>
    <script src="{{ asset('lib/js/onclick.js') }}"></script>
    <script src="{{ asset('lib/js/search.js') }}"></script>
  </body>
</html>
