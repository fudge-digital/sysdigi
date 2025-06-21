<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HandlesDocumentUpload
{
    protected function handleStudentDocumentsUpload(Request $request, $user): void
    {
        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            return;
        }

        $jenisList = ['kk', 'akta', 'ijazah', 'nisn'];

        foreach ($jenisList as $jenis) {
            $field = "document_{$jenis}";

            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Nama file berdasarkan niss + jenis + timestamp
                $filenameBase = $user->niss ?? Str::slug($user->name);
                $filename = $filenameBase . '_' . strtoupper($jenis) . '_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();

                $folder = 'student_documents';
                $relativePath = $folder . '/' . $filename;

                // Buat folder jika belum ada
                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                // Hapus file lama jika ada
                $existingDoc = $studentProfile->documents()->where('jenis_dokumen', strtoupper($jenis))->first();
                if ($existingDoc && file_exists(public_path($existingDoc->file_path))) {
                    unlink(public_path($existingDoc->file_path));
                }

                // Pindahkan file baru
                $file->move(public_path($folder), $filename);

                // Simpan ke DB
                $studentProfile->documents()->updateOrCreate(
                    ['jenis_dokumen' => strtoupper($jenis)],
                    ['file_path' => $relativePath]
                );
            }
        }
    }
}
