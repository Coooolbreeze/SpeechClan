<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/9
 * Time: 16:46
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\StudentCollection;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class StudentController extends ApiController
{
    public function index()
    {
        return new StudentCollection(Student::paginate(Input::get('limit') ?: 10));
    }

    public function store(Request $request)
    {
        Student::create($request->post());

        return $this->created();
    }

    public function destroy($id)
    {
        Student::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}