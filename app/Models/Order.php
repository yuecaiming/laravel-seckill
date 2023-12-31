<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Order extends Authenticatable
{
    use HasFactory;
    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 't_order';
//    public $timestamps = false;

    protected $fillable = ['order_id', 'product_id', 'user_id', 'buy_num', 'amount','payment_type','order_time','order_status'];

    /**
     * 不会被包含在模型的结果集中
     * @var string[]
     */
    protected $hidden = ['id', 'created_at', 'updated_at', 'is_enable'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
