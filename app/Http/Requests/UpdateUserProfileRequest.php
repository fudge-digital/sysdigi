<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Hak akses diatur di controller
    }

    public function rules(): array
    {
        $userId = auth()->id();
        $isAdmin = auth()->user()->hasRole('admin');
        $isSelf = auth()->id() == $userId;

        return [
            'name' => 'required|string|max:255',
            'niss' => $isAdmin ? 'nullable|unique:users,niss,' . $userId : 'prohibited',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'role' => $isAdmin ? 'nullable|exists:roles,name' : 'prohibited',

            'current_password' => $isAdmin ? 'nullable' : 'nullable|required_with:password',
            'password' => 'nullable|confirmed|min:6',

            'nama_panggilan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:putra,putri',
            'alamat' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tinggi_badan' => 'nullable|integer|min:0',
            'berat_badan' => 'nullable|integer|min:0',
            'asal_sekolah' => 'nullable|string|max:255',
            'nomor_whatsapp' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]{9,15}$/'],
            'nomor_jersey' => 'nullable|string',
            'photo_profile' => 'nullable|image|max:2048',

            'jabatan' => $isAdmin ? 'nullable|string|max:100' : 'prohibited',
            'lisensi' => 'nullable|string|max:100',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nomor_whatsapp') && $this->filled('nomor_whatsapp')) {
            $nomor = $this->input('nomor_whatsapp');
            $nomor = preg_replace('/^(\+62|62|0)/', '', $nomor);
            $nomor = '62' . $nomor;

            $this->merge([
                'nomor_whatsapp' => $nomor,
            ]);
        }
    }
    
}

