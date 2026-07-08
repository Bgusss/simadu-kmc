<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Opd;

class AdminOpdController extends Controller
{
    public function index(Request $request)
    {
        $query = Opd::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
        }

        $opds = $query->orderBy('name', 'asc')->paginate(5)->withQueryString();

        return view('admin.opd.index', compact('opds'));
    }

    public function create()
    {
        return view('admin.opd.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $opd = Opd::create($request->only('name'));

        \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'opd',
            'opd_id' => $opd->id,
        ]);

        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil ditambahkan.');
    }

    public function edit(Opd $opd)
    {
        $user = \App\Models\User::where('opd_id', $opd->id)->first();
        return view('admin.opd.edit', compact('opd', 'user'));
    }

    public function update(Request $request, Opd $opd)
    {
        $user = \App\Models\User::where('opd_id', $opd->id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . ($user ? $user->id : ''),
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : ''),
            'password' => 'nullable|string|min:6'
        ]);

        $opd->update($request->only('name'));

        if ($user) {
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            }
            $user->save();
        } else {
            \App\Models\User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password ?? '000000'),
                'role' => 'opd',
                'opd_id' => $opd->id,
            ]);
        }

        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil diperbarui.');
    }

    public function destroy(Opd $opd)
    {
        // User is deleted by cascadeOnDelete logic if foreign key has it. 
        // Our migration specifies nullOnDelete for opd_id in users, so we manually delete.
        \App\Models\User::where('opd_id', $opd->id)->delete();
        $opd->delete();

        return redirect()->route('admin.opd.index')->with('success', 'OPD berhasil dihapus.');
    }
}
