<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/10
 * Time: 12:42
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CourseController extends ApiController
{
    public function index()
    {
        return new CourseCollection(
            Course::orderBy('sort')
                ->latest()
                ->paginate(Input::get('limit') ?: 10)
        );
    }

    public function store(Request $request)
    {
        Course::create($request->post());

        return $this->created();
    }

    public function show($id)
    {
        return $this->success(new CourseResource(Course::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        Course::where('id', $id)
            ->update($request->post());

        return $this->updated();
    }

    public function destroy($id)
    {
        Course::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}