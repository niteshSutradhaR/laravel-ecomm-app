<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Traits\UploadAble;
use App\Models\Setting;

class SettingController extends BaseController
{
	use UploadAble;
	
    public function index()
    {
    	$this->setPageTitle('Settings','Manage Settings');
    	return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        if ($request->has('site_logo') && ($request->file('site_logo') instanceof UploadedFile)) 
        {
            if(Setting::get('site_logo')!=null)
            {
                $this->deleteOne(Setting::get('site_logo'));
            }
            $logo=$this->uploadOne($request->file('site_logo'),'img');
            Setting::set('site_logo',$logo);
        }
        elseif($request->has('site_favicon') && ($request->file('site_favicon') instanceof UploadedFile))
        {
            if ($request->file('site_favicon')!=null) 
            {
                $this->deleteOne(Setting::get('site_favicon'));
            }
            $favicon=$this->uploadOne($request->file('site_favicon'),'img');
            Setting::set('site_favicon',$favicon);
        }
        else
        {
            $keys=$request->except('_token');
            foreach ($keys as $key => $value) 
            {
                Setting::set($key,$value);
            }
        }
        return $this->responseRedirectBack('Settings Updated successfully','success');
    }
}
