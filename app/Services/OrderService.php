<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class OrderService
{
    const PRE_ORDER_ID = "sec";

    const STORE_DEDUCTION_SCRIPT_LUA = '
            local inventoryKey = KEYS[1]
            local quantity = tonumber(ARGV[1])

            local currentQuantity = tonumber(redis.call("GET", inventoryKey))

            if currentQuantity and currentQuantity >= quantity then
                return redis.call("DECRBY", inventoryKey, quantity)
            else
                return 0
            end';
    public static $store_deduction_script_sha1;
    public function __construct()
    {
        self::$store_deduction_script_sha1 = Redis::script('load',self::STORE_DEDUCTION_SCRIPT_LUA);
    }

    public static function evalsha($key, $num)
    {
        // 使用 EvalSha 方法执行脚本
        $key_num = 1; // Lua 脚本所需要的键的数量
        return Redis::evalSha(self::$store_deduction_script_sha1, $key_num, $key, $num);
    }

    public function submitOrder(array $params = [])
    {
        // 限购
        $count = self::evalsha($params['product_id'], $params['buy_num']);
        if ($count <= 0) {
            return response()->json(['msg' => '扣减库存失败'], 201);
        }
        // 下单-初始化
        $order_info = [];

        // 启动事务
        DB::beginTransaction();
        // 捕获异常
        try{
            $order_info['order_id'] = self::PRE_ORDER_ID.app('snowflake')->nextId();
            $order_info['product_id'] = $params['product_id'];
            $order_info['user_id'] = $params['user_id'];
            $order_info['buy_num'] = $params['buy_num'];
            $order_info['amount'] = $params['amount'];
            $order_info['payment_type'] = $params['payment_type'];
            $order_info['order_time'] = Carbon::now()->format('Y-m-d H:i:s');
            $order_info['order_status'] = 0;
            $order = Order::create($order_info);

            // 预占库存

            // 更新订单-下单成功待支付

            //提交事务
            DB::commit();
        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()], 201);
        }




        // 根据订单号获取支付URL

        // 其他：包括一些数据统计等，可通过消息来解耦完成
    }

    public function updateStockNum(){

    }
}


