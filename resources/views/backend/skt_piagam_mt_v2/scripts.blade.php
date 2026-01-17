@push('scripts')
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Function to handle modal cleanup
    function cleanupModal(modalElement) {
        if (modalElement) {
            const bsModal = bootstrap.Modal.getInstance(modalElement);
            if (bsModal) {
                bsModal.dispose();
            }
            modalElement.remove();
        }
    }

    // Function to get or create modal
    function getOrCreateModal(id, type) {
        const modalId = `${type}Modal${id}`;
        let modalElement = document.getElementById(modalId);
        
        if (!modalElement) {
            const templateId = `#${type}ModalTemplate`;
            const template = document.querySelector(templateId);
            if (!template) return null;

            const modalDiv = document.createElement('div');
            modalDiv.className = 'modal fade';
            modalDiv.id = modalId;
            modalDiv.setAttribute('tabindex', '-1');
            modalDiv.setAttribute('aria-hidden', 'true');
            
            // Clone the template content and replace ID placeholders
            let modalContent = template.innerHTML;
            modalContent = modalContent.replace(/__id__/g, id);
            modalDiv.innerHTML = modalContent;
            
            document.body.appendChild(modalDiv);
            modalElement = document.getElementById(modalId);
        }
        
        return new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: false
        });
    }

    $(document).ready(function() {
        let table = $('#dataTable').DataTable({
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            fixedHeader: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('skt_piagam_mt.index') }}",
                data: function(d) {
                    d.kecamatan_id = $('#kecamatan_filter').val();
                    d.kelurahan_id = $('#kelurahan_filter').val();
                },
                error: function (xhr, error, thrown) {
                    console.error('DataTables error:', error);
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                }
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nomor_statistik', name: 'nomor_statistik' },
                { data: 'nama_majelis', name: 'nama_majelis' },
                { data: 'alamat', name: 'alamat' },
                { data: 'kelurahan.nama_kelurahan', name: 'kelurahan.nama_kelurahan' },
                { data: 'kecamatan.kecamatan', name: 'kecamatan.kecamatan', 
                  render: function(data) {
                      return data.charAt(0).toUpperCase() + data.slice(1);
                  }
                },
                { data: 'status_badge', name: 'status', orderable: true, searchable: true },
                { data: 'ketua', name: 'ketua' },
                { data: 'no_hp', name: 'no_hp' },
                { data: 'mendaftar', name: 'mendaftar' },
                { data: 'mendaftar_ulang', name: 'mendaftar_ulang' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'documents', name: 'documents', orderable: false, searchable: false },
                { data: 'berkas', name: 'berkas', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            },
            drawCallback: function() {
                // Re-initialize any components after table redraw
                if (typeof Swal !== 'undefined') {
                    console.log('SweetAlert2 initialized');
                }
            }
        });

        // Event delegation for modals in DataTables
        $('#dataTable').on('click', '[data-bs-toggle="modal"]', function(e) {
            e.preventDefault();
            const targetId = $(this).data('bs-target');
            const modalType = targetId.replace(/Modal\d+$/, '').replace('#', '');
            const id = targetId.match(/\d+$/)[0];
            
            // Remove any existing modal with the same ID
            $(`#${modalType}Modal${id}`).remove();
            
            const modal = getOrCreateModal(id, modalType);
            if (modal) {
                modal.show();
            }
        });

        // Re-initialize components after DataTables updates
        $('#dataTable').on('draw.dt', function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.modal.fade').each(function() {
                if (!$(this).hasClass('show')) {
                    $(this).remove();
                }
            });
        });

        // Refresh table when filters change
        $('#kecamatan_filter, #kelurahan_filter').change(function() {
            table.draw();
        });
    });

    // Function to create and submit dynamic form
    function submitDynamicForm(templateId, id) {
        const templateForm = document.querySelector(templateId);
        const formHtml = templateForm.innerHTML.replace(/__id__/g, id);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = formHtml;
        const form = tempDiv.firstElementChild;
        document.body.appendChild(form);
        form.submit();
        setTimeout(() => form.remove(), 100);
    }

    // Fungsi konfirmasi hapus
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus akan masuk ke trash dan dapat dipulihkan kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitDynamicForm('#delete-form-template', id);
            }
        });
    }
    
    // Fungsi konfirmasi hapus file SKT
    function confirmDeleteSkt(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File SKT akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitDynamicForm('#delete-skt-form-template', id);
            }
        });
    }
    
    // Fungsi konfirmasi hapus file Piagam
    function confirmDeletePiagam(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File Piagam akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitDynamicForm('#delete-piagam-form-template', id);
            }
        });
    }
    
    // Fungsi konfirmasi hapus file Berkas
    function confirmDeleteBerkas(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File Berkas akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitDynamicForm('#delete-berkas-form-template', id);
            }
        });
    }
</script>
@endpush