<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;

trait HandlesCategoryAssignments
{
    protected function syncHandledCategories(User $user, array $handledCategories): void
    {
        $insertData = [];

        foreach ($handledCategories as $item) {
            [$kategori_umur, $jenis_kelamin] = explode('|', $item);
            $studentIds = DB::table('student_profiles')
                ->where('kategori_umur', $kategori_umur)
                ->where('jenis_kelamin', $jenis_kelamin)
                ->pluck('user_id');

            foreach ($studentIds as $student_id) {
                $insertData[] = [
                    'coach_id' => $user->id,
                    'student_id' => $student_id,
                    'kategori_umur' => $kategori_umur,
                    'jenis_kelamin' => $jenis_kelamin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('coach_student_category')->where('coach_id', $user->id)->delete();

        if (!empty($insertData)) {
            DB::table('coach_student_category')->insert($insertData);
        }
    }
}
