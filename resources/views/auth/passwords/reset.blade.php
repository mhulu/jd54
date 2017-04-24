@extends('layouts.mini')

@section('content')
<h4 id="reset" class="text-center"><i class="fa fa-key"></i> 找回密码</h4>
<form action="" class="form-horizontal">
   {{csrf_field()}}
   <reset></reset>
   <div class="row">
      <div class="col-sm-12">
       <a href="/login" class="pull-right" style="height: 43px;line-height: 43px;">已有账号，返回登录</a>
     </div>
    </div>
</form>
@endsection
