<?php

namespace App\Http\Controllers\Admin;

use App\Traits\Uploadable;
use App\Models\ProductImage;
use App\Contracts\ProductContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    use Uploadable;

    protected $productImageRepository;

    public function __construct(ProductContract $productRepository)
    {
    	$this->productRepository = $productRepository;
    }

    public function upload(Request $request)
    {
    	$product = $this->productRepository->findProductById($request->get('product_id'));

    	if ($request->has('image')) {
    		$image = $this->uploadOne($request->image,'products');
    		$productImage = new ProductImage([
    			'full'=>$image,
    		]);

    		$product->images()->save($productImage);
    	}

    	return response()->json(['status'=>'Success']);
    }

    public function delete($id)
    {
    	$image = ProductImage::findOrFail($id);

    	if ($image->full!="") {
    		$this->deleteOne($image->full);
    	}
    	$image->delete();

    	return redirect()->back();
    }
}
