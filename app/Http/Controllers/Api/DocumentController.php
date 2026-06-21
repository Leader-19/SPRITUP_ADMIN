<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $categoryId = $request->get('category_id');

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

        $documents = $query->paginate($perPage, ['*'], 'page', $page);

        $categories = Category::all();

        if ($request->header('X-Inertia')) {
            return Inertia::render('Documents/DocumentIndex', [
                'documents' => $documents,
                'categories' => $categories,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'documents' => $documents->items(),
            'categories' => $categories,
            'pagination' => [
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
                'per_page' => $documents->perPage(),
                'total' => $documents->total(),
            ]
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Get document content for viewing
     */
    public function getContent(string $id)
    {
        $document = Document::with('category')->findOrFail($id);
        
        $path = storage_path('app/public/' . $document->doc_upload);
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        $content = '';
        try {
            if (file_exists($path)) {
                if ($ext === 'docx') {
                    $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
                    $writer = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
                    $tempHtml = tempnam(sys_get_temp_dir(), 'docx') . '.html';
                    $writer->save($tempHtml, 'HTML');
                    $content = file_get_contents($tempHtml);
                    unlink($tempHtml);
                } elseif ($ext === 'pdf') {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($path);
                    $content = $pdf->getText();
                } elseif ($ext === 'xlsx') {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                    foreach ($spreadsheet->getAllSheets() as $sheet) {
                        foreach ($sheet->getRowIterator() as $row) {
                            $cells = [];
                            foreach ($row->getCellIterator() as $cell) {
                                $cells[] = $cell->getValue();
                            }
                            $content .= implode(' ', $cells) . "\n";
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $content = 'Could not extract file content: ' . $e->getMessage();
        }
        
        return response()->json([
            'document' => $document,
            'content' => $content
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
