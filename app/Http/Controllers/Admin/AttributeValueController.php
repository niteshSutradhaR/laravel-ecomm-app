<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Contracts\AttributeContract;

class AttributeValueController extends Controller
{
    protected $attributeRepository;

    public function __construct(AttributeContract $attributeRepository)
    {
    	$this->attributeRepository= $attributeRepository;
    }

    public function getValues(Request $request)
    {
    	$attributeId = $request->get('id');
    	$attribute = $this->attributeRepository->findAttributeById($attributeId);
    	$values = $attribute->values;
    	return response()->json($values);
    }

    public function addValues(Request $request)
    {
    	$value = new AttributeValue();
    	$value->attribute_id = $request->get('id');
    	$value->value = $request->get('value');
    	$value->price = $request->get('price');
    	$value->save();

    	return response()->json($value);
    }

    public function updateValues(Request $request)
    {
    	$attributeValue=AttributeValue::findOrFail($request->get('valueId'));
    	$attributeValue->attribute_id=$request->get('id');
    	$attributeValue->value=$request->get('value');
    	$attributeValue->price=$request->get('price');
    	$attributeValue->save();

    	return response()->json($attributeValue);
    }

    public function deleteValues(Request $request)
    {
    	$attributeValue = AttributeValue::findOrFail($request->get('id'));  
    	$attributeValue->delete();
    	return response()->json(['status'=>'success','message'=>'Attribute value deleted successfully.']);
    }
}
