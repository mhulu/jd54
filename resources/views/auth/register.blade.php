@extends('layouts.mini')

@section('content')
<!-- TAB START -->
<div class="login-tab row margin-top-20">
  <div class="col-sm-12 tab-headers">
    <a href="login" class="col-sm-6">用户登录</a>
    <a href="register" class="col-sm-6 active">注册新用户</a>
  </div>
</div>
<form action="{{url('/register')}}" class="form-horizontal">
    {{csrf_field()}}
    <register></register>
</form>
@endsection
