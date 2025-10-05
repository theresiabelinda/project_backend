<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // GET /api/users/{id}
    public function show(User $user)
    {
        // Otorisasi: Hanya user pemilik akun yang bisa melihat data mereka sendiri
        if (Auth::id() !== $user->id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak mengubah data ini.');
        }

        return response()->json([
            'data' => $user
        ], 200);
    }

    // PUT /api/users/{id}
    public function update(Request $request, User $user)
    {
        // Otorisasi: Hanya user pemilik akun yang bisa update
        if (Auth::id() !== $user->id) {
            // Ini akan membuat test 'unauthorized_user_cannot_access_other_users_data' lulus 403
            abort(403, 'Akses Ditolak. Anda tidak berhak mengubah data ini.');
        }

        // Validasi (hanya username dan name yang dicontohkan)
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
        // Otorisasi: Hanya user pemilik akun yang bisa menghapus
        if (Auth::id() !== $user->id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak mengubah data ini.');
        }

        // Hapus
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
