@extends('admin.public.admin')
@section('main')
	<!-- 内容 -->
	<div class="col-md-10">

		<ol class="breadcrumb">
			<li><a href="#"><span class="glyphicon glyphicon-home"></span> 首页</a></li>
			<li><a href="#">系统配置管理</a></li>
			<li class="active">系统配置列表</li>

			<button class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-refresh"></span></button>
		</ol>

		<!-- 面版 -->
		<div class="panel panel-default">
			<div class="panel-heading">
			<form action="/admin/sys/config" method="post">
				{{csrf_field()}}
				<div class="form-group">
					<label for="">Title</label>
					<input type="text" value="{{config('web.title')}}" name="title" class="form-control" placeholder="title" id="">
					<div id="userInfo">
					</div>
				</div>
				<div class="form-group">
					<label for="">Keywords</label>
					<input type="text" value="{{config('web.keywords')}}" name="keywords" class="form-control" placeholder="关键字" id="">
				</div>
				<div class="form-group">
					<label for="">Description</label>
					<input type="text" name="description" value="{{config('web.description')}}" class="form-control" placeholder="请输入描述" id="">
				</div>
				<div class="form-group">
					<label for="">Logo</label>
					<input type="text" name="logo" value="{{config('web.logo')}}" class="form-control" placeholder="请输入logo" id="">
				</div>
				<div class="form-group">
					<label for="">统计</label>
					<textarea typeof="text" name="baidu" cols="30" rows="10" class="form-control">{{config('web.baidu')}}</textarea>
				</div>
				<div class="form-group pull-right">
					<input type="submit" value="提交" onclick="add()" class="btn btn-success">
					<input type="reset" id="reset" value="重置" class="btn btn-danger">
				</div>
				<div style="clear:both"></div>
			</form>
			</div>
		</div>
	</div>
@endsection