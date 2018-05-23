<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
//        $categories=Category::orderBy('sort')->get();  //orderByRaw指内部 不转译  否则  order内使用函数 会报错

        $cate = Category::orderByRaw('concat(path ,id)');
        if ($request->get('status')) {
            $cate->where('status', '=', $request->get('status'));
        }

        if ($request->get('name')) {
            $str = '%' . $request->get('name') . '%';
            $cate->where('name', 'like', $str);
        }
        $categories = $cate->paginate(3);
        return view("admin/category/index")->with("categories", $categories);
    }

    public function create()
    {
        $All = Category::orderByRaw('concat(path ,id)')->get();

        $category = new Category();
        return view('admin/category/create')->with([
            'categories' => $category,
            "cateAll" => $All
        ]);
    }

    //接受存储数据
    public function doCreate(Request $request)
    {
        $category = new Category();
        //$category->name=$request->get('name');//得到name字段
        $formMessage = $request->all();//得到表单传输的数据
        $parent_id = $request->get('parent_id');
        if ($parent_id == 0) {
            $path = '0,';
        } else {
            //当为子目录时 查找 该父目录的path路径加上本目录的id
            $parentCategory = Category::find($parent_id);
            if ($parentCategory == null) {
                abort(500, "父级有误!");
            } else {
                $path = $parentCategory->path . $parent_id . ',';
            }
        }
        $category->path = $path;
        $category->fill($formMessage);//为对象category 填充数据不用一个一个传
        $bool = $category->save();
        if($bool){
            return redirect()->back()->with('message','创建成功!');
        }else{
            return back()->withInput()->with('message','创建失败!');
        }
    }

    public function delete(Request $request)
    {
        $ids = explode(',', $request->get('id'));
        $categories = Category::whereIn('id', $ids)->get();
        \DB::beginTransaction();// 开启事物性  当执行真确时提交到数据库
        $deleteNum = 0;
        foreach ($categories as $category) {
            $boolDelete = $category->allowDelete();
            if (!$boolDelete) {
                break;
            }
            $deleteNum += $category->delete(); //delete 函数返回影响的函数

        }
        //检查是否允许删除
        /* $boolDelete = $category->allowDelete();
         if (!$boolDelete) {
             abort('删除失败');
         }
         $category->delete();*/
        //返回到上个页面
        if ($deleteNum == count($categories)) {
            \DB::commit();
            $message = '删除成功! 删除' . $deleteNum . "条记录.";
        } else {
            \DB::rollBack();//事物回滚
            $message = '删除失败! ';
        }
        return redirect()->back()->with('message', $message);//使用redirect  发生页面跳转  传递的参数默认存储在sessION中

    }

    /**
     * 修改类型
     */
    public function update($id)
    {
        $categories = Category::findOrFail($id);//查找失败时 输入错误

        return view('admin/category/update')->with('categories', $categories);
    }

    /**
     * 修改category内容
     * @param $id  要存储的id
     * @return $this
     */
    public function doupdate(Request $request, $id)
    {
        //书写中间件书写验证规则
        $rule=[
            'name'=>'required|min:2|max:15',
            'sort'=>'required|integer',
            'status'=>'required|integer'
        ];
        $this->validate($request,$rule);//中间件
        $categories = Category::findOrFail($id);//查找失败时 输入错误
        $messageAll = $request->all();
      //  $categories->fill($messageAll);//范围前端伪造传递参数 修改了parent_id 使数据库的内容出错 修改不建议使用批量传输\
        $categories->name = $messageAll['name'];
        $categories->sort = $messageAll['sort'];
        $categories->status = $messageAll['status'];
        $bool = $categories->save();
        if ($bool) {
            return redirect('admin/category')->with("message", "修改成功");
        } else {
            return back()->withInput()->with("message", "修改失败");//携带提交的数据 返回去  保留刚才提交的数据
        }
    }
    public function  test(){
        $member = new Member();
        $member->name="123";
        $member->passwd='123';
        if($member->save()){
            auth()->guard('member')->login($member);
            return "sadas";
        }
    }
}
