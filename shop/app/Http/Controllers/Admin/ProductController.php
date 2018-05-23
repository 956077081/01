<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\StoreProductPost;
use App\Product;
use App\Category;
use App\ProductDescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
       /* $product=  Product::orderBy('id');
        //where()函数内部支持数组  可以提前将条件封装为一个数组  提交的方式
        if( $request->get('name') ) {
            $str = '%' . $request->get('name') . '%';
            $product->where('name', 'like', $str);
        }
        if($request->get('status')){
            $product->where('status','=',$request->get('status'));
        }
        $products = $product->paginate(2);//表示每页两条数据
        return view('admin/product/index')->with('products',$products);*/

       $condition = [];
       if($request->get('name')){
           $condition[] = ['name','like','%'.$request->get('name').'%'];
       }
       if($request->get('status')){
           $condition[] = ['status','=',$request->get('status')];
       }
       $products = Product::where($condition)->paginate(2);
       $products->appends( $request->all() );  //加入分页条件参数
       return view('admin/product/index')->with('products',$products);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cateAll = Category::where('status','=',Category::STATUE_YES)->orderByRaw('concat(path ,id)')->get();
        $products=new Product();
        return view('admin/product/create')->with(['cateAll'=>$cateAll ,'products'=>$products ]);
    }

    /**
     * Store a newly created resource in storage.
     *当在admin/product路由下POST传递参数时
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductPost $request)
    {
        //在StorePRODUCTPost中谢了 路由定义默认调用了rules函数
        \DB::beginTransaction();
        $product = new Product();

        $product->name = $request->get('name');
        $product->category_id= $request->get('category_id');
        $product->status = $request->get('status');
        $product->sort = $request->get('sort');
        $product->price  =  $request->get('price');

        $bool = $product->save();
        //存储description   因为关联了 productDescription 表 不用去寻找该product_id
        $description = new ProductDescription();
        $description->content = $request->get('content');
        $boolDes = $product->description()->save($description);
        if ( $bool && $boolDes ) {
            \DB::commit();//事物提交
            $message='商品添加成功!';
        }else{
            \DB::rollBack();//事物回滚
            $message='商品添加失败!';
        }
        return redirect()->back()->with('message',$message);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = explode(',',$id);
        \DB::beginTransaction();
        $product = Product::whereIn('id',$id);
        if ( $product->delete() ) {
            //再将product_description表中中字段删除
            $product_description =  ProductDescription::whereIn('product_id',$id)->delete();
            if ( $product_description ) {
                \DB::commit();
                return redirect()->back()->with('message','删除成功!');
            }
        }
        //如果两个删除都不成功则启用事物回滚
        \DB::rollBack();
        return redirect()->back()->with('message','删除失败!');
    }
}
