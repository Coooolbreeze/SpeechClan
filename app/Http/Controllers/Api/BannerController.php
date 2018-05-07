<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/7
 * Time: 12:40
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\BannerCollection;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BannerController extends ApiController
{
    public function index()
    {
        return new BannerCollection(
            Banner::orderBy('status')
                ->latest()
                ->paginate(Input::get('limit') ?: 10)
        );
    }

    public function store(Request $request)
    {
        Banner::create([
            'image_id' => $request->post('image_id')
        ]);

        return $this->created();
    }

    public function destroy($id)
    {
        Banner::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}