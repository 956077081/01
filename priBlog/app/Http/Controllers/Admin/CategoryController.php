<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cate = Category::orderByRaw('concat(path ,id)');
        if ($request->get('name')) {
            $cate->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('status')) {
            $cate->where('status', $request->get('status'));
        }
        $user = auth()->user()->name;
        $categories = $cate->paginate(2);
        $categories->appends($request->all());
        return view('admin/category/index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //最高为2级目录
        //因此只能在1 一级目录下创建
        $cateAll = Category::where('parent_id', '0')->orderByRaw('concat(path,id)')->get();

        return view('admin/category/create')->with(['cateAll' => $cateAll, 'categories' => new Category()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rule = [
            'name' => 'required||unique:Category',
            'sort' => 'required|integer',
            'status' => 'required|integer',
            'parent_id' => 'required | integer',
        ];
        $this->validate($request, $rule);//验证
        $formMessage = $request->all();
        $parent_id = $request->get('parent_id');
        if ($parent_id == 0) {
            $path = '0,';
        } else {
            $categoryPar = Category::find($request['parent_id']);
            if (!$categoryPar) {
                $message = '提交失败! 父级有误';
                return redirect()->back()->with('message', $message);
            }
            $path = $categoryPar->path . $parent_id . ',';
        }
        $category = new Category();
        $category->name = $formMessage['name'];
        $category->parent_id = $formMessage['parent_id'];
        $category->status = $formMessage['status'];
        $category->sort =  $formMessage['sort'];
        $category->path = $path;
//        $category->fill($formMessage);  //必须为request中得到的
        $bool = $category->save();
        if (!$bool) {
            $message = '提交失败!';
        } else {
            $message = '提交成功!';
        }
        return redirect()->back()->with('message', $message);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if( !$category ){
            $message = "找不到该目录";
        }else{
            $message='';
        }
        return view("admin/category/update")->with(['message'=>$message,'category'=>$category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            $rule = [
                'name'=>'required|unique:Category',
                'sort' => 'required|integer',
                'status' => 'required|integer',
            ];
        $this->validate($request,$rule);
       $category  = Category::findOrFail($id);
        $category->name = $request->name;
        $category->sort = $request->sort;
        $category->status = $request->status;
        $bool =  $category->save();
        if( $bool ){
            $message = "修改成功!";
            return redirect()->to('admin/category')->with('message',$message);
        }else{
            $message = '修改失败!';
            return redirect()->back()->withInput()->with("message",$message);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $arrids = explode(',', $id);
        //检查 是否 父节点  同时保证该目录下没有文章
        $categories = Category::whereIn('id', $arrids)->get();
        \DB::beginTransaction(); //开启事物
        $deletenum = 0;
        foreach ($categories as $category) {
            $boolall = $category->allowDelete();
            if (!$boolall) {
                break;
            }
            if ($category->delete()) {
                $deletenum++;
            } else {
                break;
            }
        }
        if ($deletenum != count($categories)) {
            //事物回滚
            \DB::rollBack();
            $message = 'error';
        } else {
            //事物提交
            \DB::commit();
            $message = "sucess";
        }

        return redirect()->back()->with('message', $message);
    }
}
