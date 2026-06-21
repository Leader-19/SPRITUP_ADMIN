<?php

namespace App\Http\Controllers;

use App\Http\Requests\Doc\DocumentRequest;
use App\Models\Category;
use App\Services\Documents\DocumentsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends Controller
{
    protected $service;

    public function __construct(DocumentsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $categoryId = $request->get('category_id');

        $paginated = $this->service->getPaginated($perPage, $page, $search, $categoryId);

        return Inertia::render('Documents/DocumentIndex', [
            'documents' => $paginated->items(),
            'categories' => Category::all(),
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
        return Inertia::render('Documents/DocumentCreate', [
            'categories' => Category::all(),
        ]);
    }

    public function store(DocumentRequest $request)
    {
        $this->service->store($request);

        return redirect()->route('documents.index')
            ->with('success', 'Document created successfully!');
    }

    public function show(string $id)
    {
        return Inertia::render('Documents/DocumentDetails', [
            'document' => $this->service->find($id),
        ]);
    }

    public function edit(string $id)
    {
        return Inertia::render('Documents/DocumentUpdate', [
            'document' => $this->service->find($id),
            'categories' => Category::all(),
        ]);
    }

    public function update(DocumentRequest $request, string $id)
    {
        $this->service->update($request, $id);

        return redirect()->route('documents.index')
            ->with('success', 'Document updated successfully!');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }
}
