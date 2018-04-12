<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/9
 * Time: 16:46
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class StudentController extends ApiController
{
    public function index()
    {
        return new StudentCollection(
            Student::orderBy('sort')
                ->latest()
                ->paginate(Input::get('limit') ?: 10)
        );
    }

    public function show($id)
    {
        return $this->success(new StudentResource(Student::findOrFail($id)));
    }

    public function store(Request $request)
    {
        Student::create($request->post());

        return $this->created();
    }

    public function update(Request $request, $id)
    {
        Student::where('id', $id)
            ->update($request->post());

        return $this->updated();
    }

    public function destroy($id)
    {
        Student::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}