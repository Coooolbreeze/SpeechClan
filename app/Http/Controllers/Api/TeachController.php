<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/10
 * Time: 10:57
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\TeachCollection;
use App\Models\Teach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TeachController extends ApiController
{
    public function index()
    {
        return new TeachCollection(
            Teach::orderBy('sort')
                ->latest()
                ->paginate(Input::get('limit') ?: 10)
        );
    }

    public function store(Request $request)
    {
        Teach::create($request->post());

        return $this->created();
    }

    public function update(Request $request, $id)
    {
        Teach::where('id', $id)
            ->update($request->post());

        return $this->updated();
    }

    public function destroy($id)
    {
        Teach::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}