<?php 
session_start();
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 p-6">

    <div class="flex flex-wrap justify-center gap-4 border-b-2 border-gray-300 pb-4 mb-8">
        <a href="#" class="px-5 py-2 border border-gray-400 rounded text-gray-700 hover:bg-gray-200 transition">Pilihan Beasiswa</a>
        <a href="index.php" class="px-5 py-2 border border-gray-400 rounded text-gray-700 hover:bg-gray-200 transition">Daftar</a>
        <a href="hasil.php" class="px-5 py-2 border border-blue-500 bg-blue-500 text-white font-bold rounded shadow">Hasil</a>
    </div>

    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">DATA HASIL PENDAFTARAN BEASISWA</h2>

        <div class="bg-white shadow-md rounded-lg overflow-x-auto border border-gray-200">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-blue-100 text-gray-700 border-b border-gray-300 uppercase text-sm">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-300">No</th>
                        <th class="py-3 px-4 border-r border-gray-300">Nama</th>
                        <th class="py-3 px-4 border-r border-gray-300">Email</th>
                        <th class="py-3 px-4 border-r border-gray-300">Nomor HP</th>
                        <th class="py-3 px-4 border-r border-gray-300">Semester</th>
                        <th class="py-3 px-4 border-r border-gray-300">IPK</th>
                        <th class="py-3 px-4 border-r border-gray-300">Pilihan Beasiswa</th>
                        <th class="py-3 px-4 border-r border-gray-300 text-center">Berkas</th>
                        <th class="py-3 px-4">Status Ajuan</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-medium">
                    <?php
                    $no = 1;
                    $data = mysqli_query($conn, "SELECT * FROM pendaftaran ORDER BY id DESC");
                    while($row = mysqli_fetch_array($data)) {
                    ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 border-r border-gray-200 text-center"><?php echo $no++; ?></td>
                        <td class="py-3 px-4 border-r border-gray-200"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="py-3 px-4 border-r border-gray-200"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="py-3 px-4 border-r border-gray-200"><?php echo htmlspecialchars($row['no_hp']); ?></td>
                        <td class="py-3 px-4 border-r border-gray-200 text-center"><?php echo $row['semester']; ?></td>
                        <td class="py-3 px-4 border-r border-gray-200 font-bold text-gray-800 text-center"><?php echo $row['ipk']; ?></td>
                        <td class="py-3 px-4 border-r border-gray-200"><?php echo htmlspecialchars($row['pilihan_beasiswa']); ?></td>
                        <td class="py-3 px-4 border-r border-gray-200 text-center">
                            <a href="uploads/<?php echo $row['berkas']; ?>" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline">Lihat File</a>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold border border-yellow-300">
                                <?php echo strtoupper($row['status_ajuan']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full transform transition-all text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Berhasil!</h3>
            <p id="successMessage" class="text-md text-gray-600 mb-6"></p>
            <button onclick="document.getElementById('successModal').classList.add('hidden')" class="w-full px-4 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-600 transition shadow">Selesai</button>
        </div>
    </div>

    <script>
        // Memicu Modal Sukses dari PHP Session
        <?php if (isset($_SESSION['flash_success'])): ?>
            document.getElementById('successMessage').innerText = "<?php echo $_SESSION['flash_success']; ?>";
            document.getElementById('successModal').classList.remove('hidden');
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
    </script>
</body>
</html>