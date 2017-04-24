<template>
		<section class="content">
			<div class="row">
			        <div class="col-lg-3 col-xs-6">
			          <!-- small box -->
			          <div class="small-box bg-aqua">
			            <div class="inner">
			              <h3>{{statistics.users}} 人</h3>
			              <p>系统人数</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-user"></i>
			            </div>
			          </div>
			        </div>
			        <!-- ./col -->
			        <div class="col-lg-3 col-xs-6">
			          <!-- small box -->
			          <div class="small-box bg-green">
			            <div class="inner">
			              <h3>{{statistics.units}} 个</h3>
			              <p>部门总数</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-group"></i>
			            </div>
			          </div>
			        </div>
			        <!-- ./col -->
			        <div class="col-lg-3 col-xs-6">
			          <!-- small box -->
			          <div class="small-box bg-yellow">
			            <div class="inner">
			              <h3>{{statistics.pops}} 人</h3>
			              <p>流动人口数量</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-street-view"></i>
			            </div>
			          </div>
			        </div>
			        <!-- ./col -->
			        <div class="col-lg-3 col-xs-6">
			          <!-- small box -->
			          <div class="small-box bg-red">
			            <div class="inner">
			              <h3>{{statistics.aged}} 人</h3>

			              <p>老年人口数量</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-blind"></i>
			            </div>
			          </div>
			        </div>
			        <!-- ./col -->
			      </div>
		</section>
</template>
<script>
	export default {
		data () {
			return {
				statistics: {}
			}
		},
		created () {
			this.fetchData()
		},
		watch: {
			'$route': 'fetchData'
		},
		methods: {
			fetchData () {
				this.$store.commit('loading')
				axios.get('home/statistics')
				.then((response) => {
					this.statistics = response.data
					this.$store.commit('loaded')
				})
			}
		}
	}
</script>

