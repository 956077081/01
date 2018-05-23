<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="product";

    //为提高代码的可维护性 采用 数字  做分类  前端页面尽可能不要出现数字
    const  STATUE_YES = 1;
    const STATUE_NO = 2;
    const STATUE_TEST = 3;
    const MAX_PATH = 0;//表示父目录

    /**
     * @param bool $returnAll 当为true时返回 所有的状态类型
     * @return array|mixed|string
     */
    public function clarifyStatue($returnAll = false)
    {
        $map = array(
            self::STATUE_YES => "开启",
            self::STATUE_NO => "禁止"
        );
        if ($returnAll) {
            return $map;
        }
        if (array_key_exists($this->status, $map)) {
            return $map[$this->status];
        }
        return '';
    }
    //数据关联  作为本类 多对一
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function  description()
    {
        return $this->hasOne(ProductDescription::class);
    }

}
