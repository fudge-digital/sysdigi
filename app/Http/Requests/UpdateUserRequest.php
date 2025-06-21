<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UmurHelper;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        
        $user = $this->route('user');
        $userId = $user?->id;
        $authUser = $this->user();

        $rules = [
            'name' => 'required|string|max:255',
            'niss' => 'required|unique:users,niss,' . $userId,
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:6|confirmed',

            'role' => 'nullable|string|exists:roles,name',

            'jenis_kelamin' => 'nullable|string|in:Putra,Putri',
            'kelompok_umur' => 'nullable|string|max:255',
            'asal_sekolah'  => 'nullable|string',
            'nama_panggilan' => 'nullable|string',
            'nomor_whatsapp' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]{9,15}$/'],
            'nomor_jersey' => 'nullable|string',
            'tinggi_badan'  => 'nullable|int',
            'berat_badan'   => 'nullable|int',
            'alamat'  => 'nullable|string',
            'photo_profile' => 'nullable|image|max:2048',
            'tempat_lahir'  => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',

            'document_kk' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'document_akta' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'document_ijazah' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'document_nisn' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],

            'jabatan' => 'nullable|string|max:100',
            'lisensi' => 'nullable|string|max:100',
        ];

        if ($this->input('role') === 'siswa') {
            $rules['status_siswa'] = 'required|in:aktif,tidak aktif';
        } else {
            $rules['status'] = 'required|in:aktif,tidak aktif';
        }

        // Validasi current_password hanya jika:
        // - User mengubah password
        // - dan user mengedit dirinya sendiri
        // - dan user bukan admin
        if ($this->filled('password') && $authUser->id === $userId && !$authUser->hasRole('admin')) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();

        if ($this->filled('tanggal_lahir')) {
            $data['kategori_umur'] = UmurHelper::hitungKategoriUmur($this->input('tanggal_lahir'));
        }

        return $data;
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}

