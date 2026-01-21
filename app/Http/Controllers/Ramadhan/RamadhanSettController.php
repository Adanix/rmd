<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\RamadhanSetting;
use Illuminate\Http\Request;

class RamadhanSettController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = RamadhanSetting::query()->orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('start_date', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%");
            });
        }

        $ramadhanSettings = $query->paginate(12)->withQueryString();

        // Jika AJAX → kirim partial
        if ($request->ajax()) {
            return view('ramadhan.ramadhanSettings.partials.table', compact('ramadhanSettings'))->render();
        }

        return view('ramadhan.ramadhanSettings.index', compact('ramadhanSettings', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ramadhan.ramadhanSettings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'days' => 'required|integer|min:1',
            // 'total_setoran' => 'sometimes|integer|min:0',
            'special_quotas' => 'nullable|json',
            'holidays' => 'nullable|json',
            'notes' => 'nullable|string|max:500',
        ]);

        // Decode JSON fields before saving
        if (isset($validated['special_quotas'])) {
            $validated['special_quotas'] = json_decode($validated['special_quotas'], true);
        }

        if (isset($validated['holidays'])) {
            $validated['holidays'] = json_decode($validated['holidays'], true);
        }

        // Set total_setoran to 0 if not provided (karena dihitung otomatis)
        $validated['total_setoran'] = $validated['total_setoran'] ?? 0;

        try {
            $ramadhanSetting = RamadhanSetting::create($validated);

            return redirect()->route('ramadhan-settings.index')
                ->with('success', 'Setting Ramadhan berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat setting Ramadhan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RamadhanSetting $ramadhanSetting)
    {
        $ramadhanSetting->load('daySettings');

        return view('ramadhan.ramadhanSettings.show', compact('ramadhanSetting'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RamadhanSetting $ramadhanSetting)
    {
        return view('ramadhan.ramadhanSettings.edit', compact('ramadhanSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RamadhanSetting $ramadhanSetting)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'days' => 'required|integer|min:1',
            // 'total_setoran' => 'sometimes|integer|min:0',
            'special_quotas' => 'nullable|json',
            'holidays' => 'nullable|json',
            'notes' => 'nullable|string|max:500',
        ]);

        // Decode JSON fields before saving
        if (isset($validated['special_quotas'])) {
            $validated['special_quotas'] = json_decode($validated['special_quotas'], true);
        } else {
            $validated['special_quotas'] = null;
        }

        if (isset($validated['holidays'])) {
            $validated['holidays'] = json_decode($validated['holidays'], true);
        } else {
            $validated['holidays'] = null;
        }

        try {
            $ramadhanSetting->update($validated);

            return redirect()->route('ramadhan-settings.index')
                ->with('success', 'Setting Ramadhan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui setting Ramadhan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RamadhanSetting $ramadhanSetting)
    {
        try {
            $ramadhanSetting->delete();

            return redirect()->route('ramadhan-settings.index')
                ->with('success', 'Setting Ramadhan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus setting Ramadhan: ' . $e->getMessage());
        }
    }
}
