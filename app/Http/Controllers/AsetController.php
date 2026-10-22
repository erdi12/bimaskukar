<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetCategory;
use App\Models\AsetDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AsetController extends Controller
{
    private const CONDITIONS = ['baik', 'rusak_ringan', 'rusak_berat'];
    private const STATUSES = ['digunakan', 'tersedia', 'pemeliharaan', 'dihapuskan'];

    public function index(Request $request)
    {
        return view('backend.aset.index', [
            'asets' => $this->filteredAssets($request)->paginate(15)->withQueryString(),
            'categories' => AsetCategory::orderBy('name')->get(),
            'totalAsets' => Aset::count(),
            'goodAsets' => Aset::where('condition', 'baik')->count(),
            'maintenanceAsets' => Aset::where('status', 'pemeliharaan')->count(),
            'conditions' => self::CONDITIONS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function export(Request $request)
    {
        $asets = $this->filteredAssets($request)->get();
        $filename = 'inventaris-aset-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($asets) {
            $output = fopen('php://output', 'w');
            // Excel recognizes this UTF-8 BOM and renders Indonesian labels correctly.
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Kode Inventaris', 'Nama Barang', 'Kategori', 'Merek', 'Tipe/Model', 'Nomor Seri', 'Tanggal Perolehan', 'Sumber Perolehan', 'Nilai Perolehan', 'Kondisi', 'Status', 'Lokasi', 'Penanggung Jawab', 'Spesifikasi Tambahan', 'Catatan'], ';');
            foreach ($asets as $aset) {
                $row = [
                    $aset->asset_code, $aset->name, $aset->category->name, $aset->brand, $aset->model,
                    $aset->serial_number, $aset->acquisition_date?->format('Y-m-d'), $aset->acquisition_source,
                    $aset->acquisition_value, $aset->condition, $aset->status, $aset->location,
                    $aset->responsible_person, json_encode($aset->specifications, JSON_UNESCAPED_UNICODE), $aset->notes,
                ];
                $row = array_map(fn ($value) => is_string($value) && preg_match('/^[=+\-@]/', $value) ? "'{$value}" : $value, $row);
                fputcsv($output, $row, ';');
            }
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function create()
    {
        return view('backend.aset.form', [
            'aset' => new Aset(),
            'categories' => AsetCategory::orderBy('name')->get(),
            'conditions' => self::CONDITIONS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedAset($request);
        unset($data['documents']);
        $aset = Aset::create($data);
        $this->saveDocuments($request, $aset);

        return redirect()->route('aset.show', $aset)->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['category', 'documents']);
        return view('backend.aset.show', compact('aset'));
    }

    public function edit(Aset $aset)
    {
        return view('backend.aset.form', [
            'aset' => $aset,
            'categories' => AsetCategory::orderBy('name')->get(),
            'conditions' => self::CONDITIONS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Aset $aset)
    {
        $data = $this->validatedAset($request, $aset);
        unset($data['documents']);
        $aset->update($data);
        $this->saveDocuments($request, $aset);
        return redirect()->route('aset.show', $aset)->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        foreach ($aset->documents as $document) {
            Storage::disk('local')->delete($document->file_path);
        }
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Data aset berhasil dihapus.');
    }

    public function downloadDocument(Aset $aset, AsetDocument $document)
    {
        $document = $aset->documents()->findOrFail($document->id);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }

    public function previewDocument(Aset $aset, AsetDocument $document)
    {
        $document = $aset->documents()->findOrFail($document->id);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        $previewableTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        abort_unless(in_array($document->mime_type, $previewableTypes, true), 415, 'Pratinjau tersedia untuk PDF dan gambar JPG/PNG.');

        return Storage::disk('local')->response(
            $document->file_path,
            $document->original_name,
            ['X-Content-Type-Options' => 'nosniff'],
            'inline'
        );
    }

    public function destroyDocument(Aset $aset, AsetDocument $document)
    {
        $document = $aset->documents()->findOrFail($document->id);
        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function categories()
    {
        return view('backend.aset.categories', ['categories' => AsetCategory::withCount('asets')->orderBy('name')->get()]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:aset_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        AsetCategory::create($data);
        return redirect()->route('aset.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, AsetCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('aset_categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $category->update($data);
        return redirect()->route('aset.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(AsetCategory $category)
    {
        if ($category->asets()->exists()) {
            return back()->with('error', 'Kategori masih digunakan oleh aset. Pindahkan aset tersebut sebelum menghapus kategori.');
        }
        $category->delete();
        return redirect()->route('aset.categories')->with('success', 'Kategori berhasil dihapus.');
    }

    private function validatedAset(Request $request, ?Aset $aset = null): array
    {
        $data = $request->validate([
            'aset_category_id' => ['required', 'exists:aset_categories,id'],
            'asset_code' => ['required', 'string', 'max:60', Rule::unique('asets', 'asset_code')->ignore($aset?->id)],
            'name' => ['required', 'string', 'max:150'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'acquisition_date' => ['nullable', 'date'],
            'acquisition_source' => ['nullable', 'string', 'max:150'],
            'acquisition_value' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'condition' => ['required', Rule::in(self::CONDITIONS)],
            'status' => ['required', Rule::in(self::STATUSES)],
            'location' => ['nullable', 'string', 'max:150'],
            'responsible_person' => ['nullable', 'string', 'max:150'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.key' => ['nullable', 'string', 'max:80'],
            'specifications.*.value' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'documents' => ['nullable', 'array', 'max:10'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx', 'max:10240'],
        ]);

        $specifications = [];
        foreach ($data['specifications'] ?? [] as $specification) {
            $key = trim($specification['key'] ?? '');
            $value = trim($specification['value'] ?? '');
            if ($key !== '' && $value !== '') {
                $specifications[$key] = $value;
            }
        }
        $data['specifications'] = $specifications ?: null;
        return $data;
    }

    private function saveDocuments(Request $request, Aset $aset): void
    {
        foreach ($request->file('documents', []) as $file) {
            $path = $file->store("aset-documents/{$aset->id}", 'local');
            if ($path === false) {
                throw new \RuntimeException('Dokumen aset gagal disimpan.');
            }

            $aset->documents()->create([
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    private function filteredAssets(Request $request)
    {
        $query = Aset::with('category')->latest();

        if ($request->filled('q')) {
            $search = trim($request->string('q')->toString());
            $query->where(function ($builder) use ($search) {
                $builder->where('asset_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category')) $query->where('aset_category_id', $request->integer('category'));
        if ($request->filled('condition')) $query->where('condition', $request->input('condition'));
        if ($request->filled('status')) $query->where('status', $request->input('status'));

        return $query;
    }
}
