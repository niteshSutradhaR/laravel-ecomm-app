<?php

namespace App\Contracts;

interface ProductContract
{
	public function listProducts(string $order="id", string $sort="desc", array $columns=['*']);

	public function findProductById(int $id);

	public function createProduct(array $param);

	public function updateProduct(array $param);

	public function deleteProduct($id);
}