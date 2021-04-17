<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';

    protected $fillable =[
    	'quantity','price','product_id','attribute_id','value'
    ];

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValues()
    {
    	return $this->belongstoMany(AttributeValue::class);
    }
}
