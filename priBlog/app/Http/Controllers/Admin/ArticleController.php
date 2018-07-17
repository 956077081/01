<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\ArticleDescript;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles =  Article::orderBy('category_id');
        if($request->get('category_id')) {
            //查找category_id=1的
            $articles->where('category_id',$request->get('category_id') );
        }
        if($request->get('status') ){
            $articles->where('status',$request->get('status'));
        }
        if($request->get('head') ){
            $articles->where('head',$request->get('head') );
        }
        //得到所有类别
        $categories = Category::orderByRaw('concat(path,id)')->get();
        $artAll = $articles->paginate(6);
        return view('admin/article/index')->with(['artAll'=>$artAll,'categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //得到所有目录
        $categories = Category::orderByRaw('concat(path ,id)')->get();
        $article = new Category();
        return view('admin/article/create')->with(['categories'=>$categories, "artcile"=>$article]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule =[
            'head'=>'required|string|max:40',
        ];
        $this->validate($request,$rule);
        //在StorePRODUCTPost中谢了 路由定义默认调用了rules函数
        \DB::beginTransaction();
        $article = new Article();
        $article->head  =  $request->get('head');
        $article->category_id= $request->get('category_id');
        $article->status = $request->get('status');
        $article->sort = $request->get('sort');
        $bool = $article->save();
        //存储description   因为关联了 productDescription 表 不用去寻找该product_id
        $description = new ArticleDescript();
        $description->content = $request->get('content');
        $boolDes = $article->descript()->save($description);
        if ( $bool && $boolDes ) {
            \DB::commit();//事物提交
            $message='文章添加成功!';
        }else{
            \DB::rollBack();//事物回滚
            $message='文章添加失败!';
        }
        return redirect()->back()->withInput()->with('message',$message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //获取类别
        $article = Article::findOrFail($id);
        $category =$article->category();
        return view('admin/article/update')->with(['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule =[
            'head'=>'required|string|max:40',
            'sort' => 'required|integer',
            'status' => 'required|integer',
            'content' => 'string',
        ];
        $this->validate($request,$rule);
        \DB::beginTransaction();
        $article  = Article::findOrFail($id);
        $article->head = $request->head;
        $article->sort = $request->sort;
        $article->status = $request->status;
        $bool =  $article->save();

        $descrit = ArticleDescript::where('article_id' , $id);
        $booldes = $descrit->update(['content'=>$request->get('content')]);
        if($bool && $booldes){
            \DB::commit();
            $message = "修改成功!";
            return redirect()->to('admin/article')->with('message',$message);
        }else{
            \DB::rollBack();
            $message = '修改失败!';
            return redirect()->back()->withInput()->with("message",$message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode(',',$id);
        $artciles =Article::whereIn('id',$ids)->get();
        $deleteNum  = 0 ;
        \DB::beginTransaction();
        foreach ( $artciles as $artcile){
            $bool = $artcile->delete();
            if($bool){
                if(!$artcile->descript()->delete()){
                    //在内容没成功删除时 也 事物回滚
                    break;
                }
                $deleteNum++;
            }else{
                break;
            }
        }
        if(count($artciles) == $deleteNum){
            $message = "删除成功!";
            \DB::commit();
        }else{
            $message = '删除失败!';
            \DB::rollBack();
        }
        return  redirect()->back()->with('message',$message);
    }
}
