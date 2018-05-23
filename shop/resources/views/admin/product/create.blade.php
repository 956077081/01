@extends('admin/layouts/master')
<?php $leftActive = 'admin/product'  ?>
@section('content-wrapper')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                添加商品
                <small> 添加商品信息</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active"><a href="{{ url('admin/category') }}">商品管理</a></li>
                <li class="active" href="#">新增商品</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content container-fluid">
            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <form role="form" action="{{ url('/admin/product') }}" method="post">
                {{csrf_field()}}
                @include('admin/common/_message')

                <div class="box-body">

                    <div class="form-group  @if($errors->has('category_id')) has-error @endif">
                        <label for="inputParent">分类</label>
                        <select class="form-control " name="category_id" id="inputParent" >
                            @foreach($cateAll as $value)
                                <option value="{{ $value->id }}">{{ $value->outputwalk() }}</option>
                            @endforeach
                        </select>

                        <p class="help-block">请选择商品类别</p>
                    </div>
                    <div class="form-group  @if($errors->has('name')) has-error @endif" >
                        <label for="exampleInputEmail1">名称</label>
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="">
                        <p class="help-block">请输入分类名称</p>
                    </div>
                    <div class="form-group @if($errors->has('price')) has-error @endif ">
                        <label for="exampleInputEmail1">价格</label>
                        <input type="text" class=" form-control" name="price">
                        <p class="help-block">请输入分类名称</p>
                    </div>
                    <div class="form-group  @if($errors->has('sort')) has-error @endif">
                        <label for="exampleInputPassword1">排序</label>
                        <input type="text" class="form-control" name="sort" id="inputsort" placeholder="">
                        <p class="help-block">请输入整数,越小越靠前.</p>
                    </div>
                <div class="form-group  @if($errors->has('status')) has-error @endif">
                    <label>状态:</label>
                    <div>
                        @foreach($products->clarifyStatue(true) as $key => $value)
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="status" id="optionsRadios1" value="{{ $key }}">
                                    {{$value}}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <p class="help-block">选择状态.</p>
                </div>

                <div class="form-group">
                    <label>商品描述:</label>
                    <div>

                        <textarea name="content" id="" class='form-control' cols="20" rows="10"></textarea>
                    </div>

                    <p class="help-block">选择状态.</p>
                </div>

    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
    </form>

    </section>
    <!-- /.content -->
    </div>
@endsection

@section('js')
@endsection