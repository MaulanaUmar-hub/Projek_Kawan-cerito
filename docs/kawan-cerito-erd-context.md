# Konteks ERD Kawan Cerito

Gunakan konteks ini jika ingin meminta ChatGPT membuat penjelasan rinci ERD, narasi database, atau bagian laporan.

## Prompt Siap Pakai

Saya sedang menyusun penjelasan ERD untuk aplikasi web Laravel bernama **Kawan Cerito**, yaitu sistem telekonseling kesehatan mental berbasis web. Tolong jelaskan ERD secara rinci, akademis, dan mudah dipahami untuk laporan akhir PWEB.

Konteks sistem:

- Aplikasi memiliki tiga role utama: **admin**, **konseli**, dan **konselor**.
- `users` digunakan untuk autentikasi dan penyimpanan role.
- Data profil konseli dipisah di tabel `konseli`.
- Data profil konselor dipisah di tabel `konselor`.
- Konselor dapat berstatus `pending`, `aktif`, atau `ditolak`.
- Konseli harus mengisi assessment awal sebelum mengajukan konseling.
- Konseli mengajukan konseling kepada konselor aktif berdasarkan assessment dan usulan jadwal.
- Konselor meninjau pengajuan, lalu dapat menyetujui, menolak, atau mengusulkan reschedule.
- Jika disetujui, pengajuan terhubung dengan jadwal konseling.
- Setelah sesi selesai, konselor membuat hasil konseling.
- Admin memantau pengguna, approval konselor, dan activity log.

Tabel utama:

1. `users`
   - Primary key: `id_user`
   - Kolom penting: `nama`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`
   - Role: `admin`, `konseli`, `konselor`
   - Fungsi: menyimpan data akun dan autentikasi.

2. `konseli`
   - Primary key: `id_konseli`
   - Foreign key: `id_user` mengarah ke `users.id_user`
   - Kolom: `asal`, `no_hp`, `gender`, `foto`, timestamps
   - Fungsi: menyimpan profil tambahan milik konseli.

3. `konselor`
   - Primary key: `id_konselor`
   - Foreign key: `id_user` mengarah ke `users.id_user`
   - Kolom: `spesialisasi`, `peminatan`, `catatan_profil`, `no_hp`, `gender`, `foto`, `link_whatsapp`, `status`, timestamps
   - Fungsi: menyimpan profil konselor dan status approval admin.

4. `assessments`
   - Primary key: `id_assessment`
   - Foreign key: `id_konseli` mengarah ke `konseli.id_konseli`
   - Kolom: `keluhan`, `created_at`
   - Fungsi: mencatat keluhan/kondisi awal konseli sebelum pengajuan konseling.

5. `jadwal`
   - Primary key: `id_jadwal`
   - Foreign key: `id_konselor` mengarah ke `konselor.id_konselor`
   - Kolom: `tanggal`, `jam`, `status_jadwal`, `tipe_konseling`, timestamps
   - Fungsi: menyimpan jadwal konseling yang dikelola atau dikonfirmasi oleh konselor.

6. `pengajuan_konselings`
   - Primary key: `id_pengajuan`
   - Foreign key:
     - `id_konseli` ke `konseli.id_konseli`
     - `id_konselor` ke `konselor.id_konselor`
     - `id_assessment` ke `assessments.id_assessment`
     - `id_jadwal` ke `jadwal.id_jadwal`, nullable
   - Kolom proses: `status_pengajuan`, `tanggal_usulan`, `jam_usulan`, `tipe_konseling_usulan`, `tanggal_reschedule`, `jam_reschedule`, `catatan_reschedule`, `alasan_penolakan`, `created_at`
   - Fungsi: menyimpan transaksi utama proses pengajuan konseling.
   - Status pengajuan: `menunggu`, `disetujui`, `reschedule`, `ditolak`, `selesai`.

7. `hasil_konseling`
   - Primary key: `id_hasil`
   - Foreign key: `id_pengajuan` ke `pengajuan_konselings.id_pengajuan`
   - Kolom: `catatan_konseling`, `rekomendasi`, `created_at`
   - Fungsi: menyimpan catatan dan rekomendasi hasil sesi konseling.

8. `activity_logs`
   - Primary key: `id_log`
   - Foreign key: `id_user` ke `users.id_user`
   - Kolom: `aktivitas`, `created_at`
   - Fungsi: mencatat aktivitas penting pengguna dan sistem.

Tabel pendukung autentikasi Laravel:

- `password_reset_tokens`
- `sessions`

Relasi utama:

- Satu `users` dapat memiliki satu profil `konseli`.
- Satu `users` dapat memiliki satu profil `konselor`.
- Satu `users` dapat memiliki banyak `activity_logs`.
- Satu `konseli` dapat memiliki banyak `assessments`.
- Satu `konseli` dapat memiliki banyak `pengajuan_konselings`.
- Satu `konselor` dapat memiliki banyak `jadwal`.
- Satu `konselor` dapat menangani banyak `pengajuan_konselings`.
- Satu `assessment` menjadi dasar untuk satu atau beberapa pengajuan, tergantung kebutuhan implementasi.
- Satu `jadwal` dapat dihubungkan dengan pengajuan setelah pengajuan disetujui.
- Satu `pengajuan_konselings` dapat memiliki satu `hasil_konseling`.

Tolong buatkan:

1. Penjelasan fungsi setiap entitas.
2. Penjelasan primary key dan foreign key.
3. Penjelasan kardinalitas relasi.
4. Penjelasan alur data dari register, assessment, pengajuan konseling, jadwal, telekonseling, sampai hasil konseling.
5. Penjelasan peran admin, konselor, dan konseli terhadap data.
6. Catatan mengapa profil konseli dan konselor dipisahkan dari tabel users.
7. Narasi akademis yang cocok dimasukkan ke laporan akhir.

## Ringkasan ERD Implementasi

ERD Kawan Cerito berpusat pada tabel `users` sebagai tabel autentikasi dan role. Karena setiap role memiliki kebutuhan profil berbeda, data profil dipisahkan ke tabel `konseli` dan `konselor`.

Tabel `konseli` menyimpan identitas tambahan pengguna yang menerima layanan konseling. Tabel `konselor` menyimpan data profesional konselor, termasuk spesialisasi, peminatan, catatan profil, WhatsApp, foto, dan status approval.

Proses layanan dimulai dari `assessments`, yaitu data keluhan awal dari konseli. Setelah itu konseli membuat `pengajuan_konselings` dengan memilih konselor dan mengusulkan waktu. Konselor kemudian dapat menyetujui, menolak, atau melakukan reschedule. Jika pengajuan disetujui, pengajuan akan terhubung ke `jadwal`. Setelah konseling selesai, catatan sesi disimpan di `hasil_konseling`.

Admin tidak menjadi bagian langsung dari transaksi konseling, tetapi berperan mengelola user, approval konselor, dan memantau aktivitas melalui `activity_logs`.

## Catatan Penyesuaian Dari Implementasi

- Nama tabel jadwal pada implementasi adalah `jadwal`, bukan `jadwals`.
- Tabel `pengajuan_konselings` adalah tabel transaksi utama.
- Kolom `id_jadwal` pada `pengajuan_konselings` bersifat nullable karena jadwal final baru ditentukan setelah pengajuan diproses konselor.
- `activity_logs` mencatat aktivitas umum dan terkait ke `users`, bukan langsung ke konseli/konselor.
- Tabel `sessions` dan `password_reset_tokens` berasal dari fitur autentikasi Laravel, sehingga bisa ditampilkan sebagai tabel pendukung atau diabaikan jika diagram hanya fokus pada domain telekonseling.

## File PlantUML

PlantUML ERD tersedia di:

`docs/kawan-cerito-erd.puml`
