<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\Makanan;
use Illuminate\Http\Request;

class MakananController extends Controller
{
    /**
     * Display a listing of the resource.
     * Search belum realtime
     */
    // public function index(Request $request)
    // {
    //     $q = $request->get('q');

    //     $makanans = Makanan::when($q, function ($query) use ($q) {
    //         $query->where('nama', 'like', "%$q%");
    //     })
    //         ->orderBy('id', 'desc')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view('ramadhan.makanans.index', compact('makanans', 'q'));
    // }

    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Makanan::query()->orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $makanans = $query->paginate(12)->withQueryString();

        // Jika AJAX → kirim partial
        if ($request->ajax()) {
            return view('ramadhan.makanans.partials.table', compact('makanans'))->render();
        }

        return view('ramadhan.makanans.index', compact('makanans', 'q'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ramadhan.makanans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Makanan::create($validated);

        return redirect()->route('makanans.index')
            ->with('success', 'Makanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Makanan $makanan)
    {
        return view('ramadhan.makanans.edit', compact('makanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Makanan $makanan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $makanan->update($validated);

        return redirect()->route('makanans.index')
            ->with('success', 'Makanan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Makanan $makanan)
    {
        $makanan->delete();

        return redirect()->route('makanans.index')
            ->with('success', 'Makanan berhasil dihapus.');
    }
}
