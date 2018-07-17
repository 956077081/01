<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table              = 'category';
    const STATUS_YES              = 1;
    const STATUS_NO               =2;
    const STATUS_TEST             = 3;
    const MAX_PATH                = 0;
    public function pitchStatus($allStatus =false)
    {
        $map =[
            self::STATUS_YES => "开启",
            self::STATUS_NO  => "关闭",
        ];
        if($allStatus){
            return $map;
        }
        if( array_key_exists( $this->status , $map) ){
            return $map[$this->status];
        }
    }

    /**
     * @return  string 返回 PHP->框架  连接父类型的名称
     */
    public function linkName()
    {   //去掉逗号 0,1,
        if ( $this->parent_id == 0 ) {
             return $this->name;
         }
         //删除尾部逗号
        $path    = rtrim($this->path,',');
        //删除开头的0,
        $delzero = substr($path,'2');
        $exparentId  = explode(',' , $this->path);
        $linkName = self::whereIn('id',$exparentId)->orderBy('path')->pluck('name')->toArray();
        return join('->',$linkName)."->".$this->name;
    }
    //查看是否容许删除
    public function allowDelete()
    {
        //检查是否存在子级
        $countp = self::where('parent_id', $this->id)->count();
        if ($countp > 0){
            return false;
        }
        $countAr = Article::where('category_id',$this->id)->count();
        if($countAr >0 ){
            return false;
        }
        return true;
    }
    public function getParentName()
    {
        if($this->parent_id == 0){
            return '[顶级]';
        }
        $category = self::where('id',$this->parent_id)->first();
        return $category->name;
    }
}
