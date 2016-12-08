<h1>创建文章</h1>
@if(count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="/admin/save" method="post">
    username:<input type="text" name="username">
    password:<input type="password" name="password">
    {{ csrf_field() }}
    <input type="submit">
</form>