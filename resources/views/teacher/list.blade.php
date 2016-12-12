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
    <!--流程控制 -->
    {{--if--}}
    @if($name === 'syc')
        i am syc
    @elseif($name === 'lgc')
        i am lgc
    @else
        i???
    @endif
    {{--unless if 的取反--}}
    @unless($name === 'syc1')
        I am syc
    @endunless
    {{--for--}}
    <p>@for($i = 0;$i<10;$i++)
            {{$i}}
        @endfor</p>
    {{--foreach--}}
    @foreach($list as $item)
        {{$item->name}}
    @endforeach
    {{--forelse--}}
    @forelse($list as $item)
        {{$item->name}}
    @empty
        null
    @endforelse
    <!--url -->
    <a href="{{url('teacher/list')}}"> url</a>
    <a href="{{action('TeacherController@list')}}"> url</a>
    <a href="{{url('list')}}"> url</a>
@stop