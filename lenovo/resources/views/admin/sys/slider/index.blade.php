@extends('admin.public.admin')
@section('main')
	<!-- 内容 -->
	<div class="col-md-10">

		<ol class="breadcrumb">
			<li><a href="#"><span class="glyphicon glyphicon-home"></span> 首页</a></li>
			<li><a href="#">系统管理</a></li>
			<li class="active">系统轮播图列表</li>

			<button class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-refresh"></span></button>
		</ol>

		<!-- 面版 -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<button class="btn btn-danger">会员展示</button>
				<a href="javascript:;" data-toggle="modal" data-target="#add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> 添加轮播图</a>

				<p class="pull-right tots" >共有  条数据</p>
				<form action="" class="form-inline pull-right">
					<div class="form-group">
						<input type="text" name="search" class="form-control" placeholder="请输入你要搜索的内容" id="">
					</div>

					<input type="submit" value="搜索" class="btn btn-success">
				</form>


			</div>
			<table class="table-bordered table table-hover">
				<th>ID</th>
				<th>TEL</th>
				<th>EMAIL</th>
				<th>注册时间</th>
				<th>状态</th>

			</table>
			<!-- 分页效果 -->
			<div class="panel-footer">
			</div>
		</div>
	</div>
	<!-- 添加的摸态框 -->
	<div class="modal fade" id="add">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">添加轮播图</h4>
				</div>
				<div class="modal-body">
					<form action="" onsubmit="return false" id="formAdd">
						{{csrf_field()}}
						<div class="form-group">
							<label for="">Title</label>
							<input type="text" name="title" class="form-control" placeholder="title" id="">
							<div id="userInfo">
							</div>
						</div>
						<div class="form-group">
							<label for="">Href</label>
							<input type="text" name="herf" class="form-control" placeholder="友情链接" id="">
						</div>
						<div class="form-group">
							<label for="">Order</label>
							<input type="number" name="order" class="form-control" placeholder="数值越大越靠前" id="">
						</div>
						<div class="form-group">
							<label for="">Img</label>

						</div>
						<div class="form-group pull-right">
							<input type="submit" value="提交" onclick="add()" class="btn btn-success">
							<input type="reset" id="reset" value="重置" class="btn btn-danger">
						</div>

						<div style="clear:both"></div>
					</form>
				</div>

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@endsection