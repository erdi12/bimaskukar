<footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2022 &copy; Voler</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class='text-danger'><i data-feather="heart"></i></span> by <a href="https://saugi.me">Saugi</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @push('scripts')
    <script>
        // $(document).ready(function() {
        //     // Inisialisasi Select2 pada dropdown kecamatan
        //     $('#kecamatan').select2({
        //         placeholder: "Pilih Kecamatan",
        //         allowClear: true
        //     }).on('select2:unselecting', function() {
        //         // Reset dan disable kelurahan saat kecamatan di-clear
        //         $('#kelurahan').val(null).empty().prop('disabled', true).trigger('change');
        //     });

        //     // Inisialisasi Select2 pada dropdown kelurahan
        //     $('#kelurahan').select2({
        //         placeholder: "Pilih Kelurahan",
        //         allowClear: true
        //     });

        //     // Event listener untuk perubahan pada select kecamatan
        //     // Hapus event handler yang ada sebelumnya untuk mencegah duplikasi
        //     $('#kecamatan').off('change').on('change', function() {
        //         const kecamatanId = $(this).val();
                
        //         // Reset select kelurahan
        //         $('#kelurahan').val(null).empty().append('<option value="">Pilih Kelurahan</option>');
                
        //         // Jika tidak ada kecamatan yang dipilih, disable select kelurahan
        //         if (!kecamatanId) {
        //             $('#kelurahan').prop('disabled', true).trigger('change');
        //             return;
        //         }
                
        //         // Ambil data kelurahan berdasarkan kecamatan_id
        //         $.ajax({
        //             url: `/api/kelurahans/${kecamatanId}`,
        //             type: 'GET',
        //             dataType: 'json',
        //             success: function(data) {
        //                 // Enable select kelurahan
        //                 $('#kelurahan').prop('disabled', false);
                        
        //                 // Bersihkan opsi yang ada sebelum menambahkan yang baru
        //                 $('#kelurahan').empty().append('<option value="">Pilih Kelurahan</option>');
                        
        //                 // Tambahkan opsi kelurahan
        //                 if (Array.isArray(data)) {
        //                     data.forEach(function(kelurahan) {
        //                         $('#kelurahan').append(new Option(
        //                             ucwords(kelurahan.nama_kelurahan),
        //                             kelurahan.id,
        //                             false,
        //                             false
        //                         ));
        //                     });
        //                 }
                        
        //                 // Trigger change untuk refresh Select2
        //                 $('#kelurahan').trigger('change');
        //             },
        //             error: function(error) {
        //                 console.error('Error:', error);
        //                 alert('Gagal mengambil data kelurahan');
        //             }
        //         });
        //     });

        //     // Fungsi untuk mengubah huruf pertama setiap kata menjadi kapital
        //     function ucwords(str) {
        //         return str.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
        //     }
        // });
    </script>
    @endpush