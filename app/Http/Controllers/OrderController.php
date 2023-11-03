<?php

namespace App\Http\Controllers;

use App\Http\Requests\order\SubmitRequest;
use App\Services\OrderService;
use Exception;


class OrderController extends Controller
{

    public function submitData(OrderService $orderService, SubmitRequest $request)
    {
        //判断是否被限流

        try{
            //生成订单
            return $orderService->submitOrder($request->all());
        }catch (Exception $ex) {
            //TODO 记录异常和入参
//            return $this->error(['msg' => '系统出小差了，请稍后再试', 'data' => []]);
            return $this->error(['msg' => $ex->getMessage(), 'data' => []]);
        }
    }
}
