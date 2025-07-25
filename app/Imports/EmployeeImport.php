<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class EmployeeImport implements ToModel, WithHeadingRow
{
    // Fungsi utama untuk mendownload dan menyimpan data
    public function model(array $row)
    {
        // Proses kategori
        $category = Category::firstOrCreate(['name' => $row['category']]);

        // Mendownload foto dari Google Drive dan simpan ke server
        // $photoUrl = $this->convertGoogleDriveToDirectLink($row['photo_url']);
        // $photoPath = $this->downloadAndSaveImage($photoUrl);

        // Menyimpan data karyawan ke database
        return new Employee([
            'name' => $row['name'],
            'category_id' => $category->id,
            'dob' => is_numeric($row['dob']) ? Date::excelToDateTimeObject($row['dob'])->format('Y-m-d') : $row['dob'],
            'address' => $row['address'],
            'phone' => (string) $row['phone'],
            'email' => $row['email'],
            'employee_number' => $row['employee_number'],
            // 'photo' => $photoPath,
            'password' => Hash::make('123456'),
            'user_id' => auth()->user()->id,
        ]);
    }

    // private function convertGoogleDriveToDirectLink($url)
    // {
    //     if (empty($url)) {
    //         return null;
    //     }

    //     if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
    //         $fileId = $matches[1];
    //     } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
    //         $fileId = $matches[1];
    //     } else {
    //         return $url;
    //     }

    //     return "https://drive.google.com/uc?export=download&id={$fileId}";
    // }

    // private function downloadAndSaveImage($url)
    // {
    //     if (empty($url)) {
    //         return null;
    //     }

    //     $imageContent = Http::get($url)->body();

    //     $imageName = 'employee_' . Str::random(10) . '.jpg';

    //     $path = Storage::disk('public')->put('photos/' . $imageName, $imageContent);

    //     return $path ? 'photos/' . $imageName : null;
    // }
}
