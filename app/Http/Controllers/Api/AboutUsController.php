<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/10
 * Time: 16:44
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\AboutUsResource;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends ApiController
{
    public function show($id)
    {
        return $this->success(new AboutUsResource(AboutUs::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        AboutUs::where('id', $id)
            ->update($request->post());

        return $this->updated();
    }
}