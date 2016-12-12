@extends('layouts')
@section('header')
    @parent
    header
@stop

@section('sidebar')
    @parent
    cebuanlan
@stop

@section('content')
    content:::
    <!-- 输出变量-->
    {{--<p>{{var_dump($list)}}</p>--}}
    {{--php中的函数--}}
    <p>{{time()}}</p>
    <p>{{date('Y-m-d H:i:s')}}</p>
    <p>{{$name ?? 0}}</p>
    {{--原样输出--}}
    <p>@{{ $name }}</p>
    {{--引入自视图--}}
    @include('teacher.common', ['message' => 'what'])
@stop