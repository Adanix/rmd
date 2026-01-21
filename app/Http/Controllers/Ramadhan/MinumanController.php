<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\Minuman;
use Illuminate\Http\Request;

class MinumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Minuman::query()->orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $minumans = $query->paginate(12)->withQueryString();

        // Jika AJAX → kirim partial
        if ($request->ajax()) {
            return view('ramadhan.minumans.partials.table', compact('minumans'))->render();
        }

        return view('ramadhan.minumans.index', compact('minumans', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ramadhan.minumans.create');
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

        Minuman::create($validated);

        return redirect()->route('minumans.index')
            ->with('success', 'Minuman berhasil ditambahkan.');
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
    public function edit(Minuman $minuman)
    {
        return view('ramadhan.minumans.edit', compact('minuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Minuman $minuman)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $minuman->update($validated);

        return redirect()->route('minumans.index')
            ->with('success', 'Minuman berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Minuman $minuman)
    {
        $minuman->delete();

        return redirect()->route('minumans.index')
            ->with('success', 'Minuman berhasil dihapus.');
    }
}
