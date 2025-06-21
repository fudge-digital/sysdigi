<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UmurHelper;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'niss' => 'required|unique:users,niss',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
            'jenis_kelamin' => 'nullable|string|in:Putra,Putri',
            'kelompok_umur' => 'nullable|string|max:255',
            'cropped_image' => 'nullable|image|max:2048',

            // Additional fields
            'nama_panggilan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:1000',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'asal_sekolah' => 'nullable|string|max:255',
            'nomor_whatsapp' => 'nullable|string|max:20',
            'nomor_jersey' => 'nullable|string|max:10',
            'status_siswa' => 'nullable|string|max:255',
            'photo_profile' => 'nullable|image|max:2048',

            'document_kk' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'document_akta' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'document_ijazah' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'document_nisn' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],

            'jabatan' => 'nullable|string|max:255',
            'lisensi' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ];
    }

    /**
     * Modifikasi data yang divalidasi sebelum dikirim ke controller.
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated();

        // Tambahkan kategori_umur otomatis jika tanggal_lahir tersedia
        if ($this->filled('tanggal_lahir')) {
            $data['kategori_umur'] = UmurHelper::hitungKategoriUmur($this->input('tanggal_lahir'));
        }

        return $data;
    }
}

