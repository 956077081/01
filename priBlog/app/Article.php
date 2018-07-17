<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';
    const STATUS_YES = 1;
    const STATUS_NO = 2;
    public function descript()
    {
        return $this->hasOne(ArticleDescript::class);//一对一
    }
    public function pitchStatus($allowAll = false)
    {
        $map = [
            self::STATUS_YES  =>  '开启',
            self::STATUS_NO   =>  '禁止',
        ];
        if($allowAll){
            return $map;
        }
        if( array_key_exists($this->status,$map) ){
            return $map[$this->status];
        }
    }
    public function category()
    {
        //多对1
        return $this->belongsTo(Category::class);
    }

}
