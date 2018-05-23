@extends('admin/layouts/master')
<?php $leftActive = 'admin/category'  ?>
@section('content-wrapper')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                分类管理
                <small>商品分类列表</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('admin')}}"><i class="fa fa-dashboard"></i> 主页</a></li>
                <li class="active">商品列表</li>
            </ol>
        </section>

        <!-- Main content -->

        <section class="content container-fluid">
            <form action="{{ url('admin/category') }}" method="get">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="名称" name="name" value="{{request()->get('name')}}">
                            </div>
                            <div class="col-md-3">
                                <select  name="status"  class="form-control" >
                                    <option value="" >全部</option>
                                    <option value="{{ \App\Category::STATUE_YES }}"
                                    @if(request()->get('status')== \App\Category::STATUE_YES  ) selected  @endif>启用</option>
                                    <option value="{{App\Category::STATUE_NO}}"
                                    @if(request()->get('status')== \App\Category:: STATUE_NO ) selected  @endif >禁止</option>

                                </select>

                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <span class=" glyphicon glyphicon-search"> 搜索 </span>
                                </button>

                                <a class="btn btn-default btn-sm" href="{{ url('admin/category') }}">
                                    <span class=" glyphicon glyphicon-book" > 全部 </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            @include('admin/common/_message')
            <div class="box">
                <div class="box-header">
                    <a href="{{ url('/admin/category/create') }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> 增加</a>
                    <a href="javascript:;" class="js-delete btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span>删除</a>
                    <form action="{{ url('admin/category/delete') }}" method="post" id="js-delete-form" style="display:none">
                        {{csrf_field()}}
                        <input  type="text"  name ='id' value=""  >
                    </form>

                </div>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th><input type="checkbox" class="js-delete-all"></th>
                            <th>ID</th>
                            <th>名称</th>
                            <th>状态</th>
                            <th>排序</th>
                            <th>新增时间</th>
                            <th>操作</th>

                        </tr>
                        @foreach($categories as $category)
                        <tr>
                            <td><input type="checkbox" class="ids" value="{{ $category->id }}"></td>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->outputwalk() }}</td>
                            <td>{{ $category->clarifyStatue() }}</td>
                            <td>{{ $category->sort }}</td>
                            <td>{{ $category->updated_at }}</td>
                            <td><a href="{{ url('admin/category/update',$category->id) }}">编辑</a></td>
                            <td><a  data-id="{{ $category->id }}" class="js-delete-one" href="javascript:;">删除</a></td>
                        </tr>
                     @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    {{$categories->links()}}
                </div>
                <!-- /.box-body -->
            </div>

        </section>
        <!-- /.content -->
    </div>
@endsection
@section('js')
    <script>
        $(function(){
            //删除单项
            $('.js-delete-one').click(function () {
                if(!confirm("您确定要删除吗?")){
                    return;
                }
                var id = $(this).attr('data-id');
                //吧id填充到表单中去
                $("#js-delete-form  input[ name = 'id' ]").val(id);//得到ID值
                $('#js-delete-form').submit();
            });
            //删除选中
            $('.js-delete').click(function () {
                var checkBox=$('.ids:checked');//返回的为一个集合
                if(checkBox.length == 0){
                    return;
                }
                var arrids=[];
                for(var i=0;i<checkBox.length;i++){
                    arrids.push( $(checkBox[i] ).val() );//将所选中的元素的带有的id中传到 arrids中
                }
                var idsStr=arrids.toString();
                $("#js-delete-form input[name = 'id']").val(idsStr);//给input赋值
                $('#js-delete-form').submit();
                //上传表单
            })
            //全选
            $('.js-delete-all').click(function () {
               //去自己的状态
                var status = $(this).prop('checked');
                //设置其他的状态
                $('.ids').prop('checked',status);
                //1,2,3
                })


        })
    </script>
@endsection