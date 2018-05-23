<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //因为artisan make:model 建立数据迁移文件  默认建表为复数 格式 及categories表  应为 我们将数据迁移文件的  category改
    //为单数 (方便共同开发)   因此在模型文件中需要我们重写掉table文件名
    protected $table = "category";
    //设置fill可以进行全部填充的范围  及 $category->fill()函数 fill字段 可以填写的参数范围
    protected $fillable = ['name', 'status', 'sort', 'parent_id', 'path'];

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

    /**
     *   规格化输出 名称
     * @return mixed|string
     */
    public function outputwalk()
    {

        //0,1.2.
        $path = $this->path;
        if ($this->parent_id == 0) {
            return $this->name;
        }
        //去掉0
        $path = substr($path, 2);//截取掉第二个字节后的字符
        $path = rtrim($path, ',');//去掉,号
        $ids = explode(',', $path);
        $names = self::whereIn('id', $ids)->orderBy('path')->pluck('name')->ToArray();
        return join('>', $names) . ">" . $this->name;
    }

    /**
     * 检查是否可以删除
     * @return boolean
     */
    public function allowDelete()
    {
        //有子关联不能删
        $count = self::where('parent_id', '=', $this->id)->count();
        if ($count > 0) {
            return false;
        }
        //有商品不能删
        $count = Product::where('category_id', '=', $this->id)->count();
        if ($count > 0) {
            return false;
        }
        return true;
    }

    /**
     * 得到父节点
     */
    public function getParentName()
    {
        $id = $this->parent_id;
        if($id==0){
            return '[顶级]';
        }
        $category = self::where('parent_id','=', $id)->get();//返回得是一个集合及对象数组
//        $category = self::find($id);//返回一个对象 因此 在直接使用$category->name 不要指定  第几个对象的属性 例如: $category[0]->name
        return $category;
    }
}
