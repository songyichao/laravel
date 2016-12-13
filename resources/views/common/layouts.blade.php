<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    @section('style')

    @show
</head>
<body>
@section('header')
    <div class="jumbotron">
        <div class="container">
            <h1>laravel练习</h1>
            <p>-表单+布局-</p>
        </div>
    </div>
@show
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @section('leftMenu')
                <div class="list-group">
                    <a href="{{url('people/index')}}"
                       class="list-group-item {{Request::getPathInfo() == '/people/index' ? 'active' : ''}}">学生列表</a>
                    <a href="{{url('people/create')}}"
                       class="list-group-item {{Request::getPathInfo() == '/people/create' ? 'active' : ''}}">新增学生</a>
                </div>
            @show
        </div>
        <div class="col-md-9">
            @yield('content')
        </div>
    </div>
</div>
@section('footer')
    <div class="jumbotron" style="margin: 0;">
        <div class="container">
            <span>{{'@'.date('Y')}} syc</span>
        </div>
    </div>
@show
<script src="{{asset('js/app.js')}}"></script>
@section('javascript')
@show
</body>
</html>