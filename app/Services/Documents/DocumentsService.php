<?php

namespace App\Services\Documents;

use App\Interfaces\Documents\DocumentsInterface;
use Illuminate\Support\Facades\Storage;

class DocumentsService
{
    protected $repo;

    public function __construct(DocumentsInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function getPaginated($perPage = 10, $page = 1, $search = '', $categoryId = null)
    {
        return $this->repo->getPaginated($perPage, $page, $search, $categoryId);
    }

    public function store($request)
    {
        $path = null;

        if ($request->hasFile('doc_upload')) {
            $file = $request->file('doc_upload');

            // keep original name but make it unique
            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('documents', $filename, 'public');
        }

        return $this->repo->store([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'doc_name' => $request->doc_name,
            'doc_title' => $request->doc_title,
            'doc_upload' => $path,
            'description' => $request->description,
        ]);
    }
    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update($request, $id)
    {
        $document = $this->repo->find($id);

        $data = [
            'doc_name' => $request->doc_name,
            'doc_title' => $request->doc_title,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('doc_upload')) {

            // delete old file
            if ($document->doc_upload) {
                \Storage::disk('public')->delete($document->doc_upload);
            }

            $file = $request->file('doc_upload');

            // keep original name + timestamp
            $filename = time() . '_' . $file->getClientOriginalName();

            $data['doc_upload'] = $file->storeAs('documents', $filename, 'public');
        }

        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        $document = $this->repo->find($id);

        if ($document->doc_upload) {
            Storage::disk('public')->delete($document->doc_upload);
        }

        return $this->repo->delete($id);
    }
}
