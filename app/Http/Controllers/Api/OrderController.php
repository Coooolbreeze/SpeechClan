<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/10
 * Time: 13:17
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\StoreOrder;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class OrderController extends ApiController
{
    public function index()
    {
        return new OrderCollection(
            Order::latest()
                ->paginate(Input::get('limit') ?: 10)
        );
    }

    public function store(StoreOrder $request)
    {
        Order::create($request->post());

        return $this->created();
    }

    public function update(Request $request, $id)
    {
        Order::where('id', $id)
            ->update($request->post());

        return $this->updated();
    }

    public function destroy($id)
    {
        Order::where('id', $id)
            ->delete();

        return $this->deleted();
    }
}