<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/3
 * Time: 15:40
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }
}