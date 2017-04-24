<template>
	<!-- 信息卡片开始 -->
	<div class="box box-primary">
		<div class="box-body box-profile">
			<img :src=profile.avatar alt="" class="profile-user-img img-responsive img-circle">
			<h3 class="profile-username text-center">{{userInfo.name}}</h3>
			<p class="text-muted text-center">{{userInfo.unit}}</p>
			<ul class="list-group list-group-unbordered">
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 我的生日
					<span class="pull-right text-blue">{{userInfo.diffMyDays}}</span>
				</li>
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 注册时间
					<span class="pull-right text-blue">{{userInfo.created_at}}</span>
				</li>
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 上次登录
					<span class="pull-right text-blue">{{userInfo.last_login}}</span>
				</li>
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 上次登录IP
					<span class="pull-right text-blue">{{userInfo.last_ip}}</span>
				</li>
			</ul>
			<strong><i class="fa fa-user-o margin-r-5"></i> 我的角色</strong>
			<label-group :lists="userInfo.rolemission.roles"></label-group>
			<strong><i class="fa fa-key margin-r-5"></i> 我的权限</strong>
			<label-group :lists="userInfo.rolemission.permissions"></label-group>
		</div>
	</div>
	<!-- 信息卡片END -->
</template>
<script>
	import LabelGroup from './LabelGroup'
	export default {
		props: {
			userInfo: Object
		},
		computed: {
			userInfo () {
				return this.$store.getters.getUserInfo
			},
			profile () {
				return this.$store.getters.getProfile
			}
		},
		components: {
			LabelGroup
		}
	}
</script>