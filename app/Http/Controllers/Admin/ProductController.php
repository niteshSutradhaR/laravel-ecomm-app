<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Contracts\ProductContract;
use App\Contracts\BrandContract;
use App\Contracts\CategoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\StoreProductFormRequest;

class ProductController extends BaseController
{
    protected $productRepository;
    protected $brandRepository;
    protected $categoryRepository;

    public function __construct(
    	ProductContract $productRepository,
    	BrandContract $brandRepository,
    	CategoryContract $categoryRepository
    )
    {
    	$this->productRepository = $productRepository;
    	$this->brandRepository = $brandRepository;
    	$this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
    	$products= $this->productRepository->listProducts();
    	$this->setPageTitle('Products', 'Products List');
    	return view('admin.products.index',compact('products'));
    }

    public function create()
    {
    	 $brands = $this->brandRepository->listBrands('name','asc');
    	 $categories = $this->categoryRepository->listCategories('name','asc');

    	 $this->setPageTitle('Products','Create Product');
    	 return view('admin.products.create',compact('brands','categories'));
    }

    public function store(StoreProductFormRequest $request)
    {
    	$params = $request->except('_token');
    	$params['slug'] = Str::slug($params['name']);
    	$product = $this->productRepository->createProduct($params);

    	if (!$product) 
    	{
    		return $this->responseRedirectBack('Error occured while creating product','error',true,true);
    	}

    	return $this->responseRedirect('admin.products.index','Product added Successfully','success',false,false);
    }

    public function edit($id)
    {
    	$product = $this->productRepository->findProductById($id);
    	$brands = $this->brandRepository->listBrands('name','asc');
    	$categories = $this->categoryRepository->listCategories('name','asc');
    	$this->setPageTitle('Products','Edit Products');
    	return view('admin.products.edit',compact('product','brands','categories'));
    }

    public function update(StoreProductFormRequest $request)
    {
    	$params = $request->except('_token');
    	$params['slug'] = Str::slug($params['name']);
    	$product = $this->productRepository->updateProduct($params);
    	if (!$product) 
    	{
    		return $this->responseRedirectBack('Error occured while updating product','error',true,true);
    	}
    	return $this->responseRedirect('admin.products.index','Product updated successfully',false,false);
    }
}
