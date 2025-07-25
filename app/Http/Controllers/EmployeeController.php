<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;


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
        // Log::info('Current Auth User:', auth()->user() ? auth()->user()->toArray() : 'No user authenticated');
        Log::info('Current Auth User:', ['user' => auth()->user() ? auth()->user()->toArray() : 'No user authenticated']);

        $query = \App\Models\Employee::with('category')
        ->whereHas('user', function ($q) {
            $q->where('id', auth()->user()->id);
        });
        
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
    // public function storeIndividual(Request $request)
    // {
    //     Log::info('Request Data:', $request->all());

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'category' => 'required|string|max:255',
    //         'dob' => 'required|date',
    //         'address' => 'required|string',
    //         'phone' => 'required|string',
    //         'email' => 'required|email|unique:employees,email',
    //         'employee_number' => 'required|string|unique:employees,employee_number',
    //         // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    //     Log::info('Validated:', $validated);

    //     // Admin sets the default password
    //     $validated['password'] = Hash::make('123456');  // Default password (hashed)

    //     // Buat category baru kalau belum ada
    //     $category = Category::firstOrCreate(['name' => $validated['category']]);
    //     unset($validated['category']); // jangan disimpan sebagai kolom string
        
    //     Log::info('Category:', $category->toArray());

    //     // if ($request->hasFile('photo')) {
    //     //     $validated['photo'] = $request->file('photo')->store('photos', 'public');
    //     //     Log::info('Photo path:', ['path' => $validated['photo']]);
    //     // }

    //     // Simpan employee
    //     $employee = new Employee($validated);
    //     $employee->category_id = $category->id;
    //     // $employee->save();
    //     if ($employee->save()) {
    //         Log::info('Employee Saved:', $employee->toArray());
    //     } else {
    //         Log::error('Employee Save Failed');
    //     }

    //     // Now, register the employee in Presence API
    //     $this->registerEmployeeInPresenceAPI($employee);
        
    //     // Register face for the employee in Presence API
    //     // $this->registerFaceForEmployee($employee);
        
    //     return redirect()->route('employee.index')->with('success', 'Karyawan berhasil ditambahkan.');
    // }
    
    public function createMass()
    {
        return view('employee.create_mass');
    }
    public function storeMass(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv|max:10240',
    ]);

    try {
        // Mengimpor data menggunakan Laravel Excel
        Excel::import(new EmployeeImport, $request->file('file'));

        // Menambahkan user_id untuk setiap employee setelah disimpan dan mendaftarkan employee dan kategori di API Presence
        foreach (Employee::all() as $employee) {
            $employee->user_id = auth()->user()->id;
            $employee->save();

            // Daftarkan karyawan di Presence API
            $this->registerEmployeeInPresenceAPI($employee);

            // Jika kategori baru dibuat, registrasi kategori di API Presence
            $category = Category::find($employee->category_id);
            if ($category && $category->wasRecentlyCreated) {
                $this->registerCategoryInPresenceAPI($category);
            }
        }

        return redirect()->route('employee.index')->with('success', 'Data berhasil diimpor.');
    } catch (\Exception $e) {
        // Tangkap error dan tampilkan pesan kesalahan
        return back()->with('error', 'Import gagal: ' . $e->getMessage());
    }
}

    
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'category' => 'required|string|max:255',
    //         'dob' => 'nullable|date',
    //         'address' => 'nullable|string',
    //         'phone' => 'nullable|string',
    //         'email' => 'nullable|email',
    //         'employee_number' => 'nullable|string',
    //         // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $employee = Employee::findOrFail($id);

    //     // if ($request->hasFile('photo')) {
    //     //     $path = $request->file('photo')->store('photos', 'public');
    //     //     $validated['photo'] = $path;
    //     // }

    //     $category = Category::firstOrCreate(['name' => $request->category]);
    //     $validated['category_id'] = $category->id;
    //     unset($validated['category']); // jangan simpan 'category' string

    //     $employee->update($validated);

    //     return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diperbarui.');
    // }
   public function storeIndividual(Request $request)
{
    Log::info('Request Data:', $request->all());

    // Validasi input dari form
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'dob' => 'required|date',
        'address' => 'required|string',
        'phone' => 'required|string',
        'email' => 'required|email|unique:employees,email',
        'employee_number' => 'required|string|unique:employees,employee_number',
    ]);
    Log::info('Validated:', $validated);

    // Admin sets the default password
    $validated['password'] = Hash::make('123456');  // Default password (hashed)

    // Buat kategori baru atau ambil yang sudah ada
    $category = Category::firstOrCreate(['name' => $validated['category']]);
    unset($validated['category']); // Jangan disimpan sebagai kolom string
    
    Log::info('Category:', $category->toArray());

    // Simpan data karyawan
    $employee = new Employee($validated);
    $employee->category_id = $category->id;
    $employee->user_id = auth()->user()->id;

    if ($employee->save()) {
        Log::info('Employee Saved:', $employee->toArray());
    } else {
        Log::error('Employee Save Failed');
    }

    // Sekarang daftarkan employee ke API Presence
    $this->registerEmployeeInPresenceAPI($employee);

    // Jika kategori baru dibuat, registrasi kategori di API Presence
    if ($category->wasRecentlyCreated) {
        $this->registerCategoryInPresenceAPI($category);
    }

    return redirect()->route('employee.index')->with('success', 'Karyawan berhasil ditambahkan.');
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
    ]);

    $employee = Employee::findOrFail($id);

    // Ambil atau buat kategori baru
    $category = Category::firstOrCreate(['name' => $request->category]);
    $validated['category_id'] = $category->id;
    unset($validated['category']); // Jangan simpan 'category' sebagai kolom string

    $employee->update($validated);

    // Jika kategori baru dibuat, registrasi kategori ke API Presence
    if ($category->wasRecentlyCreated) {
        $this->registerCategoryInPresenceAPI($category);
    }

    return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diperbarui.');
}


    public function destroy($id)
{
    // Mencari employee berdasarkan ID
    $employee = Employee::findOrFail($id);

    // if ($employee->photo) {
    //     Storage::disk('public')->delete($employee->photo);
    // }

    // Menghapus data karyawan
    $employee->delete();

    // Redirect kembali dengan pesan sukses
    return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil dihapus.');
}

    public function registerEmployeeInPresenceAPI($employee)
    {
        try {
            $response = Http::post('http://presence.guestallow.com/api/auth/register', [
                'name' => $employee->name,
                'email' => $employee->email,
                'password' => '123456',
                'password_confirmation' => '123456',
                'category_user_id' => $employee->category_id,
            ]);

            if ($response->successful()) {
                Log::info('Employee Registered in Presence API:', $response->json());
            } else {
                Log::error('Failed to Register Employee in Presence API:', $response->json());
            }
        } catch (\Exception $e) {
            Log::error('Error registering employee in Presence API:', ['message' => $e->getMessage()]);
        }
    }

    public function registerCategoryInPresenceAPI($category)
{
    try {
        // Pastikan ID kategori valid dan sudah ada di server
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('api_token')
        ])->post('http://presence.guestallow.com/api/category-users', [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description ?? null,
        ]);

        Log::info('API Response:', $response->json());

        if ($response->successful()) {
            Log::info('Category Registered in Presence API:', $response->json());
        } else {
            Log::error('Failed to Register Category in Presence API:', $response->json());
        }
    } catch (\Exception $e) {
        Log::error('Error registering category in Presence API:', ['message' => $e->getMessage()]);
    }
    // try {
    //     $response = Http::post('http://presence.guestallow.com/api/category-users', [
    //         'id' => $category->id,
    //         'name' => $category->name,
    //         'description' => $category->description ?? null,
    //     ]);
    //     Log::info('API Response:', $response->json());
    //     if ($response->successful()) {
    //         Log::info('Category Registered in Presence API:', $response->json());
    //     } else {
    //         Log::error('Failed to Register Category in Presence API:', $response->json());
    //     }
    // } catch (\Exception $e) {
    //     Log::error('Error registering category in Presence API:', ['message' => $e->getMessage()]);
    // }
}
}