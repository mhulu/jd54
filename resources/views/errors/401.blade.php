@extends('layouts.mini')
@section('content')
	<div class="row">
	        <div class="col-md-12">
	              <div class="callout callout-danger">
	                <h4><i class="fa fa-user-times"></i> 没有访问权限</h4>
	                <p>
	                	您当前的登录用户无法访问特殊权限资源，这可能由于您权限不足或者您的账户被管理员冻结。
	                </p>
	              </div>
	            	<a href="/login"  class="btn btn-primary btn-lg btn-block margin-bottom-20"> 确定</a>
	            </div>
	      </div>
@endsection