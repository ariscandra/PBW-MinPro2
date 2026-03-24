<?php
declare(strict_types=1);

require __DIR__ . "/db.php";

function fetchAllAssoc(mysqli $db, string $sql): array
{
    $result = $db->query($sql);
    if (!$result) {
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

$profil = fetchAllAssoc(
    $koneksi,
    "SELECT nama_lengkap, tagline, deskripsi_singkat, deskripsi_diri, github_url, hero_image
     FROM profil
     WHERE is_active = 1
     ORDER BY id ASC
     LIMIT 1"
);

$profilAktif = $profil[0] ?? [
    "nama_lengkap" => "Aris Candra Muzaffar",
    "tagline" => "وَالْعِلْمُ فِي الْكِبَرِ كَالنَّقْشِ عَلَى الْمَاءِ الْعِلْمُ فِي الصِّغَرِ كَالنَّقْشِ عَلَى الْحَجَرِ",
    "deskripsi_singkat" => "Manusia ini lahir di negeri sebelah, tumbuh di sini, jelajahi jawa guna cari bekal agama. Sekarang, he's just tryna get by, day by day.",
    "deskripsi_diri" => "I describe myself as an unimaginative creature with severely lacking motivation trying to learn more about the world.",
    "github_url" => "https://github.com/ariscandra",
    "hero_image" => "assets/gua.png",
];

$daftarSkill = fetchAllAssoc(
    $koneksi,
    "SELECT nama, persen
     FROM skills
     WHERE is_active = 1
     ORDER BY urutan ASC, id ASC"
);

$pengalamanRows = fetchAllAssoc(
    $koneksi,
    "SELECT deskripsi
     FROM pengalaman
     WHERE is_active = 1
     ORDER BY urutan ASC, id ASC"
);
$daftarPengalaman = array_map(static fn(array $row): string => (string) $row["deskripsi"], $pengalamanRows);

$daftarSertifikat = fetchAllAssoc(
    $koneksi,
    "SELECT id, nama, penerbit, tahun, gambar
     FROM sertifikat
     WHERE is_active = 1
     ORDER BY urutan ASC, id ASC"
);

$koneksi->close();

$numSertifikat = count($daftarSertifikat) > 0 ? count($daftarSertifikat) : 6;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($profilAktif["nama_lengkap"], ENT_QUOTES, "UTF-8") ?> - Portfolio</title>
  <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <style>:root { --num: <?= (int) $numSertifikat ?>; }</style>
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-lg sticky-top" :class="{ 'nav-unlocked': sudahKlikCurious }">
      <div class="container">
        <a class="navbar-brand brand-arcan" href="#home"><?= htmlspecialchars($profilAktif["nama_lengkap"], ENT_QUOTES, "UTF-8") ?></a>
        <button class="navbar-toggler" type="button" @click="toggleNavbar" :aria-expanded="navbarTerbuka" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse" :class="{ 'show': navbarTerbuka, 'collapse': true }" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#home" @click="tutupNavbar"><img src="assets/ikon/homepage.png" alt="Home" class="nav-icon"></a>
            </li>
            <li class="nav-item" v-show="sudahKlikCurious">
              <a class="nav-link" :class="{ 'nav-link-unlock-glow': secretFound }" href="#about" @click="tutupNavbar"><img src="assets/ikon/About Me.png" alt="About Me" class="nav-icon"></a>
            </li>
            <li class="nav-item" v-show="sudahKlikCurious">
              <a class="nav-link" :class="{ 'nav-link-unlock-glow': secretFound }" href="#certificates" @click="tutupNavbar"><img src="assets/ikon/Certificates.png" alt="Certificates" class="nav-icon"></a>
            </li>
            <li class="nav-item" v-show="sudahKlikCurious">
              <a class="nav-link nav-link-github" :class="{ 'nav-link-unlock-glow': secretFound }" :href="githubUrl" target="_blank" rel="noopener"><img src="assets/ikon/github.png" alt="Github" class="nav-icon"></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <section id="home" class="section-home min-vh-100 d-flex align-items-center">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 order-2 order-lg-1">
            <div class="hero-plaster mb-3" :class="{ 'plaster-shake': namaShake, 'plaster-revealed': namaTerbuka }" @click="revealNama">
              <div class="hero-plaster-block" v-show="!namaTerbuka">
                <span class="plaster-label">My Name?</span>
                <span class="plaster-hint">[ click {{ 3 - namaClicks }}x more ]</span>
                <div class="plaster-bar"><div class="plaster-bar-fill" :style="{ width: (100 - (namaClicks / 3) * 100) + '%' }"></div></div>
              </div>
              <h1 class="hero-title hero-plaster-content" v-show="namaTerbuka">{{ namaLengkap }}</h1>
            </div>
            <div class="hero-plaster mb-2" :class="{ 'plaster-shake': mottoShake, 'plaster-revealed': mottoTerbuka }" @click="revealMotto">
              <div class="hero-plaster-block" v-show="!mottoTerbuka">
                <span class="plaster-label">My Motto?</span>
                <span class="plaster-hint">[ click {{ 3 - mottoClicks }}x more ]</span>
                <div class="plaster-bar"><div class="plaster-bar-fill" :style="{ width: (100 - (mottoClicks / 3) * 100) + '%' }"></div></div>
              </div>
              <p class="hero-tagline hero-plaster-content" v-show="mottoTerbuka">{{ tagline }}</p>
            </div>
            <div class="hero-plaster mb-4" :class="{ 'plaster-shake': deskripsiShake, 'plaster-revealed': deskripsiTerbuka }" @click="revealDeskripsi">
              <div class="hero-plaster-block" v-show="!deskripsiTerbuka">
                <span class="plaster-label">A Little About Myself</span>
                <span class="plaster-hint">[ click {{ 3 - deskripsiClicks }}x more ]</span>
                <div class="plaster-bar"><div class="plaster-bar-fill" :style="{ width: (100 - (deskripsiClicks / 3) * 100) + '%' }"></div></div>
              </div>
              <p class="hero-deskripsi hero-plaster-content" v-show="deskripsiTerbuka">{{ deskripsiSingkat }}</p>
            </div>
            <button class="btn-curious" @click="klikCurious" v-if="!sudahKlikCurious">Wanna know more about me?</button>
            <p v-if="secretFound" class="secret-feedback mt-3">Secret found!</p>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center mb-4 mb-lg-0">
            <div class="hero-img-wrapper">
              <img :src="heroImage" :alt="namaLengkap" class="hero-img img-fluid" :style="parallaxStyle">
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="section-about py-5">
      <div class="container">
        <h2 class="section-title mb-4">About Me</h2>
        <div class="row">
          <div class="col-lg-6 mb-4">
            <p class="about-deskripsi">{{ deskripsiDiri }}</p>
            <h3 class="h5 mt-4 mb-3">Skills</h3>
            <div v-for="skill in daftarSkill" :key="skill.nama" class="skill-item mb-3">
              <div class="d-flex justify-content-between mb-1">
                <span>{{ skill.nama }}</span>
                <span class="skill-persen">{{ skill.persen }}%</span>
              </div>
              <div class="progress progress-pixel">
                <div class="progress-bar" role="progressbar" :style="{ width: skill.persen + '%' }" :aria-valuenow="skill.persen" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <h3 class="h5 mb-3">Pengalaman</h3>
            <ul class="list-pengalaman">
              <li v-for="p in daftarPengalaman" :key="p">{{ p }}</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="certificates" class="section-certificates py-5">
      <div class="container">
        <h2 class="section-title mb-4">Certificates</h2>
        <div class="carousel">
          <ul class="gallery">
            <li class="card" v-for="(s, i) in daftarSertifikat" :key="s.id" :style="{'--timer': i + 1}">
              <img :src="s.gambar" :alt="s.nama">
            </li>
          </ul>
        </div>
      </div>
    </section>
  </div>

  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfeftQFSMpOe4Dh5BYGED3FiEfNFp3bFbGxYHHT4B9YoQRFzFRaRoMR" crossorigin="anonymous"></script>
  <script>
    const serverData = {
      profil: <?= json_encode($profilAktif, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      daftarSkill: <?= json_encode($daftarSkill, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      daftarPengalaman: <?= json_encode($daftarPengalaman, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      daftarSertifikat: <?= json_encode($daftarSertifikat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    };

    const { createApp } = Vue;
    createApp({
      data() {
        return {
          sudahKlikCurious: false,
          secretFound: false,
          navbarTerbuka: false,
          namaTerbuka: false,
          mottoTerbuka: false,
          deskripsiTerbuka: false,
          namaClicks: 0,
          mottoClicks: 0,
          deskripsiClicks: 0,
          namaShake: false,
          mottoShake: false,
          deskripsiShake: false,
          scrollY: 0,
          namaLengkap: serverData.profil.nama_lengkap,
          tagline: serverData.profil.tagline,
          deskripsiSingkat: serverData.profil.deskripsi_singkat,
          deskripsiDiri: serverData.profil.deskripsi_diri,
          githubUrl: serverData.profil.github_url,
          heroImage: serverData.profil.hero_image,
          daftarSkill: serverData.daftarSkill,
          daftarPengalaman: serverData.daftarPengalaman,
          daftarSertifikat: serverData.daftarSertifikat
        };
      },
      computed: {
        parallaxStyle() {
          const y = Math.min(this.scrollY * 0.12, 35);
          return { transform: `translateY(${y}px)` };
        }
      },
      methods: {
        handleScroll() {
          this.scrollY = window.scrollY || document.documentElement.scrollTop;
        },
        toggleNavbar() {
          this.navbarTerbuka = !this.navbarTerbuka;
        },
        tutupNavbar() {
          this.navbarTerbuka = false;
        },
        revealNama() {
          if (this.namaTerbuka) return;
          this.namaShake = true;
          setTimeout(() => { this.namaShake = false; }, 400);
          this.namaClicks = Math.min(3, this.namaClicks + 1);
          if (this.namaClicks >= 3) this.namaTerbuka = true;
        },
        revealMotto() {
          if (this.mottoTerbuka) return;
          this.mottoShake = true;
          setTimeout(() => { this.mottoShake = false; }, 400);
          this.mottoClicks = Math.min(3, this.mottoClicks + 1);
          if (this.mottoClicks >= 3) this.mottoTerbuka = true;
        },
        revealDeskripsi() {
          if (this.deskripsiTerbuka) return;
          this.deskripsiShake = true;
          setTimeout(() => { this.deskripsiShake = false; }, 400);
          this.deskripsiClicks = Math.min(3, this.deskripsiClicks + 1);
          if (this.deskripsiClicks >= 3) this.deskripsiTerbuka = true;
        },
        klikCurious() {
          this.sudahKlikCurious = true;
          this.secretFound = true;
          document.documentElement.classList.remove("no-scroll");
          document.body.classList.remove("no-scroll");
          window.addEventListener("scroll", this.handleScroll);
          setTimeout(() => { this.secretFound = false; }, 2500);
        }
      },
      mounted() {
        document.documentElement.classList.add("no-scroll");
        document.body.classList.add("no-scroll");
      }
    }).mount("#app");
  </script>
</body>
</html>
