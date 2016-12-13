@extends('common.layouts')
@section('content')
    @include('common.message')
    @if(isset($list) && !$list->isEmpty())
        <div class="panel panel-default">

            <div class="panel-heading">学生列表</div>
            <table class="table table-striped table-hover table-responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>年龄</th>
                    <th>性别</th>
                    <th>添加时间</th>
                    <th width="120">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $man)
                    <tr>
                        <th scope="row">{{$man->id}}</th>
                        <td>{{$man->name}}</td>
                        <td>{{$man->age}}</td>
                        <td>
                            {{$man->sex}}
                        </td>
                        <td>{{date('Y-m-d H:is',$man->created_at)}}</td>
                        <td>
                            <a href="{{ url('people/show', ['id' => $man->id]) }}">详情</a>
                            <a href="{{ url('people/update', ['id' => $man->id]) }}">修改</a>
                            <a href="{{ url('people/delete', ['id' => $man->id]) }}"
                               onclick="if (confirm('确定要删除吗？') == false) return false; ">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- 分页  -->
        <div>
            <div class="pull-right">
                {{$list->render()}}
            </div>
        </div>
    @else
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>没有数据请添加！</strong>
        </div>
    @endif

@stop