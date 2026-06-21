<?php

namespace App\Repositories\Documents;

use App\Interfaces\Documents\DocumentsInterface;
use App\Models\Document;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentsRepository implements DocumentsInterface
{
    public function getAll()
    {
        return Document::with('category')->get();
    }

    public function getPaginated($perPage = 10, $page = 1, $search = '', $categoryId = null)
    {
        $query = Document::with('category')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('doc_name', 'like', "%{$search}%")
                  ->orWhere('doc_title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function store($data)
    {
        return Document::create($data);
    }

    public function find($id)
    {
        return Document::with('category')->findOrFail($id);
    }

    public function update($id, $data)
    {
        $document = Document::findOrFail($id);
        $document->update($data);
        return $document;
    }

    public function delete($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return $document;
    }
}