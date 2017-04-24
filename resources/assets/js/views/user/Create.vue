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
										<label>姓名<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" v-model="user.name" placeholder="输入人员真实姓名">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>手机<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" v-model="user.mobile" placeholder="管理人员的手机号码">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>邮箱<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" v-model="user.email" placeholder="管理人员的邮箱">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>出生日期<span class="text-danger"> *</span></label>
										<date-picker v-model="user.profile.birthday" :date.sync="user.profile.birthday"></date-picker>
									</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-3">
									<div class="form-group">
									<label>初始密码</label>
									<input type="text" class="form-control" v-model="user.password" placeholder="指定初始密码">
								</div>
								</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>昵称</label>
										<input type="text" class="form-control" v-model="user.profile.nickname" placeholder="可以输入人员的昵称">
									</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
										<label>QQ</label>
										<input type="text" class="form-control" v-model="user.profile.qq" placeholder="人员的QQ号码">
									</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
										<label>性别 <span class="text-danger"> *</span></label>
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
										<label>用户状态<span class="text-danger"> *</span></label>
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
										<label>所属部门<span class="text-danger"> *</span></label>
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
                                        <input type="checkbox" :disabled="index === 0" :id="permission.name" :value="permission.name" v-model="checked">
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
					<a @click.prevent="saveUserInfo" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交数据</a>
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
					name: '',
					mobile: '',
					email: '',
					status: 1,
					unit: 1,
					password: 'password',
					profile: {
						birthday: '1980-01-01',
						nickname: '',
						sex: 0
					}
				},
				units: {},
				permissions: {},
				checked: ['general']
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
				let getUnits = () => {
					return axios.get('unit')
				}
				let getPermissions = () => {
					return axios.get('permission')
				}

				axios.all([getUnits(), getPermissions()])
				.then(axios.spread((units, permissions) => {
					this.units = units.data.data
					this.permissions = permissions.data
					this.$store.commit('loaded')
				}))
				.catch((error) => {
					toastr.error(error)
				})
			},
			saveUserInfo () {
				let profile = {
					nickname: this.user.profile.nickname,
					birthday: this.user.profile.birthday,
					qq: this.user.profile.qq,
					sex: this.user.profile.sex
				}
				let baseInfo = {
					name: this.user.name,
					mobile: this.user.mobile,
					email: this.user.email,
					password: this.user.password,
					status: parseInt(this.user.status)
				}
				let unit = this.user.unit
				let permissions = this.user.checked

				this.submitData ({ profile: profile, baseInfo:baseInfo, unit: unit, permissions: permissions })
			},
			submitData (data) {
				axios.post('user', data)
				.then((response) => {
					toastr.success(response.data.data)
					this.$router.push({path: '/dashboard/users'})
				})
				.catch((error) => {
					let obj = error.response.data
					swal({
						title: '操作失败',
						text: obj[Object.keys(obj)[0]],
						type: 'error'
					})
						
					// toastr.error(obj[Object.keys(obj)[0]])
				})
			}
		},
		components: {
			DatePicker
		}
	}
</script>