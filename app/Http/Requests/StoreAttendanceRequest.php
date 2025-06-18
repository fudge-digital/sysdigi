<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('coach');
    }

    public function rules(): array
    {
        $rules = [
            'jenis_absen' => ['required', 'in:latihan,pertandingan'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam' => ['required'],
            'siswa_hadir' => ['array', 'nullable'],
            'siswa_hadir.*' => ['exists:users,id'],
            'selected_siswa_hadir' => 'required|string', // string seperti "1,2,3"
        ];

        if ($this->jenis_absen === 'latihan') {
            $rules = array_merge($rules, [
                'tempat_latihan' => ['required'],
                'tempat_latihan_lainnya' => ['nullable', 'string'],
                'foto_latihan' => ['nullable', 'image'],
                'keterangan' => ['nullable', 'string'],
            ]);
        } elseif ($this->jenis_absen === 'pertandingan') {
            $rules = array_merge($rules, [
                'jenis_pertandingan' => ['required', 'in:scrimmage,turnamen'],
                'nama_turnamen' => ['nullable', 'string'],
                'klub_tanding' => ['required', 'string'],
                'tempat_pertandingan' => ['required', 'string'],
                'hasil_skor' => ['required', 'string'],
                'foto_pertandingan' => ['nullable', 'image'],
            ]);
        }

        return $rules;
    }
}

