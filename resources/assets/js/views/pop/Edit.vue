<template>
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#base" data-toggle="tab" aria-expanded="true">个人基本信息</a>
					</li>
					<li>
						<a href="#archive" data-toggle="tab" aria-expanded="true">健康体检记录</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="base">
						<fieldset>
							<legend>
								基本信息
							</legend>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>姓名</label>
										<input type="text" class="form-control" v-model="pop.name">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>电话</label>
										<input type="text" class="form-control" v-model="pop.phone">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>身份证号码 <i class="fa fa-times text-danger" v-show="!pidValid"></i></label> 
										<div class="input-group">
																<input type="text" class="form-control" v-model="pop.identify">
																<span class="input-group-btn">
												                      <button @click="onCheckPid" type="button" class="btn btn-info btn-flat">身份验证</button>
											                    </span>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>现住址</label>
										<input type="text" class="form-control" v-model="pop.address">
									</div>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>户籍类型</label>
										<div class="radio-primary clip-radio">
											<input type="radio" value="1" name="livetype" id="yes" v-model="pop.livetype">
											<label for="yes">户籍</label>
											<input type="radio" value="0" name="livetype" id="no" v-model="pop.livetype">
											<label for="no">非户籍</label>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>民族</label>
										<input type="text" class="form-control" v-model="pop.nation">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>出生日期</label>
										<input type="text" class="form-control" readonly :value="pidInfo.birthday">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>户口所在地</label>
										<input type="text" class="form-control" readonly :value="pidInfo.area">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>性别</label>
										<div class="radio-primary clip-radio">
											<input type="radio" value="1" name="gender" id="male" v-model="pidInfo.gender" disabled>
											<label for="male">男</label>
											<input type="radio" value="0" name="gender" id="female" v-model="pidInfo.gender" disabled>
											<label for="female">女</label>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<p><label>医疗费用支付方式</label></p>
										<div class="radio clip-radio radio-primary radio-inline" v-for="(item, index) in paytype">
											<input type="radio" :id="index"  :value="item" v-model="pop.paytype">
											<label :for="index">
												{{item}}
											</label>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<div class="panel-footer">
							<button @click.prevent="saveUserInfo" class="btn btn-success" :disabled="!pidValid"><i class="fa fa-check-circle"></i> 修改用户资料</button>
							<a @click.prevent="onDeleted" class="btn btn-danger"><i class="fa fa-times-circle"></i> 删除该用户</a>
						</div>
					</div>
					<div class="tab-pane" id="archive">
						<fieldset  v-show="!showArchive">
							<div class="row">
								<legend>{{pop.name}}的健康档案</legend>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>编号</th>
											<th>建档日期</th>
											<th>建档单位</th>
											<th>责任医生</th>
											<th class="text-center">管理操作</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(archive, index) in pop.archive" @click.prevent="view_archive(index)">
											<td>{{index+1}}</td>
											<td>{{archive.created_at}}</td>
											<td>{{archive.check_unit}}</td>
											<td>{{archive.doctor}}</td>
											<td class="text-center">
												<a @click.prevent="view_archive(index)" class="btn btn-primary btn-sm">修改</a>
												<a @click.prevent="del_archive" class="btn btn-danger btn-sm">删除</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</fieldset>
						<div class="row" v-show="showArchive">
							<div class="col-md-12">
							<a  class="btn btn-info" @click.prevent="toggleArchive"><i class="fa fa-reply"></i> 返回档案首页</a>
								<fieldset>
								<legend>一般状况</legend>
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label>体温</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.temprature">
												<span class="input-group-addon">℃</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>脉率</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.pulse">
												<span class="input-group-addon">次/分钟</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>呼吸频率</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.breathe">
												<span class="input-group-addon">次/分钟</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>左侧血压</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.bloodpressure_left">
												<span class="input-group-addon">mmHg</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>右侧血压</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.bloodpressure_right">
												<span class="input-group-addon">mmHg</span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label>身高</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.height">
												<span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>体重</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.weight">
												<span class="input-group-addon">kg</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>腰围</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.common.waistline">
												<span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>体质指数</label>
											<div class="input-group">
												<input type="text" class="form-control" :value="bmi" readonly>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>辅助检查</legend>
								
								<h4><i class="fa fa-plus-square"></i> 血常规</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>血红蛋白</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.blood.hemoglobin">
												<span class="input-group-addon">g/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>白细胞</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.blood.leukocyte">
												<span class="input-group-addon">×10<sup>9</sup>/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>血小板</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.blood.platelet">
												<span class="input-group-addon">×10<sup>9</sup>/L</span>
											</div>
										</div>
									</div>
								</div>
								<h4><i class="fa fa-plus-square"></i> 尿常规</h4>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label>尿蛋白</label>
												<div class="radio-primary clip-radio">
												<input type="radio" value="0" name="up0" id="up0" v-model="currentArchive.data.aid.urine.protein">
												<label for="up0">阴性</label>
												<input type="radio" value="1" name="up1" id="up1" v-model="currentArchive.data.aid.urine.protein">
												<label for="up1">阳性</label>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>尿糖</label>
												<div class="radio-primary clip-radio">
												<input type="radio" value="0" name="us0" id="us0" v-model="currentArchive.data.aid.urine.sugar">
												<label for="us0">阴性</label>
												<input type="radio" value="1" name="us1" id="us1" v-model="currentArchive.data.aid.urine.sugar">
												<label for="us1">阳性</label>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>尿酮体</label>
												<div class="radio-primary clip-radio">
												<input type="radio" value="0" name="uk0" id="uk0" v-model="currentArchive.data.aid.urine.ketone">
												<label for="uk0">阴性</label>
												<input type="radio" value="1" name="uk1" id="uk1" v-model="currentArchive.data.aid.urine.ketone">
												<label for="uk1">阳性</label>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>尿潜血</label>
												<div class="radio-primary clip-radio">
												<input type="radio" value="0" name="uo0" id="uo0" v-model="currentArchive.data.aid.urine.occult">
												<label for="uo0">阴性</label>
												<input type="radio" value="1" name="uo1" id="uo1" v-model="currentArchive.data.aid.urine.occult">
												<label for="uo1">阳性</label>
											</div>
										</div>
									</div>
								</div>
								<h4><i class="fa fa-plus-square"></i> 常规检测</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>空腹血糖</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.blood_sugar">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>心电图</label>
											<div class="radio-primary clip-radio">
											<input type="radio" value="1" name="ecg" id="a1" v-model="currentArchive.data.aid.ecg">
											<label for="a1">正常</label>
											<input type="radio" value="0" name="ecg" id="a2" v-model="currentArchive.data.aid.ecg">
											<label for="a2">异常</label>
										</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>B超</label>
											<div class="radio-primary clip-radio">
											<input type="radio" value="1" name="bscan" id="b1" v-model="currentArchive.data.aid.bscan">
											<label for="b1">正常</label>
											<input type="radio" value="0" name="bscan" id="b2" v-model="currentArchive.data.aid.bscan">
											<label for="b2">异常</label>
										</div>
										</div>
									</div>
								</div>
								<h4><i class="fa fa-plus-square"></i> 肝功能</h4>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label>血清谷丙转氨酶</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.liver.alt">
												<span class="input-group-addon">U/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>血清谷草转氨酶</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.liver.ast">
												<span class="input-group-addon">U/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>白蛋白</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.liver.alb">
												<span class="input-group-addon">g/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>总胆红素</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.liver.tbl">
												<span class="input-group-addon">μ mol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>结合胆红素</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.liver.bl">
												<span class="input-group-addon">μ mol/L</span>
											</div>
										</div>
									</div>
								</div>
								<h4><i class="fa fa-plus-square"></i> 肾功能</h4>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label>血清肌酐</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.kidney.scr">
												<span class="input-group-addon">μ mol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>血尿素氮</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.kidney.bun">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>血钾浓度</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.kidney.ka">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>血钠浓度</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.kidney.na">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
								</div>
								<h4><i class="fa fa-plus-square"></i> 血脂</h4>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label>总胆固醇</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.lipids.tc">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>甘油三酯</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.lipids.trig">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>血清低密度脂蛋白</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.lipids.ldl">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>血清高浓度脂蛋白</label>
											<div class="input-group">
												<input type="text" class="form-control" v-model="currentArchive.data.aid.lipids.hdl">
												<span class="input-group-addon">mmol/L</span>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<div class="row">
							<div class="col-md-6">
								<a  class="btn btn-info" @click.prevent="toggleArchive"><i class="fa fa-reply"></i> 返回档案首页</a>
							</div>
								<div class="col-md-6 ">
									<button class="btn btn-success btn-lg pull-right" @click.prevent = "checkResult">生成诊断报告</button>
									<button class="btn btn-danger btn-lg pull-right" style="margin-right: 10px">删除该记录</button>
								</div>
								<!-- <div class="col-md-2">
								</div> -->
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<modal :show="showModal" :title="pop.name + '的诊断报告'"  v-on:cancel="toggleModal">
			<slot>
				<div class="box box-primary">
				              <div><p class="text-muted pull-right">由{{currentArchive.check_unit}} {{currentArchive.doctor}} 于 {{currentArchive.created_at}} 创建</p></div>
				            <!-- /.box-header -->
				            <div class="box-body">
				              <strong><i class="fa fa-heartbeat margin-r-5"></i> 基本状况</strong>
				              <p class="text-muted">
				              	体温：{{currentArchive.data.common.temprature}} ℃ 脉率：{{currentArchive.data.common.pulse}}次/分钟
								呼吸频率：{{currentArchive.data.common.breathe}}次/分钟 左侧血压：{{currentArchive.data.common.bloodpressure_left}}mmHg
								右侧血压：{{currentArchive.data.common.bloodpressure_right}}mmHg 身高：{{currentArchive.data.common.height}}cm
								体重：{{currentArchive.data.common.weight}}kg 腰围：{{currentArchive.data.common.waistline}}cm
								体质指数：{{bmi}}
				              </p>
				              <hr>

				              <strong><i class="fa fa-universal-access margin-r-5"></i> 辅助检查</strong>
				              <p class="text-muted">
				              	
				              </p>
				              <hr>

				              <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

				              <p>
				                <span class="label label-danger">UI Design</span>
				                <span class="label label-success">Coding</span>
				                <span class="label label-info">Javascript</span>
				                <span class="label label-warning">PHP</span>
				                <span class="label label-primary">Node.js</span>
				              </p>

				              <hr>

				              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

				              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
				            </div>
				            <!-- /.box-body -->
				          </div>
				</slot>
			</modal>
	</div>
</template>
<script>
	import Modal from '../../components/Modal'
	import {calculateBmi, checkData} from '../../utils'
	import {PAY_TYPE} from '../../config'
	export default {
		data () {
			return {
				showModal: false,
				pop: {
					paytype: '',
					archive: {}
				},
				pidInfo: {},
				pidValid: false,
				paytype:  PAY_TYPE,
				showArchive: false,
				currentArchive: {
					data: {
						aid: {
							blood: {},
							kidney: {},
							lipids: {},
							liver: {},
							urine: {}
						},
						common: {}
					}
				},
				result: {}
			}
		},
		computed: {
			bmi () {
				return calculateBmi(this.currentArchive.data.common.weight, this.currentArchive.data.common.height)
			}
		},
		watch: {
			'$route': 'loadData'
		},
		mounted () {
			this.loadData()
		},
		methods: {
			loadData () {
				this.$store.commit('loading')
				axios.get('pop/' + this.$route.params.id + '/edit')
				.then((response) => {
					this.pop = response.data.data
					this.onCheckPid()
					this.$store.commit('loaded')
				})
				.catch((error) => {
					toastr.error(error)
					this.$store.commit('loaded')
				})
			},
			checkResult () {
				this.result = checkData (this.currentArchive.data.aid)
				this.toggleModal()
			},
			toggleModal () {
				this.showModal = !this.showModal
			},
			onCheckPid () {
					// let info = checkPid(this.pop.identify)
					this.$store.commit('loading')
					axios.get('checkPid', {
					  params: { pid: this.pop.identify }
					})
					        .then((response) => {
					          let result = response.data.data
					          if (_.has(result, 'birthday')) {
					          	this.pidInfo = result
					          	this.pidValid = true
					          	this.$store.commit('loaded')
					          } else {
					          	this.pidInfo = { birthday: '1900-01-01', area: '身份证非法', gender: 0 }
					          	this.pidValid = false
					          	this.$store.commit('loaded')
					          }
					        })
					        .catch((error) => {
					        	this.$store.commit('loaded')
					          return error.response
					        })
					// if (_.has(checkPid(this.pop.identify), 'birthday')) {
					// 	this.pidInfo = checkPid(this.pop.identify)
					// 	window.console.log('adsf')
					// } else {
					// 	this.pidInfo = { birthday: '身份证非法', area: '身份证非法', gender: 0 }
					// }
			},
			saveUserInfo () {
				let pop = _.omit(this.pop, 'archive')
				this.submitData (pop)
			},
			submitData (data) {
				axios.put('pop/' + this.$route.params.id, data)
				.then(() => {
					toastr.success('用户资料修改成功')
					this.$router.push({path: '/dashboard/pops'})
				})
				.catch((error) => {
					swal('出错了', _.values(error.response.data)[0], 'error')
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
				this.$store.commit('loading')
				axios.delete('pop/' + this.$route.params.id)
				.then(() => {
					toastr.success('用户资料修改成功')
					this.$router.push({path: '/dashboard/pops'})
					this.$store.commit('loaded')
				})
				.catch((error) => {
					swal('出错了', _.values(error.response.data)[0], 'error')
				})
			},
			view_archive (index) {
				this.toggleArchive()
				this.currentArchive = this.pop.archive[index]
			},
			del_archive () {
				window.console.log('deleted')
			},
			toggleArchive () {
				this.showArchive = !this.showArchive
			},
			showResult () {

			}
		},
		components: {
			Modal
		}
	}
</script>
<style>
	.box-body span {
		margin: 0 5px;
	}
</style>