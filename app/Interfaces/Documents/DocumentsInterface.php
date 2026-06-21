<?php

namespace App\Interfaces\Documents;

interface DocumentsInterface
{
    public function getAll();
    public function getPaginated($perPage = 10, $page = 1, $search = '');
    public function store($data);
    public function find($id);
    public function update($id, $data);
    public function delete($id);
}