@extends("admin/layouts/master")
@section("title","仪表盘")
@section("content-wrapper")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                welcome {{ auth()->user()->name }} blog manage
                <small>控制面板</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">主页</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">最近发表文章</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>ID</th>
                            <th>标题头</th>
                            <th>状态</th>
                            <th>优先级</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                        </tr>
                        @foreach($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td> {{ $article->head }} </td>
                            <td> {{ $article->status }} </td>
                            <td>{{ $article->sort }}</td>
                            <td><span class="label label-success">{{ $article->created_at }}</span></td>
                            <td> {{ $article->updated_at }}</td>
                        </tr>
                            @endforeach
                        </tbody></table>
                </div>
                <!-- /.box-body -->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection