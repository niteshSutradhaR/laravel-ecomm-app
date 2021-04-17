<?php

namespace App\Repositories;

use App\Models\Category;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use App\Contracts\CategoryContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\BaseRepository;
use Doctrine\Instantiator\Exception\IvalidArgumentExecption;

class CategoryRepository extends BaseRepository implements CategoryContract
{
 	use UploadAble;

 	public function __construct(Category $model)
 	{
 		parent::__construct($model);
 		$this->model=$model;
 	}

 	public function listCategories(string $orderBy='id',string $sortBy='desc',array $columns=['*'])
 	{
 		return $this->all($columns,$orderBy,$sortBy);
 	}

 	public function findCategoryById($id)
 	{
 		try
 		{
 			return $this->findOneOrFail($id);
 		}
 		catch(ModelNotFoundException $e)
 		{
 			throw new ModelNotFopundException($e);
 		}
 	}

 	public function createCategory(array $params)
 	{
 		try
 		{
 			$collection=collect($params);
 			$image=null;
 			if($collection->has('image') && ($params['image'] instanceof UploadedFile))
 			{
 				$image=$this->uploadOne($params['image'],'categories');
 			}

 			$featured=$collection->has('featured')?1:0;
 			$menu=$collection->has('menu')?1:0;
 			$merge=$collection->merge(compact('menu','image','featured'));
 			$category=new Category($merge->all());
 			$category->save();
 			return $category;
 		}
 		catch(QueryException $e)
 		{
 			throw new InvalidArgumentException($e);
 		}
 	}

 	public function updateCategory(array $params)
 	{
 		$category=$this->findCategoryById($params['id']);
 		$collection=collect($params)->except('_token');
 		$image=$collection->has('image')? $collection->get('image'):"";
 		if($collection->has('image') && ($params['image'] instanceof UploadedFile))
 		{
 			if($category->image!=null)
 			{
 				$this->deleteOne($category->image);
 			}

 			$image=$this->uploadOne($params['image'],'categories');
 		}
 		else
 		{
 			$image=$category->image;
 		}

 		$featured=$collection->has('featured')?1:0;

 		$menu=$collection->has('menu')?1:0;
 		$merge=$collection->merge(compact('featured','image','menu'));
 		$category->update($merge->all());
 		return $category;
 	}

 	public function deleteCategory($id)
 	{
 		$category->findCategoryById($id);

 		if($category->image!=null)
 		{
 			$this->deleteOne($category->image);
 		}

 		$category->delete();
 		return $category;
 	}

 	public function treeList()
 	{
 		return Category::orderByRaw('-name ASC')
 			->get()
 			->nest()
 			->listsFlattened('name');
 	}

	public function findBySlug($slug)
	{
	    return Category::with('products')
	        ->where('slug', $slug)
	        ->where('menu', 1)
	        ->first();
	}
}