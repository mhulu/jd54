<template>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-white">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<fieldset>
								<legend>
									用户资料
								</legend>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
										<label>姓名</label>
										<input type="text" class="form-control" v-model="user.name">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>手机</label>
										<input type="text" class="form-control" v-model="user.mobile">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>邮箱</label>
										<input type="text" class="form-control" v-model="user.email">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>出生日期</label>
										<date-picker v-model="user.profile.birthday" :date.sync="user.profile.birthday"></date-picker>
									</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
										<label>昵称</label>
										<input type="text" class="form-control" v-model="user.profile.nickname">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>QQ</label>
										<input type="text" class="form-control" v-model="user.profile.qq">
									</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
										<label>性别</label>
										<div class="radio-primary clip-radio">
											<input type="radio" value="1" name="gender" id="male" v-model="user.profile.sex" >
											<label for="male">男</label>
											<input type="radio" value="0" name="gender" id="female" v-model="user.profile.sex">
											<label for="female">女</label>
										</div>
									</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
										<label>用户状态</label>
										<div class="clip-radio radio-primary">
											<input type="radio" id="0" name="status" value="0" v-model="user.status">
											<label for="0"> 冻结 </label>
											<input type="radio" id="1" name="status" value="1" v-model="user.status">
											<label for="1"> 正常 </label>
										</div>
									</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="col-md-12">
							<fieldset>
								<legend>
									角色权限
								</legend>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
										<label>所属部门</label>
										<span class="clip-select">
											<select name="unit" v-model="user.unit" class="form-control">
											<option v-for="item in units" :value="item.id">{{item.name}}</option>
											</select>
										</span>
									</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
										<p><label>用户权限</label></p>
										<div class="checkbox clip-check check-primary checkbox-inline" v-for="(permission, index) in permissions">
                                        <input type="checkbox" :disabled="index === 0" :id="permission.name" :value="permission.name" v-model="user.rolemission.permissions">
                                        <label :for="permission.name">
                                            {{permission.label}}
                                        </label>
                                    </div>
									</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<p><code v-show="isMyself"> 您不能在该模块中修改自己的资料，如需修改请去 个人中心 </code></p>
					<a @click.prevent="saveUserInfo" class="btn btn-success" :disabled="isMyself"><i class="fa fa-check-circle"></i> 修改用户资料</a>
					<a @click.prevent="onDeleted" class="btn btn-danger" :disabled="isMyself"><i class="fa fa-times-circle"></i> 删除该用户</a>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import DatePicker from '../../components/Datepicker'
	export default {
		data () {
			return {
				user: {
					profile: {},
					rolemission: {
						roles: {},
						permissions: {}
					},
					oldMobile: ''
				},
				units: {},
				permissions: {}
			}
		},
		computed: {
			isMyself () {
				return this.$route.params.id === window.Wemesh.id
			}
		},
		watch: {
			'$route': 'loadData'
		},
		created () {
			this.loadData()
		},
		methods: {
			loadData () {
				this.$store.commit('loading')
				let getUser = () => {
					return axios.get('user/' + this.$route.params.id + '/edit')
				}
				let getUnits = () => {
					return axios.get('unit')
				}
				let getPermissions = () => {
					return axios.get('permission')
				}

				axios.all([getUser(), getUnits(), getPermissions()])
				.then(axios.spread((user, units, permissions) => {
					this.user = user.data.data
					this.user.oldMobile = user.data.data.mobile
					this.units = units.data.data
					this.permissions = permissions.data
					this.$store.commit('loaded')
				}))
				.catch((error) => {
					let data = error.response.data
					toastr.error(data[Object.keys(data)[0]])
				})
			},
			saveUserInfo () {
				let profile = {
					nickname: this.user.profile.nickname,
					birthday: this.user.profile.birthday,
					qq: this.user.profile.qq,
					sex: this.user.profile.sex
				}
				if (this.user.mobile === this.user.oldMobile) {
					var baseInfo = {
						name: this.user.name,
						email: this.user.email,
						status: parseInt(this.user.status)
					}
				} else {
					var baseInfo = {
						name: this.user.name,
						mobile: this.user.mobile,
						email: this.user.email,
						status: parseInt(this.user.status)
					}
				}
				
				let unit = this.user.unit
				let permissions = this.user.rolemission.permissions

				this.submitData ({ profile: profile, baseInfo:baseInfo, unit: unit, permissions: permissions })
			},
			submitData (data) {
				axios.put('user/' + this.$route.params.id, data)
				.then(() => {
					toastr.success('用户资料修改成功')
					this.$router.push({path: '/dashboard/users'})
				})
				.catch((error) => {
					let data = error.response.data
					swal('操作失败', data[Object.keys(data)[0]], 'error')
				})
			},
			onDeleted () {
				swal({
				  title: '正在删除用户',
				  text: '您确定要删除这个用户吗？一旦确认，这个用户所有的数据将被彻底删除',
				  type: 'error',
				  allowOutsideClick: true,
				  showCancelButton: true,
				  confirmButtonColor: '#DD6B55',
				  confirmButtonText: '确定删除',
				  cancelButtonText: '取消删除'
				}, () => {
					this.deleteData()
				})
			},
			deleteData () {
				axios.delete('user/' + this.$route.params.id)
					.then(() => {
						toastr.success('用户资料修改成功')
						this.$router.push({path: '/dashboard/users'})
					})
					.catch((error) => {
						toastr.error(error)
					})
			}
		},
		components: {
			DatePicker
		}
	}
</script>