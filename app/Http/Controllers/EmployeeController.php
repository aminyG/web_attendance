<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Current Auth User:', ['user' => auth()->user() ? auth()->user()->toArray() : 'No user authenticated']);

        $query = Employee::with('category')
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

        $employees = $query->paginate(10);
        $categories = Category::where('user_id', auth()->id())->get();

        return view('employee.index', compact('employees', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();

        if ($categories->isEmpty()) {
            Log::warning("No categories found for user: " . auth()->id());
        }

        return view('employee.create_individual', compact('categories'));
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
        ]);
        Log::info('Validated:', $validated);

        $validated['password'] = Hash::make('123456');

        $category = Category::where('name', $validated['category'])
                            ->where('user_id', auth()->id())
                            ->first();

        if (!$category) {
            return back()->with('error', 'Kategori tidak ditemukan.');
        }

        unset($validated['category']);

        $employee = new Employee($validated);
        $employee->category_id = $category->id;
        $employee->user_id = auth()->user()->id;

        if ($employee->save()) {
            Log::info('Employee Saved:', $employee->toArray());
        } else {
            Log::error('Employee Save Failed');
        }

        $this->registerEmployeeInPresenceAPI($employee, $category);

        return redirect()->route('employee.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }
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
            Log::info('Importing file:', ['file' => $request->file('file')->getClientOriginalName()]);

            Excel::import(new EmployeeImport(auth()->id()), $request->file('file'));

            $today = now()->toDateString();
            $importedEmployees = Employee::where('user_id', auth()->id())
                ->whereDate('created_at', $today)
                ->get();

            foreach ($importedEmployees as $employee) {
                Log::info('Processing Employee:', [
                    'employee_name' => $employee->name,
                    'category_name' => $employee->category->name ?? 'N/A'
                ]);

                $category = Category::where('name', $employee->category->name)
                                    ->where('user_id', auth()->id())
                                    ->first();

                if (!$category) {
                    Log::error('Category not found for employee', [
                        'employee_name' => $employee->name, 
                        'category_name' => $employee->category->name
                    ]);
                    return back()->with('error', 'Kategori "' . $employee->category->name . '" tidak ditemukan. Pastikan kategori sudah terdaftar.');
                }

                $employee->category_id = $category->id;
                $employee->save();

                Log::info('Employee saved successfully:', ['employee_name' => $employee->name]);

                $this->registerEmployeeInPresenceAPI($employee, $category);
            }

            return redirect()->route('employee.index')->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Import failed:', ['message' => $e->getMessage()]);
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
    public function registerEmployeeInPresenceAPI($employee, $category)
    {
        try {
            $response = Http::post('http://presence.guestallow.com/api/auth/register', [
                'name' => $employee->name,
                'email' => $employee->email,
                'password' => '123456',
                'password_confirmation' => '123456',
                'category_user_id' => $category->server_id,
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

    // public function destroy($id)
    // {
    //     $employee = Employee::findOrFail($id);

    //     $employee->delete();

    //     return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil dihapus.');
    // }
}
