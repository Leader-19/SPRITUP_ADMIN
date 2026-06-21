<?php

namespace App\Http\Controllers;

use App\Services\Categories\CategoriesService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoriesService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $paginated = $this->service->getPaginated($perPage);

        return Inertia::render("Category/CategoryIndex", [
            'categories' => $paginated->items(),
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render("Category/CategoryCreate");
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $this->service->store($request);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function show(string $id)
    {
        return Inertia::render("Category/CategoryDetails", [
            "category" => $this->service->find($id),
        ]);
    }

    public function edit(string $id)
    {
        return Inertia::render("Category/CategoryUpdate", [
            "category" => $this->service->find($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $this->service->update($request, $id);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
