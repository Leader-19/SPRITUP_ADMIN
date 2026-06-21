<?php

namespace App\Repositories\Categories;

use App\Interfaces\Categories\CategoriesInterface;
use App\Models\Category;

class CategoriesRepository implements CategoriesInterface
{
    public function getAll()
    {
        return Category::all();
    }

    public function getPaginated($perPage = 10)
    {
        return Category::withCount('documents')->paginate($perPage);
    }

    public function store($data)
    {
        return Category::create($data);
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function update($id, $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        return Category::destroy($id);
    }
}