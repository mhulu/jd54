<template>
	<div class="row">
		<div class="col-md-3">
			<user-card :userInfo="userInfo"></user-card>
		</div>
		<div class="col-md-9">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#edit" data-toggle="tab" aria-expanded="true">修改资料</a>
					</li>
					<li>
						<a href="#security" data-toggle="tab" aria-expanded="true">安全设置</a>
					</li>
					<li>
						<a href="#timeline" data-toggle="tab" aria-expanded="true">近期事件</a>
					</li>
				</ul>
				<div class="tab-content">
					<!-- START PROFILE EDIT PANE-->
					<div class="tab-pane active" id="edit">
						<div class="form-horizontal">
							<avatar :imgPath="profile.avatar" :apiUrl="'home/avatar'"></avatar>
							<div class="form-group">
								<label class="col-sm-2 control-label">姓名 <span class="text-red">*</span></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" required placeholder="真实姓名" v-model="userInfo.name" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">昵称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="可以选填昵称" v-model="profile.nickname">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">性别 <span class="text-red">*</span></label>
								<div class="col-sm-10">
									<div class="radio-primary clip-radio">
										<input type="radio" value="1" name="gender" id="male" v-model="profile.sex" >
										<label for="male">
											男
										</label>
										<input type="radio" value="0" name="gender" id="female" v-model="profile.sex">
										<label for="female">
											女
										</label>
									</div>
								</div>	
							</div>

							<div class="form-group">
								<label for="datepicker" class="col-sm-2 control-label">出生日期 <span class="text-red">*</span></label>
								<date-picker class="col-sm-10"  v-model="profile.birthday" :date.sync="profile.birthday"></date-picker>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Email <span class="text-red">*</span></label>
								<div class="col-sm-10">
									<input type="email" class="form-control" required placeholder="需要填写电子邮箱" v-model="userInfo.email">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">QQ</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="可以选填QQ" v-model="profile.qq">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-danger" @click.stop.prevent = "onSubmit">提交修改</button>
								</div>
							</div>
						</div>
					</div>
					<!-- END PROFILE EDIT PANE-->
					<!-- START AVATAR CHANGED -->
					<div class="tab-pane" id="security">

					<div class="callout callout-danger">
							<h4><i class="fa fa-lightbulb-o"></i> 友情提示</h4>
						 修改密码或手机号码后会退出当前系统并需重新登录
					</div>
						<div class="row">
							<div class="col-md-6">
								<div class="panel panel-white">
								<div class="panel-heading bg-info">
									<h5 class="panel-title"><i class="fa fa-key"></i> 修改密码</h5>
								</div>
								<div class="panel-body">
									<form role="form">
										<div class="form-group">
											<label> 输入旧的密码 </label>
											<input type="password" class="form-control" placeholder="输入当前正在使用密码" v-model = "password.oldpass">
										</div>
										<div class="form-group">
											<label> 输入新的密码 </label>
											<input type="password" class="form-control" placeholder="输入不少于6位新的密码" v-model="password.newpass">
										</div>
										<div class="form-group">
											<label> 再次输入上面的密码 </label>
											<input type="password" class="form-control" placeholder="核对您的新密码" v-model="password.newpass2">
										</div>
										<button class="btn btn-info btn-block" @click.prevent="onChangePass" :disabled="!(validator.password)">
											提交新密码
										</button>
									</form>
								</div>
							</div>
							</div>
							<div class="col-md-6">
								<div class="panel panel-white">
								<div class="panel-heading bg-primary">
									<h5 class="panel-title"><i class="fa fa-mobile"></i> 更换手机</h5>
								</div>
								<div class="panel-body">
									<form role="form">
									<div class="form-group">
										<label> 当前手机号码 </label>
										<input type="number" class="form-control" :value="userInfo.mobile" readonly>
									</div>
										<div class="form-group">
											<label> 输入新的手机号码 </label>
											<input type="number" class="form-control" placeholder="输入您新的手机号码" v-model="mobile.newmobile">
										</div>
										<div class="form-group row">
													<label class="col-md-12"> 输入短信验证码 </label>
										      <div class="col-md-7">
										          <input type="text" name="authcode" class="form-control" placeholder="输入您收到的验证码" v-model="mobile.authcode">
										      </div>
										      <div class="col-md-5">
										          <authcode :color="'success'" :data="{mobile:this.mobile.newmobile}" :disabled="!validator.mobile.isMobile">获取验证码</authcode>
										      </div>
										    </div>
										<button  class="btn btn-primary btn-block" :disabled="!validator.mobile.passed" @click.prevent="onChangeMobile">
											提交新手机号码
										</button>
									</form>
								</div>
							</div>
							</div>
						</div>
						
					</div>
					<!-- END AVATAR CHANGED -->

					<!-- START TIMELINE PANE -->
					<div class="tab-pane" id="timeline">
						<timeline></timeline>
					</div>
					<!-- END TIMELINE PANE -->
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import xhr from '../xhr'
	import LabelGroup from '../components/LabelGroup'
	import Timeline from '../components/Timeline'
	import DatePicker from '../components/Datepicker'
	import Avatar from '../components/AvatarEditor'
	import UserCard from '../components/UserCard'
	import { isEmail, isDate, isMobile, required } from '../validator'
	import Authcode from '../components/AuthCode'
	export default {
		components: {
			Timeline, Avatar, UserCard, DatePicker, Authcode
		},
		data () {
			return {
				password: {
					oldpass: '',
					newpass: '',
					newpass2: ''
				},
				mobile: {
					authcode: '',
					newmobile: '',
					oldmobile: ''
				},
				alertContent: {
					alertType: 'toastr',
					success: {
						text: '个人资料修改成功'
					}
				}
			}
		},
		computed: {
			userInfo: {
				get () {
					return this.$store.getters.getUserInfo
				},
				set (value) {
					this.$store.commit('userInfo', value)
				}
			},
			profile: {
				get () {
					return this.$store.getters.getProfile
				},
				set (value) {
					this.$store.commit('profile', value)
				}
			},
			validator () {
			  return {
			    mobile: {
			    	isMobile: isMobile(this.mobile.newmobile),
			    	passed: required(this.mobile.authcode) && isMobile(this.mobile.newmobile) && this.mobile.newmobile !== this.mobile.oldmobile
			    },
			    password: required(this.password.oldpass) && required(this.password.newpass) && this.password.newpass === this.password.newpass2
			  }
			}
		},
		methods: {
			onSubmit () {
				this.$store.commit('loading')
				let baseInfo = {
					email: this.userInfo.email
				}
				let profile = {
					nickname: this.profile.nickname,
					birthday: this.profile.birthday,
					qq: this.profile.qq,
					sex: this.profile.sex
				}
				xhr('post', 'home', { profile: profile, baseInfo: baseInfo }, this.alertContent)
				// axios.post('home', { profile: profile, baseInfo: baseInfo })
				// 			.then((response) => {
				// 				// window.location.reload()
				// 				this.$store.commit('userInfo', response.data.data)
				// 				toastr.success('个人资料修改成功')
				// 				this.$store.commit('loading')
				// 			})
				// 			.catch((error) => {
				// 				toastr.error(error)
				// 			})
			},
			onChangePass () {
				this.$store.commit('loading')
				axios.post('home/changePassword', { password: this.password })
							.then((response) => {
								toastr.success(response.data.data)
								window.location.href = '/logout'
							})
							.catch((error) => {
								this.$store.commit('loaded')
								let data = error.response.data
								swal('操作出错', data[Object.keys(data)[0]], 'error')
							})
			},
			onChangeMobile() {
				this.$store.commit('loading')
				axios.post('home/changeMobile', { mobile: this.mobile })
							.then((response) => {
								toastr.success(response.data.data)
								window.location.href = '/logout'
							})
							.catch((error) => {
								this.$store.commit('loaded')
								let data = error.response.data
								swal('操作出错', data[Object.keys(data)[0]], 'error')
							})
			}
		}
	}
</script>