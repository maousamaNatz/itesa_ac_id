document.addEventListener("DOMContentLoaded", () => {
  const devProfile = document.getElementById("dev-profile");

  // Mendapatkan query parameter dan path dari URL
  const queryParams = new URLSearchParams(window.location.search);
  const currentPath = window.location.pathname;

  // Periksa apakah URL mengandung '/dev' atau '/index.html?dev'
  const isDevPage =
    currentPath.endsWith("/dev") ||
    (currentPath.endsWith("/index.html") && queryParams.has("dev"));

  if (isDevPage) {
    // Jika elemen devProfile belum ada, tambahkan elemen dinamis
    if (!devProfile) {
      const devContent = `
    <header class="dev-main" id="dev-profile">
        <div class="card-list-dev">
            <main class="list-dev">
                <!-- Developer 1 -->
                <figure class="card-dev">
                <div>
                    <section class="link">
                    <div>
                        <a href="">
                        <i class="bx bxl-instagram"></i>
                        </a>
                        <a href="">
                        <i class="bx bxl-whatsapp"></i>
                        </a>
                        <div class="hr-dev"><hr /></div>
                    </div>
                    </section>
                    <article class="dev">
                    <div>
                        <figure class="profile-dev">
                        <div>
                            <img src="lib/default_media/pp natan.jpg" alt="" />
                            <img src="lib/default_media/natan-p.jpg" alt="" />
                        </div>
                        </figure>
                        <article class="profile-dev-name">
                        <div>
                            <div class="name-dev">
                            <h3>natanael ben iriyanto</h3>
                            <h3>natz!! of the year</h3>
                            </div>
                            <h2>developer backend</h2>
                            <div class="email">
                            <hr />
                            <h4>scutscut820@gmail.com</h4>
                            </div>
                        </div>
                        </article>
                    </div>
                    </article>
                    <figure class="banner-dev">
                    <div>
                        <img src="lib/default_media/natan-banner.jpg" alt="" />
                    </div>
                    </figure>
                </div>
                </figure>
                <!-- Developer 2 -->
                <figure class="card-dev">
                <div>
                    <section class="link">
                    <div>
                        <a href="">
                        <i class="bx bxl-instagram"></i>
                        </a>
                        <a href="">
                        <i class="bx bxl-whatsapp"></i>
                        </a>
                        <div class="hr-dev"><hr /></div>
                    </div>
                    </section>
                    <article class="dev">
                    <div>
                        <figure class="profile-dev">
                        <div>
                            <img src="lib/default_media/pp hiruka.jpg" alt="" />
                            <img src="lib/default_media/hiruka-p.jpg" alt="" />
                        </div>
                        </figure>
                        <article class="profile-dev-name">
                        <div>
                            <div class="name-dev">
                            <h3>haidar rifky andrianno</h3>
                            <h3>hanzz, the demon king</h3>
                            </div>
                            <h2>developer frontend</h2>
                            <div class="email">
                            <hr />
                            <h4>haidarandrianno098@gmail.com</h4>
                            </div>
                        </div>
                        </article>
                    </div>
                    </article>
                    <figure class="banner-dev">
                    <div>
                        <img src="lib/default_media/hiruka-banner.jpg" alt="" />
                    </div>
                    </figure>
                </div>
                </figure>
                <!-- Developer 3 -->
                <figure class="card-dev">
                <div>
                    <section class="link">
                    <div>
                        <a href="">
                        <i class="bx bxl-instagram"></i>
                        </a>
                        <a href="">
                        <i class="bx bxl-whatsapp"></i>
                        </a>
                        <div class="hr-dev"><hr /></div>
                    </div>
                    </section>
                    <article class="dev">
                    <div>
                        <figure class="profile-dev">
                        <div>
                            <img src="lib/default_media/pp ridho.jpg" alt="" />
                            <img src="lib/default_media/ridho-p.jpg" alt="" />
                        </div>
                        </figure>
                        <article class="profile-dev-name">
                        <div>
                            <div class="name-dev">
                            <h3>ridho totti febiyanto</h3>
                            <h3>skyy4one</h3>
                            </div>
                            <h2>developer frontend</h2>
                            <div class="email">
                            <hr />
                            <h4>tottiaja9@gmail.com</h4>
                            </div>
                        </div>
                        </article>
                    </div>
                    </article>
                    <figure class="banner-dev">
                    <div>
                        <img src="lib/default_media/ridho-banner.jpg" alt="" />
                    </div>
                    </figure>
                </div>
                </figure>
            </main>
        </div>
    </header>
    `;

      // Menambahkan konten ke elemen <body>
      document.body.insertAdjacentHTML("beforeend", devContent);
    }

    // Tambahkan kelas 'dev-show' untuk menampilkan elemen
    const devProfileElement = document.getElementById("dev-profile");
    devProfileElement?.classList.add("dev-show");
  } else {
    // Jika URL tidak sesuai, pastikan elemen tidak terlihat
    devProfile?.classList.remove("dev-show");
  }
});
