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
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BannerController extends ApiController
{
    public function index(Request $request)
    {
        $type = $request->get('type') ?: 1;
        $res = Banner::where(function ($query) use ($type) {
            $type && $query->where('type', $type);
        })->where(function ($query) {
            !$this->isAdmin() && $query->where('publish', 1);
        })
            ->orderBy('publish', 'desc')
            ->orderBy('sort')
            ->latest()
            ->paginate(Input::get('limit') ?: 10);

        return new BannerCollection($res);
    }

    public function store(Request $request)
    {
        Banner::create([
            'type' => $request->post('type'),
            'image_id' => $request->post('image_id')
        ]);

        return $this->created();
    }

    public function update(Request $request, $id)
    {
        Banner::where('id', $id)->update($request->post());

        return $this->updated();
    }

    public function destroy($id)
    {
        Banner::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}