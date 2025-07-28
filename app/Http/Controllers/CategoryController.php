<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('user_id', auth()->id())->get();

        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $data = $request->only(['name', 'description']);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token')
            ])->post('http://presence.guestallow.com/api/category-users', $data);

            Log::info('API Response for Store Category:', $response->json());

            if ($response->status() == 201) {
                $categoryData = $response->json()['data'];
                Category::create([
                    'name' => $categoryData['name'],
                    'user_id' => auth()->id(),
                    'server_id' => $categoryData['id']
                ]);

                Log::info('Category user created successfully:', $response->json());
                return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
            } else {
                Log::error('Failed to create category user:', $response->json());
                return redirect()->route('categories.index')->with('error', 'Gagal menambah kategori.');
            }
        } catch (\Exception $e) {
            Log::error('Error creating category user in Presence API:', ['message' => $e->getMessage()]);
            return redirect()->route('categories.index')->with('error', 'Terjadi kesalahan.');
        }
    }

    public function showAttendanceSettings()
    {
        $user = auth()->user();

        if (!$user) {
            Log::error('User not authenticated.');
            return redirect()->route('login')->with('error', 'User not authenticated');
        }

        Log::info('User accessing attendance settings:', ['user' => $user->toArray()]);

        $categories = Category::where('user_id', $user->id)->get();

        Log::info('Categories found for user:', ['categories' => $categories->toArray()]);

        return view('category.attendance_settings', compact('categories'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $category = Category::findOrFail($id);
            
            $serverId = $category->server_id;

            $data = $request->only(['name', 'description']);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token')
            ])->put("http://presence.guestallow.com/api/category-users/{$serverId}", $data);

            Log::info('API Response for Update Category:', $response->json());

            if ($response->status() == 200) {
                $category->update([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                Log::info('Category user updated successfully:', $response->json());
                return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
            } else {
                Log::error('Failed to update category user:', $response->json());
                return redirect()->route('categories.index')->with('error', 'Gagal memperbarui kategori.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating category user in Presence API:', ['message' => $e->getMessage()]);
            return redirect()->route('categories.index')->with('error', 'Terjadi kesalahan.');
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $serverId = $category->server_id;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token')
            ])->delete("http://presence.guestallow.com/api/category-users/{$serverId}");

            Log::info('API Response for Delete Category:', $response->json());

            if ($response->status() == 200) {
                $category->delete();

                Log::info('Category user deleted successfully:', ['id' => $id]);
                return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
            } else {
                Log::error('Failed to delete category user:', $response->json());
                return redirect()->route('categories.index')->with('error', 'Gagal menghapus kategori.');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting category user in Presence API:', ['message' => $e->getMessage()]);
            return redirect()->route('categories.index')->with('error', 'Terjadi kesalahan.');
        }
    }

}
