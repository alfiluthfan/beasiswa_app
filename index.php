<?php
session_start();
$ipk_sistem = 3.4; // Silakan ubah untuk uji coba
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Beasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 p-6">

    <div class="flex flex-wrap justify-center gap-4 border-b-2 border-gray-300 pb-4 mb-8">
        <a href="#" class="px-5 py-2 border border-gray-400 rounded text-gray-700 hover:bg-gray-200 transition">Pilihan Beasiswa</a>
        <a href="index.php" class="px-5 py-2 border border-blue-500 bg-blue-500 text-white font-bold rounded shadow">Daftar</a>
        <a href="hasil.php" class="px-5 py-2 border border-gray-400 rounded text-gray-700 hover:bg-gray-200 transition">Hasil</a>
    </div>

    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">DAFTAR BEASISWA</h2>

    <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-200 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-6 border-b pb-2 text-gray-700">Registrasi Beasiswa</h3>
        
        <form action="proses.php" method="POST" enctype="multipart/form-data" onsubmit="konfirmasiDaftar(event, this)">
            
            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-600">Masukkan Nama</label>
                <input type="text" name="nama" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-600">Masukkan Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-600">Nomor HP</label>
                <input type="tel" name="no_hp" minlength="10" maxlength="15" oninput="this.value = this.value.replace(/[^0-9+]/g, '');" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-600">Semester saat ini</label>
                <select name="semester" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">Pilih Semester</option>
                    <?php for($i=1; $i<=8; $i++) { echo "<option value='$i'>$i</option>"; } ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-600">IPK terakhir</label>
                <input type="text" id="ipk" name="ipk" value="<?php echo $ipk_sistem; ?>" readonly class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-md cursor-not-allowed text-gray-600 font-bold">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-600">Pilihan Beasiswa</label>
                <select name="pilihan_beasiswa" id="pilihan_beasiswa" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">Pilih Jenis Beasiswa</option>
                    <option value="Beasiswa Akademik">Beasiswa Akademik</option>
                    <option value="Beasiswa Non-Akademik">Beasiswa Non-Akademik</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block mb-1 font-semibold text-gray-600">Upload Berkas Syarat</label>
                <input type="file" name="berkas" id="berkas" accept=".pdf,.jpg,.jpeg,.zip" onchange="validasiFile(this)" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                <p class="text-sm text-gray-500 mt-1">* Maksimal ukuran file 2 MB (.pdf, .jpg, .zip)</p>
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" id="btn_daftar" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-md transition duration-200 shadow">Daftar Sekarang</button>
                <button type="reset" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-md transition duration-200 shadow">Batal</button>
            </div>
        </form>
    </div>

    <div id="customModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full transform transition-all">
            <h3 id="modalTitle" class="text-xl font-bold mb-2 text-gray-800">Pemberitahuan</h3>
            <p id="modalMessage" class="text-md text-gray-600 mb-6"></p>
            <div class="flex justify-end gap-3">
                <button id="modalCancelBtn" class="hidden px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded hover:bg-gray-300 transition">Batal</button>
                <button id="modalOkBtn" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition shadow">OK</button>
            </div>
        </div>
    </div>

    <script>
        // Logika UX Bawaan
        window.onload = function() {
            var ipk = parseFloat(document.getElementById('ipk').value);
            var elemenPilihan = document.getElementById('pilihan_beasiswa');
            var elemenBerkas = document.getElementById('berkas');
            var btnDaftar = document.getElementById('btn_daftar');

            if (ipk < 3.0) {
                elemenPilihan.disabled = true;
                elemenBerkas.disabled = true;
                btnDaftar.disabled = true;
                btnDaftar.classList.replace('bg-green-500', 'bg-gray-400');
                btnDaftar.classList.replace('hover:bg-green-600', 'hover:bg-gray-400');
                btnDaftar.classList.add('cursor-not-allowed');
                elemenPilihan.removeAttribute('required');
                elemenBerkas.removeAttribute('required');
            } else {
                elemenPilihan.focus();
            }
        }

        // --- SISTEM MODAL POPUP TAILWIND ---
        const modal = document.getElementById('customModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalOkBtn = document.getElementById('modalOkBtn');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        let formToSubmit = null;

        function showModal(title, message, isConfirm = false) {
            modalTitle.innerText = title;
            modalMessage.innerText = message;
            modal.classList.remove('hidden');

            if (isConfirm) {
                modalCancelBtn.classList.remove('hidden');
                modalOkBtn.innerText = "Ya, Kirim";
                modalOkBtn.className = "px-4 py-2 bg-green-500 text-white font-semibold rounded hover:bg-green-600 transition shadow";
                
                modalOkBtn.onclick = function() {
                    if(formToSubmit) formToSubmit.submit();
                };
                modalCancelBtn.onclick = function() {
                    modal.classList.add('hidden');
                };
            } else {
                modalCancelBtn.classList.add('hidden');
                modalOkBtn.innerText = "Mengerti";
                modalOkBtn.className = "px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition shadow";
                
                modalOkBtn.onclick = function() {
                    modal.classList.add('hidden');
                };
            }
        }

        // Cek Ukuran File
        function validasiFile(input) {
            const file = input.files[0];
            if (file && (file.size / (1024 * 1024) > 2)) {
                showModal("File Terlalu Besar", "Maaf, ukuran berkas melebihi batasan maksimal 2 MB. Silakan pilih file lain yang lebih kecil.");
                input.value = ""; 
            }
        }

        // Konfirmasi Submit
        function konfirmasiDaftar(event, form) {
            event.preventDefault(); // Tahan pengiriman data
            formToSubmit = form;
            showModal("Konfirmasi Data", "Apakah Anda yakin seluruh data yang Anda isi sudah benar dan siap untuk dikirim?", true);
        }

        // Memicu Modal dari PHP Session (Jika ada error dari server)
        <?php if (isset($_SESSION['flash_error'])): ?>
            showModal("Pendaftaran Gagal", "<?php echo $_SESSION['flash_error']; ?>");
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>