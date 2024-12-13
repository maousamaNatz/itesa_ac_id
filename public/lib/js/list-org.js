const users = [
  {
    image: "/lib/default_media/rektor.jpg",
    name: "rektor",
    fullName: "nurul huda, s.kom., m.kom., ph.d..",
    description:
      "Selamat datang di dunia akademik yang penuh peluang! Jadikan kampus ini sebagai rumah kedua untuk tumbuh, belajar, dan menciptakan masa depan cerah.",
  },
  {
    image: "/lib/default_media/pak herman.jpg",
    name: "Wakil Rektor Bidang Akademik dan AIK",
    fullName: "herman yuliansyah, s.t., m.eng., ph.d.",
    description:
      "Mulailah perjalanan akademikmu dengan penuh semangat dan keingintahuan. Setiap langkah kecil yang kamu ambil hari ini akan membawa dampak besar di masa depan. Kami siap mendukungmu untuk meraih impian!",
  },
  {
    image: "/lib/default_media/bu khikmah.JPG",
    name: "Wakil Rektor Bidang SDM, Keuangan, dan Kemahasiswaan",
    fullName: "laelatul khikmah, m.si.",
    description:
      "Kampus ini bukan hanya tempat untuk belajar, tapi juga untuk berkembang. Manfaatkan setiap kesempatan untuk menumbuhkan potensi diri, baik dalam akademik, sosial, maupun kegiatan kemahasiswaan. Kami ada untuk mendampingimu!",
  },
  {
    image: "/lib/default_media/pak deden.JPG",
    name: "Kepala LPPM",
    fullName: "deden istiawan, m.kom.",
    description:
      "Selamat datang di lingkungan yang penuh dengan inovasi dan penelitian! Jangan ragu untuk mengeksplorasi ide-ide baru dan bergabung dalam kegiatan riset yang akan memperluas wawasanmu",
  },
  {
    image: "/lib/default_media/pak nasihin.JPG",
    name: "Kepala LAIK",
    fullName: "drs. moh. nasihin",
    description:
      "Di sini, kalian akan belajar tidak hanya tentang teori, tetapi juga bagaimana mengelola pengetahuan dengan bijak. Nikmati perjalanan ini dan jadikan setiap proses pembelajaran sebagai pengalaman berharga.",
  },
  {
    image: "/lib/default_media/pak safaat.JPG",
    name: "Ketua Prodi DIII Statistika",
    fullName: "safa'at yulianto, m.si.",
    description:
      "Matematika dan statistik bukan hanya angka dan rumus—mereka adalah alat untuk memahami dunia. Jadikan ilmu yang kalian pelajari sebagai jembatan untuk menemukan solusi dalam kehidupan sehari-hari.",
  },
  {
    image: "/lib/default_media/pak zakariya.JPG",
    name: "Ketua Prodi S1 Sains Aktuaria",
    fullName: "zakariya bani ikhtiar, m.mat.",
    description:
      "Selamat bergabung di dunia aktuaria, tempat di mana matematika bertemu dengan dunia nyata! Tantang dirimu untuk berpikir kritis, dan siapkan dirimu untuk menjadi ahli dalam mengelola risiko.",
  },
  {
    image: "/lib/default_media/bu rowi.JPG",
    name: "Ketua Prodi S1 Manajemen Retail",
    fullName: "rowiyani, m.m.",
    description:
      "Kampus ini adalah tempat yang sempurna untuk mengasah keterampilan bisnismu. Bergabunglah dengan komunitas yang penuh ide-ide segar dan siap menghadapi tantangan dunia retail yang terus berkembang.",
  },
  {
    image: "/lib/default_media/bu diyah.jpg",
    name: "Ketua Prodi S1 Rekayasa Perangkat Lunak",
    fullName: "adiyah mahiruna, m.kom.",
    description:
      "Di dunia yang semakin digital, kemampuan untuk menciptakan perangkat lunak yang berguna adalah kekuatan. Selamat datang di dunia teknologi, tempat di mana kreativitas bertemu dengan kecanggihan.",
  },
  {
    image: "/lib/default_media/bu aul.jpg",
    name: "Biro Kemahasiswaan",
    fullName: "latifatul aulia, m.mat.",
    description:
      "Jangan hanya menjadi mahasiswa, jadilah mahasiswa yang aktif, kreatif, dan berani berkontribusi. Kampus ini adalah tempat untuk mengembangkan diri, menemukan passion, dan menciptakan kenangan tak terlupakan.",
  },
  {
    image: "/lib/default_media/mas aulad.JPG",
    name: "Biro Kerjasama",
    fullName: "aulad muwaffaq, m.ag.",
    description:
      "Di dunia yang saling terhubung ini, kolaborasi adalah kunci. Manfaatkan kesempatan untuk memperluas jaringan dan menjalin kerjasama yang dapat membuka pintu kesuksesan bagi kalian.",
  },
  {
    image: "/lib/default_media/mas wahid hakim azzaky.JPG",
    name: "Bagian Akademik dan Kemahasiswaan",
    fullName: "wahid hakim azzaky, s.ag.",
    description:
      "Di balik setiap perjalanan akademik, ada banyak kesempatan untuk tumbuh dan belajar. Kami di sini untuk memastikan kamu mendapatkan pengalaman belajar yang terbaik dan mendukungmu di setiap langkah.",
  },
  {
    image: "/lib/default_media/mba indah.JPG",
    name: "Bagian Administrasi Umum",
    fullName: "indah ayu lestari, m.pd.",
    description:
      "Setiap detil administratif yang kami kelola adalah bagian dari dukungan kami untuk kalian. Kami ingin memastikan bahwa perjalanan akademikmu berjalan lancar, tanpa hambatan.",
  },
  {
    image: "/lib/default_media/pak aria furisan.jpg",
    name: "Bagian Sarana dan Prasarana",
    fullName: "aria furisan, s.sos.",
    description:
      "Kami berkomitmen untuk menyediakan fasilitas terbaik yang mendukung kenyamanan dan produktivitas kalian. Setiap ruang adalah tempat untuk menciptakan karya terbaikmu.",
  },
  {
    image: "/lib/default_media/Fadhillah Rahmadhani Wahyunintya.JPG",
    name: "Bagian Keuangan",
    fullName: "fadhillah rahmadhani wahyunintya, m.ak.",
    description:
      "Manajemen keuangan yang bijak adalah kunci untuk meraih tujuan. Kami di sini untuk memastikan kalian memiliki akses yang jelas dan mudah dalam pengelolaan keuangan selama masa studi.",
  },
  {
    image: "/lib/default_media/galih.jpg",
    name: "Bagian Sistem Informasi",
    fullName: "galih setyaningrum, s.kom.",
    description:
      "Teknologi adalah alat yang dapat membantu kalian meraih tujuan dengan lebih efisien. Manfaatkan sistem yang ada untuk mendukung perjalanan akademik kalian, dan jangan ragu untuk berinovasi.",
  },
];

// Function to shuffle the users array
function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]]; // Swap elements
  }
}

// Function to dynamically create and add cards
function createUserCards(users) {
  const container = document.getElementById("user-container");

  users.forEach((user) => {
    const card = document.createElement("figure");
    card.classList.add("card-user");

    card.innerHTML = `
      <div>
        <figure class="card-user-img">
          <div>
            <img src="${user.image}" alt="${user.name}" />
          </div>
        </figure>
        <article class="card-user-name">
          <div>
            <h3>${user.name}</h3>
            <div class="name">
              <h4>${user.fullName}</h4>
              <hr />
              <h5>${user.description}</h5>
            </div>
          </div>
        </article>
      </div>
    `;

    container.appendChild(card); // Append the card to the container
  });
}

function createRandomUserProfiles(users) {
  shuffleArray(users);
  const container = document.getElementById("profile-container");
  const selectedUsers = users.slice(0, 5); // Select the first 5 users after shuffling

  selectedUsers.forEach((user) => {
    const profile = document.createElement("figure");
    profile.classList.add("profile-user");

    profile.innerHTML = `
      <div>
        <img src="${user.image}" alt="${user.name}" />
      </div>
    `;

    container.appendChild(profile);
  });
}

// Call the function to generate user cards
createUserCards(users);
createRandomUserProfiles(users);
