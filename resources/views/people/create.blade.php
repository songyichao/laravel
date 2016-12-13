@extends('common.layouts')
@section('content')
    {{--@include('common.validate')--}}
    <div class="panel panel-default">
        <div class="panel-heading">新增学生</div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{url('people/save')}}">
            @include('people._form')
        </div>
    </div>
@stop