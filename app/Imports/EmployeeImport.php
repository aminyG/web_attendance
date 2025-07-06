<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Importing row: ', $row);

        $category = Category::firstOrCreate(['name' => $row['category']]);
        Log::info('Resolved category id: ', ['id' => $category->id]);

        $employee = new Employee([
            'name' => $row['name'],
            'category_id' => $category->id, // ← Tambahkan baris ini!
            'dob' => is_numeric($row['dob']) ? Date::excelToDateTimeObject($row['dob'])->format('Y-m-d') : $row['dob'],
            'address' => $row['address'],
            'phone' => (string) $row['phone'],
            'email' => $row['email'],
            'employee_number' => $row['employee_number'],
            'photo' => $this->convertGoogleDriveToDirectLink($row['photo_url']),
        ]);

        Log::info('New employee model built: ', $employee->toArray());

        return $employee;
    }

    private function convertGoogleDriveToDirectLink($url)
    {
        if (empty($url)) {
            return null;
        }

        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
        } else {
            return $url;
        }

        return "https://drive.google.com/uc?export=download&id={$fileId}";
    }
}
