<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // GET /api/users
    public function index()
    {
        // Ambil semua user (bisa juga kamu filter kalau mau)
        $users = User::all();

        return response()->json($users, 200);
    }

    // GET /api/users/{id}
    public function show(User $user)
    {
        // Otorisasi: hanya user pemilik akun yg bisa lihat
        if (Auth::id() !== $user->id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak melihat data ini.');
        }

        return response()->json([
            'data' => $user
        ], 200);
    }

    // PUT /api/users/{id}
    public function update(Request $request, User $user)
    {
        // Otorisasi: hanya user pemilik akun yg bisa update
        if (Auth::id() !== $user->id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak mengubah data ini.');
        }

        // Validasi
        $request->validate([
            'name' => 'string|max:255|nullable',
            'username' => 'string|max:255|unique:users,username,' . $user->id,
        ]);

        // Update data
        $user->update($request->only('name', 'username'));

        return response()->json([
            'data' => $user->fresh()
        ], 200);
    }

    // DELETE /api/users/{id}
    public function destroy(User $user)
    {
        // Otorisasi: hanya user pemilik akun yg bisa hapus
        if (Auth::id() !== $user->id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak menghapus data ini.');
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
