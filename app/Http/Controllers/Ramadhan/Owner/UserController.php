<?php

namespace App\Http\Controllers\Ramadhan\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = User::query()->orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(12)->withQueryString();

        // Jika AJAX → kirim partial
        if ($request->ajax()) {
            return view('ramadhan.users.partials.table', compact('users'))->render();
        }

        return view('ramadhan.users.index', compact('users', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ramadhan.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash', // hanya huruf, angka, dash, dan underscore
                Rule::unique('users')->ignore($request->id),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->id),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed', // membutuhkan password_confirmation
            ],
            'role' => ['nullable', 'in:owner,user'], // opsional, default 'user'
        ], [
            'name.required' => 'Nama harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash, dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.in' => 'Role tidak valid.',
        ]);

        try {
            // Buat user baru
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? 'user',
            ]);

            // Jika ingin mengirim email verifikasi (opsional)
            // if ($user->email) {
            //     $user->sendEmailVerificationNotification();
            // }

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user. Error: ' . $e->getMessage());
        }
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
    public function edit(User $user)
    {
        // Owner tidak boleh edit diri sendiri
        if (
            Auth::user()->role === 'owner' &&
            Auth::id() === $user->id
        ) {
            abort(403, 'Anda tidak boleh mengedit akun sendiri.');
        }

        return view('ramadhan.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => ['nullable', 'confirmed', 'min:8'],
            ],
            [
                // REQUIRED
                'name.required' => 'Nama wajib diisi.',
                'username.required' => 'Username wajib diisi.',

                // UNIQUE
                'username.unique' => 'Username sudah digunakan.',
                'email.unique' => 'Email sudah digunakan.',

                // FORMAT
                'email.email' => 'Format email tidak valid.',

                // PASSWORD
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
