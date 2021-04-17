<?php

namespace App\Contracts;


interface AttributeContract
{
	public function listAttributes(array $columns=['*'], string $orderBy='id', string $sortBy='desc');

	public function findAttributeById(int $id);

	public function createAttribute(array $params);

	public function updateAttribute(array $params);

	public function deleteAttribute(int $id);
}