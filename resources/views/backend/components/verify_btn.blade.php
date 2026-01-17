@if (!isset($marbot) || $marbot->status != 'disetujui')
    <div class="btn-group {{ isset($small) ? 'btn-group-sm' : 'btn-group-sm' }}" role="group">
        <!-- Valid Button -->
        <button type="button" class="btn btn-outline-success btn-verify-valid" title="Tandai Valid">
            <i class="fas fa-check"></i>
        </button>
        <!-- Invalid Button -->
        <button type="button" class="btn btn-outline-danger btn-verify-invalid" title="Tandai Tidak Valid">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
