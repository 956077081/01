@extends('admin/layouts/master')
@section('content-wrapper')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                修改文章
                <small> 修改文章信息</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active"><a href="{{ url('admin/artcile') }}">文章管理</a></li>
                <li class="active" href="{{url('admin/article/create')}}">新增文章</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content container-fluid">
            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <form role="form" action="{{ url('/admin/article', $article->id ) }}" method="post">
                {{csrf_field()}}
                {{ method_field('PUT') }}
                @include('admin/common/_message')

                <div class="box-body">

                    <div class="form-group  @if($errors->has('category_id')) has-error @endif">
                        <label for="inputParent">分类 -------------></label>
                        {{ $article->category->name }}
                        <p class="help-block">请选择文章类别</p>
                    </div>
                    <div class="form-group  @if($errors->has('head')) has-error @endif" >
                        <label for="exampleInputEmail1">标头</label>
                        <div>
                            <textarea name="head" id="" class='form-control' cols="10" rows="3">{{old('head',$article->head )}}</textarea>
                        </div>
                        <p class="help-block">请输入标头内容</p>
                    </div>

                    <div class="form-group  @if($errors->has('sort')) has-error @endif">
                        <label for="exampleInputPassword1">排序</label>
                        <input type="text" class="form-control" name="sort" id="inputsort" placeholder=""  value ="{{ old('sort',$article->sort) }} ">
                        <p class="help-block">请输入整数,越小越靠前.</p>
                    </div>


                    <div class="form-group">
                        <label>文章描述:</label>
                        <div>

                            <textarea name="content" id="" class='form-control' cols="20" rows="10">{{ old('content',$article->descript->content) }}</textarea>
                        </div>

                        <p class="help-block">选择状态.</p>
                    </div>
                    <div class="form-group  @if($errors->has('status')) has-error @endif">
                        <label>状态:</label>
                        <div>
                            @foreach($article->pitchStatus(true) as $key => $value)
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="status" id="optionsRadios1" value="{{ $key }}" @if( old('status',$article->status)== $key ) checked @endif>
                                        {{$value}}
                                    </label>
                                </div>
                            @endforeach
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