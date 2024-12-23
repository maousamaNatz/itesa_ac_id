<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Lexend:wght@100..900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('lib/css/dev.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/index.css') }}" />
    <title>Document</title>
  </head>
  <body>
    <main class="container">
      <main class="organisasi-main" id="page-org">
        <section class="organisasi-user">
          <div>
            <article class="btn-close-organisasi">
              <button id="btn-close-org" onclick="Close_org()">
                <i class="bx bx-x"></i>
              </button>
            </article>
            <article class="tag-organisasi">
              <div>
                <h3>struktur organisasi <b>itesa</b></h3>
              </div>
            </article>
            <main class="list-user-organisasi">
              <div id="user-container"></div>
            </main>
          </div>
        </section>
      </main>
      <div>
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
                    <img src="{{ asset('lib/default_media/logos.png') }}" alt="logo itesa " />
                    <div>
                      <h4>itesa muhammadiyah</h4>
                      <h6>semarang</h6>
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
                  <a href="{{ route('login') }}">
                    <figure class="nav-tag nav-menu">
                      <article>
                        <h4>login</h4>
                      </article>
                    </figure>
                  </a>
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
                  <a href="{{ route('berita.index') }}">
                    <figure class="nav-tag nav-menu">
                      <article>
                        <h4>berita</h4>
                      </article>
                    </figure>
                  </a>
                  <a href="#section1">
                    <figure class="nav-tag nav-menu">
                      <article>
                        <h4>profile</h4>
                      </article>
                    </figure>
                  </a>
                  <a href="https://pmb.itesa.ac.id/">
                    <figure class="nav-tag nav-menu">
                      <article>
                        <h4>pmb</h4>
                      </article>
                    </figure>
                  </a>
                </div>
              </main>
            </nav>
          </div>
        </nav>
        <header class="header-main main" id="header">
          <div>
            <figure class="head-rigth">
              <div>
                <section class="head-card-main">
                  <figure class="head-card">
                    <div>
                      <img src="{{ asset('lib/default_media/student.jpg') }}" alt="" />
                    </div>
                  </figure>
                  <figure class="head-card">
                    <div>
                      <img src="{{ asset('lib/default_media/gedung.jpeg') }}" alt="" />
                    </div>
                  </figure>
                </section>
                <section class="head-card-main">
                  <figure class="head-card">
                    <div>
                      <img src="{{ asset('lib/default_media/logos.png') }}" alt="" />
                    </div>
                  </figure>
                  <figure class="head-card">
                    <div>
                        <img src="{{ asset('lib/default_media/kampus-merdeka.jpg') }}" alt="" />
                    </div>
                  </figure>
                </section>
              </div>
            </figure>
            <figure class="head-left">
              <figure></figure>
              <figure></figure>
              <figure></figure>
              <figure></figure>
              <div>
                <article class="head-card-title">
                  <div>
                    <article class="main-title">
                      <div>
                        <h3><b>itesa</b></h3>
                      </div>
                      <div>
                        <h4>muhammadiyah</h4>
                        <h5>
                          institut teknologi statistika dan bisnis muhammadiyah
                        </h5>
                      </div>
                    </article>
                    <article class="subtitle">
                      <h4>
                        Jelajahi potensi dan wujudkan mimpi bersama kampus kami!
                      </h4>
                    </article>
                  </div>
                </article>
                <article class="head-card-join">
                  <a href="https://penmaru.itesa.ac.id/">
                    <button>
                      <h4>yuk! buruan daftar</h4>
                    </button>
                  </a>
                </article>
              </div>
            </figure>
          </div>
        </header>
        <section class="tag-rektor-main main" id="section1">
          <div>
            <aside class="tag-rektor-left">
              <figure>
                <div>
                  <article class="tag-title-rektor1">
                    <h5>keluarga besar <b>itesa</b> mengucapkan</h5>
                    <h4>selamat datang,</h4>
                    <h3>
                      <hr />
                      selamat
                    </h3>
                    <h3><b>sukses!</b></h3>
                  </article>
                  <article class="tag-name-rektor">
                    <h4>ibu nurul huda, m.kom</h4>
                    <h5>sebagai rektor terpilih</h5>
                    <h5>
                      Institut Teknologi Statistika dan Bisnis Muhammadiyah
                    </h5>
                  </article>
                </div>
              </figure>
              <figure class="tag-rektor-img">
                <div>
                  <img src="{{ asset('lib/default_media/rektor.jpg') }}" alt="" />
                </div>
              </figure>
            </aside>
            <figure class="tag-rektor-right">
              <div>
                <article class="tag-title-rektor2">
                  <h2>sambutan rektor</h2>
                  <h3>itesa</h3>
                </article>
                <article class="tag-rektor-desc">
                  <div>
                    <h4>
                      <b>Selamat datang</b> di halaman web resmi Institut
                      Teknologi Statistika dan Bisnis Muhammadiyah Semarang!
                    </h4>
                    <h4>
                      Salam sejahtera untuk seluruh civitas akademika, mitra
                      kerja, dan para pengunjung yang terhormat. Saya, Nurul
                      Huda, S.Kom, M.Kom, dengan bangga menyambut Anda di ruang
                      virtual ini, tempat di mana inovasi, pengetahuan, dan
                      pengembangan diri bertemu.
                    </h4>
                  </div>
                </article>
              </div>
            </figure>
          </div>
        </section>
        <section class="tag-organisasi-main" id="section2">
          <div>
            <figure class="list-profile" id="profile-container"></figure>
            <figure class="title-organisasi">
              <article>
                <h3>struktur</h3>
                <h4>organisasi <b>itesa</b></h4>
              </article>
              <article class="btn-open-organisasi">
                <button id="btn-open-org" onclick="Open_org()">
                  <h5>lihat struktur</h5>
                </button>
              </article>
            </figure>
          </div>
        </section>
        <section class="tag-vimitu-main main" id="section3">
          <div>
            <figure class="tag-vimitu-img">
              <img src="{{ asset('lib/default_media/student.jpg') }}" alt="" />
            </figure>
            <figure class="tag-vimitu-desc">
              <div>
                <figure class="card-vimitu">
                  <div class="title-vimitu">
                    <h3>visi</h3>
                  </div>
                  <article class="vimitu-txt">
                    <div>
                      <h3>visi</h3>
                      <div class="vimitu-list">
                        <h4>
                          “Menjadi Institut Unggul berdasarkan nilai-nilai Islam
                          yang berkemajuan”
                        </h4>
                      </div>
                    </div>
                  </article>
                </figure>
                <figure class="card-vimitu">
                  <div class="title-vimitu">
                    <h3>misi</h3>
                  </div>
                  <article class="vimitu-txt">
                    <div>
                      <h3>misi</h3>
                      <div class="vimitu-list">
                        <ul>
                          <li>
                            Menyelenggarakan pembinaan dan penguatan nilai-nilai
                            Al Islam dan Kemuhammadiyahan.
                          </li>
                          <li>
                            Menyelenggarakan dan mengembangkan pendidikan di
                            bidang teknologi, statistika dan bisnis untuk
                            menghasilkan sumber daya insani yang profesional.
                          </li>
                          <li>
                            Melaksanakan penelitian berdasarkan ilmu pengetahuan
                            yang bermanfaat dan berkelanjutan.
                          </li>
                          <li>
                            Melaksanakan pengabdian kepada masyarakat untuk
                            meningkatkan kesejahteraan masyarakat.
                          </li>
                        </ul>
                      </div>
                    </div>
                  </article>
                </figure>
                <figure class="card-vimitu">
                  <div class="title-vimitu">
                    <h3>tujuan</h3>
                  </div>
                  <article class="vimitu-txt">
                    <div>
                      <h3>tujuan</h3>
                      <div class="vimitu-list">
                        <ul>
                          <li>
                            Menghasilkan sumber daya insani unggul dan
                            berkarakter Islam berkemajuan
                          </li>
                          <li>
                            Menghasilkan penelitian di bidang teknologi,
                            statistika dan bisnis untuk mendukung pembangunan
                            nasional
                          </li>
                          <li>
                            Menghasilkan pengabdian kepada masyarakat yang
                            bermanfaat untuk meningkatkan kesejahteraan
                            masyarakat.
                          </li>
                        </ul>
                      </div>
                    </div>
                  </article>
                </figure>
              </div>
            </figure>
          </div>
        </section>
        <section class="tag-history-main main" id="section4">
          <article class="tag-title-history">
            <div>
              <h3>sejarah <b>itesa</b></h3>
            </div>
          </article>
          <header class="main-history">
            <div>
              <figure class="tag-history-img">
                <div>
                  <div class="title-history-img">
                    <h3>itesa</h3>
                    <h4>muhammadiyah</h4>
                    <h5>
                      <hr />
                      semarang
                    </h5>
                  </div>
                  <div class="title-history-img">
                    <h2>MCMXCII<b>,M</b></h2>
                  </div>
                  <img src="{{ asset('lib/default_media/gedung2.jpeg') }}" alt="" />
                </div>
              </figure>
              <figure class="tag-history-desc">
                <div>
                  <article class="desc-history">
                    <h4>
                      Pada tahun 1992, Akademi Statistika (AIS) Muhammadiyah
                      didirikan Pada tanggal 1 Juni 1992 Program Studi Diploma
                      III Statistika mendapatkan ijin penyelenggaraan oleh
                      Direktorat Jenderal Pendidikan Tinggi, Departemen
                      Pendidikan dan Kebudayaan Republik Indonesia dengan Nomor
                      220/ DIKTI/ Kep/ 1992.
                    </h4>
                    <h4>
                      Tahun 1992-1999 Akademi Statistika (AIS) Muhammadiyah
                      Semarang dengan 1 Program Studi Diploma III Statistika
                      dipimpin oleh Direktur yaitu Bapak Drs. Marlan Hendro,
                      M.M.
                    </h4>
                    <h4>
                      Tahun 1999-2010 Akademik Statistika (AIS) Muhammadiyah
                      Semarang dengan 1 (satu) Program Studi yaitu Diploma III
                      Statistika dipimpin oleh Direktur yaitu Bapak Dr. Rochdi
                      Wasono, M.Si.
                      <span id="dots">. . .<b>selengkapnya</b></span>
                    </h4>
                    <div id="more">
                      <h4>
                        Tahun 2010-2014 Akademik Statistika (AIS) Muhammadiyah
                        Semarang dengan 1 (satu) Program Studi yaitu Diploma III
                        Statistika dipimpin oleh Direktur yaitu Bapak Suharyono,
                        S.E., M.Si.
                      </h4>
                      <h4>
                        Tahun 2014-2021 Akademik Statistika (AIS) Muhammadiyah
                        Semarang dengan 1 (satu) Program Studi yaitu Diploma III
                        Statistika dipimpin oleh Direktur yaitu Ibu Drs. Wellie
                        Sulistijanti, M.Sc.
                      < /h4>
                      <h4>
                        Tahun 2021 Akademi Statistika (AIS) Muhammadiyah
                        Semarang berubah bentuk menjadi Institut Teknologi
                        Statistika dan Bisnis Muhammadiyah Semarang tepatnya
                        pada tanggal 26 Juni 2021 dengan nomor 347/E/O/2021.
                        Pada perubahan bentuk menjadi Institut, ada 3 (tiga)
                        Program Studi baru yang diusulkan yaitu S1 Sains
                        Aktuaria, S1 Manajemen Retail, dan S1 Rekayasa Perangkat
                        Lunak.
                      </h4>
                      <h4>
                        Tahun 2021-Sekarang Institut Teknologi Statistika dan
                        Bisnis Muhammadiyah Semarang dengan 4 (satu) Program
                        Studi yaitu Diploma III Statistika, S1 Sains Aktuaria,
                        S1 Manajemen Retail, dan S1 Rekayasa Perangkat Lunak
                        dipimpin oleh Rektor yaitu Dra. Wellie Sulistijanti,
                        M.Sc.
                      </h4>
                    </div>
                  </article>
                  <article class="history-btn">
                    <button onclick="Read_more()" id="btn-more">
                      Read more
                    </button>
                  </article>
                </div>
              </figure>
            </div>
          </header>
        </section>
        <main class="info-main main" id="section5">
          <div>
            <main>
              <figure class="card-info">
                <div>
                  <article class="info-ico">
                    <i class="bx bx-time"></i>
                    <i class="bx bx-time"></i>
                  </article>
                  <article class="info-title">
                    <h3>pendaftaran mahasiswa baru</h3>
                  </article>
                  <figure class="info-subtitle">
                    <a href="#section7">
                      <article>
                        <i class="bx bx-home"></i>
                        <h4>offline</h4>
                      </article>
                    </a>
                    <a href="https://pmb.itesa.ac.id/">
                      <article>
                        <i class="bx bx-globe"></i>
                        <h4>online</h4>
                      </article>
                    </a>
                  </figure>
                </div>
              </figure>
              <figure class="card-info">
                <div>
                  <article class="info-ico">
                    <i class="bx bxl-whatsapp"></i>
                    <i class="bx bxl-whatsapp"></i>
                  </article>
                  <article class="info-title">
                    <h3>nomor whatsapp PMB</h3>
                    <h5>layanan penerimaan mahasiswa baru</h5>
                  </article>
                  <a
                    href="whatsapp://send?text=send messages&phone=+628112515415"
                  >
                    <article class="info-no">
                      <i class="bx bxl-whatsapp"></i>
                      <h4>0811-2515-415</h4>
                    </article>
                  </a>
                </div>
              </figure>
              <figure class="card-info">
                <div>
                  <article class="info-ico">
                    <i class="bx bx-time"></i>
                    <i class="bx bx-time"></i>
                  </article>
                  <article class="info-title">
                    <h3>ikuti sosial media kami!</h3>
                  </article>
                  <article class="info-no fs">
                    <i class="bx bxl-facebook"></i>
                    <h5>facebook ITESA</h5>
                  </article>
                  <a
                    href="https://www.instagram.com/itesa.official/"
                    target="_blank"
                  >
                    <article class="info-no ig">
                      <i class="bx bxl-instagram"></i>
                      <h5>instagram ITESA</h5>
                    </article>
                  </a>
                  <a
                    href="https://www.youtube.com/@itesatv7891"
                    target="_blank"
                  >
                    <article class="info-no yt">
                      <i class="bx bxl-youtube"></i>
                      <h5>youtube ITESA</h5>
                    </article>
                  </a>
                </div>
              </figure>
            </main>
          </div>
        </main>
        <section class="tag-berita-main" id="section6">
          <div>
            <article class="tag-berita">
              <div>
                <h3>berita <b>itesa</b></h3>
                <h4>update informasi</h4>
              </div>
            </article>
            <main class="menu-berita">
                @foreach ($latestArticles as $item)
              <figure class="card-berita">
                <div>
                  <figure class="berita-img">
                    <div>
                      <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="" />
                    </div>
                  </figure>
                  <article class="berita-txt">
                    <div class="berita-desc">
                      <h3>
                        {{ $item->title }}
                      </h3>
                      <h5>{{ $item->created_at }}</h5>
                    </div>
                    <section class="card-kampus">
                      <figure>
                        <div>
                          <div class="ico-berita">
                            <img src="{{ asset('lib/default_media/logos.png') }}" alt="" />
                          </div>
                          <div class="kampus-txt">
                            <h4><b>itesa</b> muhammadiyah</h4>
                            <h5>
                              <hr />
                              semarang
                            </h5>
                          </div>
                        </div>
                      </figure>
                      <figure class="berita-show">
                        <div>
                          <a href="{{ route('berita.show', ['slug' => $item->slug]) }}" style="text-decoration: none; color: #e9e9e9;">
                            <h4>baca selengkapnya</h4>
                          </a>
                        </div>
                      </figure>
                    </section>
                  </article>
                </div>
              </figure>
              @endforeach
            </main>
            <article class="btn-show-berita">
              <div>
                <button onclick="window.location.href='{{ route('berita.index') }}'">
                  <h4>Lihat semua berita</h4>
                </button>
              </div>
            </article>
          </div>
        </section>
        <section class="location-main" id="section7">
          <div>
            <figure class="card-location">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15840.702559434985!2d110.3570682!3d-6.9885819!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708a98163a32ff%3A0x6bf2bae3ffd66e1e!2sInstitut%20Teknologi%20Statistika%20dan%20Bisnis%20Muhammadiyah%20(ITESA%20Muhammadiyah)%20Semarang!5e0!3m2!1sid!2sid!4v1701148708235!5m2!1sid!2sid"
                width="100%"
                height="450"
                style="border: 0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
            </figure>
            <figure class="location-txt">
              <div>
                <article class="title-location">
                  <h3>alamat</h3>
                  <h3>itesa</h3>
                </article>
                <article class="location-desc">
                  <h4>
                    Institut Teknologi Statistika dan Bisnis Muhammadiyah (ITESA
                    Muhammadiyah) Semarang. Jl. Prof. Dr. Hamka No.KM.1,
                    Tambakaji, Kec. Ngaliyan, Kota Semarang, Jawa Tengah 50185
                  </h4>
                </article>
              </div>
            </figure>
          </div>
        </section>
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
                <a href="https://www.instagram.com/itesa.official/">
                  <article>
                    <i class="bx bxl-instagram"></i>
                  </article>
                </a>
                <a href="https://www.youtube.com/@itesatv7891">
                  <article>
                    <i class="bx bxl-youtube"></i>
                  </article>
                </a>
                <a href="">
                  <article>
                    <i class="bx bxl-facebook"></i>
                  </article>
                </a>
                <hr />
              </div>
            </figure>
            <figure class="list-footer">
              <div>
                <ul>
                  <h3>link website lain</h3>
                  <li>
                    <a href="https://pmb.itesa.ac.id/" target="_blank">PMB</a>
                  </li>
                  <li>
                    <a href="https://journal.itesa.ac.id/index.php/jasdm"
                      >Jurnal Itesa</a
                    >
                  </li>
                  <li><a href="#">ITESA Tracer</a></li>
                  <li>
                    <a href="https://itesacareercenter.itesa.ac.id/"
                      >ITESA Career Center</a
                    >
                  </li>
                </ul>
                <ul>
                  <h3>fakultas ITESA</h3>
                  <li>D3 Statistika</li>
                  <li>S1 Sains Aktuaria</li>
                  <li>S1 Rekayasa Perangkat Lunak</li>
                  <li>S1 Manajemen Retail</li>
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
    </main>
    <script src="{{ asset('lib/js/onclick.js') }}"></script>
    <script src="{{ asset('lib/js/search.js') }}"></script>
    <script src="{{ asset('lib/js/list-org.js') }}"></script>
    <script src="{{ asset('lib/js/dynamic-dev.js') }}"></script>
  </body>
</html>
