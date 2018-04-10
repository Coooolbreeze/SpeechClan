<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/9
 * Time: 13:20
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\ClubCollection;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ClubController extends ApiController
{
    public function index()
    {
        return new ClubCollection(
            Club::orderBy('sort')
                ->latest()
                ->paginate(Input::get('limit') ?: 10)
        );
    }

    public function store(Request $request)
    {
        Club::create($request->post());

        return $this->created();
    }

    public function show($id)
    {
        return $this->success(new ClubResource(Club::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        Club::where('id', $id)
            ->update($request->post());

        return $this->updated();
    }

    public function destroy($id)
    {
        Club::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}