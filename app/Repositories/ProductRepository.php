<?php
namespace App\Repositories;

use App\Models\Product;
use App\Contracts\ProductContract;
use App\Traits\Uploadable;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\QueryException;
use Illuminate\Database\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class ProductRepository extends BaseRepository implements ProductContract
{
	use Uploadable;

	public function __construct(Product $model)
	{
		parent::__construct($model);
		$this->model = $model;
	}

	public function listProducts(string $order ="id", string $sort="desc", array $columns=['*'] )
	{
		return $this->all($columns,$order,$sort);
	}

	public function findProductById($id)
	{
		try
		{
			return $this->findOneOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			throw new ModelNotFoundException($e);
		}
	}

	public function createProduct(array $params)
	{
		try
		{
			$collection=collect($params);

			$featured=$collection->has('featured')?1:0;
			$status=$collection->has('status')?1:0;
			$merge=$collection->merge(compact('featured','status'));
			
			$product=new Product($merge->all());
			$product->save();

			if ($collection->has('categories')) 
			{
				$product->categories()->sync($params['categories']);
			}

			return $product;
		}
		catch(QueryException $e)
		{
			throw new InvalidArgumentException($e->getMessage());
		}
	}

	public function updateProduct(array $params)
	{
		$product = $this->findProductById($params['product_id']);

		$collection = collect($params)->except('_token');

		$featured = $collection->has('featured')?1:0;
		$status = $collection->has('status')?1:0;

		$merge = $collection->merge(compact('featured','status'));
		$product->update($merge->all());

		if ($collection->has('categories')) 
		{
			$product->categories()->sync($params['categories']);
		}

		return $product;
	}

	public function deleteProduct($id)
	{
		$product = $this->findProductById($id);
		$product->delete();
		return $product;
	}

	public function findProductBySlug($slug)
	{
		$product = Product::where('slug',$slug)->first();

		return $product;
	}
}