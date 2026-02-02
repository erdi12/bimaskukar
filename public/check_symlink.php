<?php

$target = '/home/kemena10/bimasv2/storage/app/public';
$shortcut = '/home/kemena10/public_html/bimas.kemenagkukar.id/storage';

echo '<h1>Status Symlink / Storage</h1>';

// 1. Cek Target (Folder Asli)
if (file_exists($target) && is_dir($target)) {
    echo "<p style='color:green'>✅ Folder TARGET (sumber) ditemukan: <code>$target</code></p>";
} else {
    echo "<p style='color:red'>❌ Folder TARGET (sumber) TIDAK ditemukan: <code>$target</code>. Pastikan path 'bimasv2' benar.</p>";
}

// 2. Cek Shortcut (Folder di Public)
if (file_exists($shortcut)) {
    if (is_link($shortcut)) {
        echo "<p style='color:green'>✅ Public Storage adalah SYMLINK.</p>";
        echo '<p>Link mengarah ke: '.readlink($shortcut).'</p>';
    } elseif (is_dir($shortcut)) {
        echo "<p style='color:orange'>⚠️ Public Storage adalah FOLDER BIASA (Bukan Symlink).</p>";
        echo '<p>Ini yang menyebabkan file upload tidak muncul. Folder ini harus dihapus lalu dibuat ulang sebagai symlink.</p>';

        echo "<form method='post'>
                <button type='submit' name='fix' value='1'>Hapus Folder & Buat Symlink</button>
              </form>";
    }
} else {
    echo "<p style='color:red'>❌ Shortcut Public Storage belum ada.</p>";
    echo "<form method='post'>
            <button type='submit' name='fix' value='1'>Buat Symlink Sekarang</button>
          </form>";
}

// 3. Action Fix
if (isset($_POST['fix'])) {
    // Hapus jika folder biasa / salah link
    if (file_exists($shortcut)) {
        // Hati-hati menghapus folder, pastikan ini public storage
        // Di shared hosting kadang perlu rename dulu baru unlink jika permission rewel
        if (is_dir($shortcut) && ! is_link($shortcut)) {
            $backupName = $shortcut.'_backup_'.time();
            if (rename($shortcut, $backupName)) {
                echo "<p style='color:blue'>Info: Folder lama berhasil di-rename jadi: ".basename($backupName).'</p>';
            } else {
                echo "<p style='color:red'>Gagal Rename folder. Mencoba memaksa hapus folder kosong...</p>";
                @rmdir($shortcut); // Coba hapus jika kosong
            }
        } elseif (is_link($shortcut)) {
            unlink($shortcut);
        }
    }

    // Cek lagi sebelum link
    if (file_exists($shortcut)) {
        echo "<p style='color:red; font-weight:bold'>❌ Gagal membersihkan folder 'storage' lama. Mohon hapus folder 'storage' di public_html secara manual via File Manager cPanel, lalu refresh halaman ini.</p>";
    } else {
        // COBA RELATIF LINK (Lebih disukai di cPanel/Shared Hosting)
        // Dari: /home/kemena10/public_html/bimas.kemenagkukar.id
        // Ke:   /home/kemena10/bimasv2/storage/app/public
        // Naik 2 level: ../../bimasv2/storage/app/public
        $relativeTarget = '../../bimasv2/storage/app/public';

        echo "<p>Mencoba membuat symlink dengan path relatif: <code>$relativeTarget</code></p>";

        $result = symlink($relativeTarget, $shortcut);

        if ($result) {
            echo "<p style='color:green; font-weight:bold'>✅ SUKSES! Symlink berhasil diperbaiki (Mode Relatif)!</p>";
            echo '<p>Sekarang file upload seharusnya sudah muncul. Silakan cek website Anda.</p>';
            // Auto refresh
            echo "<meta http-equiv='refresh' content='2'>";
        } else {
            // Fallback ke Absolute jika relatif gagal
            echo "<p style='color:orange'>Gagal mode relatif, mencoba mode absolut...</p>";
            $resultAbs = symlink($target, $shortcut);
            if ($resultAbs) {
                echo "<p style='color:green; font-weight:bold'>✅ SUKSES! Symlink berhasil diperbaiki (Mode Absolut)!</p>";
                echo "<meta http-equiv='refresh' content='2'>";
            } else {
                echo "<p style='color:red'>❌ Gagal total. Folder tujuan tidak bisa di-link. Pastikan permission folder 'bimas.kemenagkukar.id' adalah 755.</p>";
            }
        }
    }
}
