<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->has('start')) {
            $start = date('Y-m-d', strtotime($request->start));
            $end = date('Y-m-d', strtotime($request->end));

            $data = Kegiatan::where('start_date', '>=', $start)
                ->where('start_date', '<=', $end)
                ->get(['id', 'title', 'start_date as start', 'end_date as end', 'color', 'description', 'location', 'petugas', 'type']);
            return response()->json($data);
        }
        return view('backend.kegiatan.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:255',
        ]);

        $kegiatan = Kegiatan::create($request->all());

        return response()->json($kegiatan);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:255',
        ]);

        $kegiatan->update($request->all());

        return response()->json($kegiatan);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
