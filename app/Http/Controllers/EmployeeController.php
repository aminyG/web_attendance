<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    // public function index()
    // {
    //     // $employees = Employee::all();
    //     $employees = Employee::with('category')->get();
    //     return view('employee.index', compact('employees'));
    // }

    public function index(Request $request)
    {
        $query = \App\Models\Employee::with('category');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->category . '%');
            });
        }

        $employees = $query->get();

        return view('employee.index', compact('employees'));
    }


    public function create()
    {
        return view('employee.create_individual');
    }
    public function storeIndividual(Request $request)
    {
        Log::info('Request Data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'dob' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'employee_number' => 'required|string|unique:employees,employee_number',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        Log::info('Validated:', $validated);
        // Buat category baru kalau belum ada
        $category = Category::firstOrCreate(['name' => $validated['category']]);

        unset($validated['category']); // jangan disimpan sebagai kolom string
        Log::info('Category:', $category->toArray());

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
            Log::info('Photo path:', ['path' => $validated['photo']]);
        }

        // Simpan employee
        $employee = new Employee($validated);
        $employee->category_id = $category->id;
        // $employee->save();
        if ($employee->save()) {
            Log::info('Employee Saved:', $employee->toArray());
        } else {
            Log::error('Employee Save Failed');
        }
        return redirect()->route('employee.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }
    public function createMass()
    {
        return view('employee.create_mass');
    }

    //     public function storeMass(Request $request)
//     {
//         $request->validate([
//             'file' => 'required|file|mimes:xlsx,csv|max:10240',
//         ]);

    //         try {
//             Excel::import(new EmployeeImport, $request->file('file'));
//             return redirect()->route('employee.index')->with('success', 'Data berhasil diimport.');
//         } catch (\Exception $e) {
//             return redirect()->back()->withErrors(['file' => 'Gagal mengimport data: ' . $e->getMessage()]);
//         }
//     }
//     public function destroy($id)
// {
//     $employee = Employee::findOrFail($id);
//     $employee->delete();

    //     return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil dihapus.');
// }
    public function storeMass(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv|max:10240',
        ]);

        try {
            Excel::import(new EmployeeImport, $request->file('file'));
            return redirect()->route('employee.index')->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            // Tangkap error dan tampilkan untuk debug
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'employee_number' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $employee = Employee::findOrFail($id);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        }

        $category = Category::firstOrCreate(['name' => $request->category]);
        $validated['category_id'] = $category->id;
        unset($validated['category']); // jangan simpan 'category' string

        $employee->update($validated);

        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

}
