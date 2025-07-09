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
        $photoUrl = $this->convertGoogleDriveToDirectLink($row['photo_url']);
        $photoPath = $this->downloadAndSaveImage($photoUrl);

        // Menyimpan data karyawan ke database
        return new Employee([
            'name' => $row['name'],
            'category_id' => $category->id,
            'dob' => is_numeric($row['dob']) ? Date::excelToDateTimeObject($row['dob'])->format('Y-m-d') : $row['dob'],
            'address' => $row['address'],
            'phone' => (string) $row['phone'],
            'email' => $row['email'],
            'employee_number' => $row['employee_number'],
            'photo' => $photoPath,
            'password' => Hash::make('123456')
        ]);
    }

    // Mengubah URL Google Drive menjadi link download langsung
    private function convertGoogleDriveToDirectLink($url)
    {
        if (empty($url)) {
            return null;
        }

        // Mengambil ID dari URL Google Drive dan mengubahnya menjadi link download
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
        } else {
            return $url;
        }

        return "https://drive.google.com/uc?export=download&id={$fileId}";
    }

    // Mendownload foto dan menyimpannya ke server Laravel
    private function downloadAndSaveImage($url)
    {
        if (empty($url)) {
            return null;
        }

        // Mendownload konten gambar dari URL
        $imageContent = Http::get($url)->body();

        // Membuat nama file foto yang unik
        $imageName = 'employee_' . Str::random(10) . '.jpg';

        // Menyimpan gambar ke folder public/storage/photos di server
        $path = Storage::disk('public')->put('photos/' . $imageName, $imageContent);

        // Mengembalikan path gambar untuk disimpan di database
        return $path ? 'photos/' . $imageName : null;
    }
}
