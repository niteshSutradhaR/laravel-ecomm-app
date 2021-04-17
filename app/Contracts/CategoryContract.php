<?php

namespace App\Contracts;

use App\Contracts\BaseContract;

interface CategoryContract
{
	public function listCategories(string $orderBy='id',string $sortBy='desc',array $columns=['*']);

	public function findCategoryById(int $id);

	public function createCategory(array $params);

	public function updateCategory(array $params);

	public function deleteCategory(int $id);

	public function treeList();

	public function findBySlug($slug);
}