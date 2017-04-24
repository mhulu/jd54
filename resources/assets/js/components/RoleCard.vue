<template>
	<!-- 权限列表开始 -->
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">角色与权限</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<strong><i class="fa fa-user-o margin-r-5"></i> 我的角色</strong>
			<label-group :lists="query.roles"></label-group>
			<hr>
			<strong><i class="fa fa-key margin-r-5"></i>我的权限</strong>
			<label-group :lists="query.permissions"></label-group>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- 权限列表结束 -->
</template>

<script>
	import LabelGroup from './LabelGroup'
	export default {
		computed: {
			query () {
				return this.$store.getters.getPermissions
			},
		},
		components: {
			LabelGroup
		}
	}
</script>