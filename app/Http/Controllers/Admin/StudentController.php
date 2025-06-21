<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function show($id)
    {
        $user = User::with(['studentProfile.documents'])->findOrFail($id);

        if (!$user->hasRole('siswa')) {
            abort(403, 'Hanya untuk role siswa.');
        }

        return view('siswa.show', compact('user'));
    }
}
