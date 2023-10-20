<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class ReserveInfo extends Authenticatable
{
    use HasFactory;
    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 't_reserve_info';
//    public $timestamps = false;

    protected $fillable = ['sku_id', 'reserve_start_time', 'reserve_end_time', 'seckill_start_time', 'seckill_end_time', 'creator', 'yn'];


}
