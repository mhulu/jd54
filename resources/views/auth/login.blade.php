@extends('layouts.mini')
@section('content')
<!-- TAB START -->
<div class="login-tab row margin-top-20">
  <div class="col-sm-12 tab-headers">
    <a href="login" class="col-sm-6 active">用户登录</a>
    <a href="register" class="col-sm-6">注册新用户</a>
  </div>
</div>
<!-- FORM START -->
<form method="POST" role="form" action="{{ url('/login') }}">
  {{ csrf_field() }}
  <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-mobile" style="font-size: 20px;width:14px"></i></div>
      <input type="text" class="form-control" required autofocus placeholder=" 输入手机号" value="{{ old('mobile') }}" name="mobile">
    </div>
    @if ($errors->has('mobile'))
    <p class="text-danger">{{ $errors->first('mobile') }}</p>
    @endif
  </div>
  <div class="form-group">
   <div class="input-group">
     <div class="input-group-addon"><i class="fa fa-key"></i></div>
     <input type="password" class="form-control" required placeholder=" 输入您的密码" name="password">
   </div>
 </div>
 <div class="row">
   <div class="col-sm-6">
    <div class="checkbox clip-check check-primary ">
      <input type="checkbox"  id="rememberMe" name="remember">
      <label for="rememberMe" class="pull-left">
      自动登陆？ <i class="fa fa-unlock-alt"></i>
      </label>
    </div>
  </div>
  <div class="col-sm-6">
    <a href="/reset" class="pull-right" style="height: 43px;line-height: 43px;">找回密码？</a>
  </div>
</div>
<button  class="btn btn-primary btn-lg btn-block margin-bottom-20"><i class="fa fa-sign-in"></i> 点击登陆</button>
</form>
@endsection
