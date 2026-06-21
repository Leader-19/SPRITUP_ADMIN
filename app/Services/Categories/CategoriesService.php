<?php

namespace App\Services\Categories;

use App\Interfaces\Categories\CategoriesInterface;
use Illuminate\Support\Facades\Auth;

class CategoriesService
{
    protected $repo;

    public function __construct(CategoriesInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function getPaginated($perPage = 10)
    {
        return $this->repo->getPaginated($perPage);
    }

    public function store($request)
    {
        return $this->repo->store([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update($request, $id)
    {
        return $this->repo->update($id, [
            'title' => $request->title,
            'description' => $request->description,
        ]);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}