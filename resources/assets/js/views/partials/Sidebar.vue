<template>
	<aside class="main-sidebar">
		<section class="sidebar">
			<div class="user-panel">
				<div class="pull-left image">
					<img :src="profile.avatar" class="img-circle" alt="User Image">
				</div>
				<div class="pull-left info">
					<p>
						{{userInfo.name}}
					</p>
					<small>[ {{profile.nickname}} ]</small>
				</div>
			</div>
			<!-- search form -->
			<!-- <form action="#" method="get" class="sidebar-form">
				<div class="input-group">
					<input type="text" name="q" class="form-control" placeholder="Search...">
					<span class="input-group-btn">
						<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</form> -->
			<div>
				<p v-if="noWeather" class="weather">正在获取天气信息... <a class="btn" @click="fetchWeather"><i class="fa fa-refresh"></i></a></p>
				<p v-else class="weather">{{weather.date}} {{weather.week}} {{weather.weather}} <a class="btn" @click="fetchWeather"><i class="fa fa-refresh"></i></a></p>
			</div>
			<ul class="sidebar-menu">
						<li class="treeview"
						 v-if="typeof item.children !== 'undefined'" v-for="item in menu"
						 :class="{ 'active': hasRoute(item.path) }">
						<a href="">
							<i class="fa" :class="'fa-' + item.icon"></i> <span>{{item.name}}</span>
							<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">
							<router-link :to="{path: child.path, name: child.name}"  tag="li"  v-for="child in item.children">
								<a>{{child.name}}</a>
							</router-link>
						</ul>
						</li>
							<router-link :to="{path: item.path, name: item.name}"  tag="li" v-else>
								<a><i class="fa" :class="'fa-' + item.icon"></i> {{item.name}}</a>
							</router-link>
			</ul>
		</section>
		<!-- /.sidebar -->
	</aside>
</template>

<script>
	import { mapGetters } from 'vuex'
	export default {
		data () {
		    return {
		        menu: {},
		        noWeather: true
		    }
		},
		methods: {
			hasRoute (partial) {
			    return (this.$route.path.indexOf(partial) > -1)
			},
			fetchWeather () {
				this.noWeather = true
				axios.get('weather')
							.then((response) => {
								this.$store.commit('weather', response.data.result)
								this.noWeather = !this.noWeather
							})
							.catch((error) => {
								this.hasWeather = false
								toastr.error('无法连接天气服务器：' + error)
							})
			}
		},
		created () {
			// let that = this
			axios.get('home/menu')
						.then((response) => {
							this.menu = response.data.data
						})
						.catch((error) => {
							toastr.error('获取菜单错误：' + error)
						})
			this.fetchWeather()
		},
		computed: {
		  weather () {
		  	return this.$store.getters.getWeather
		  },
		  userInfo () {
		    return this.$store.getters.getUserInfo
		  },
		  profile () {
		    return this.$store.getters.getProfile
		  }
		},
	}
</script>
<style>
	.weather {
		color: #c3c3c3;
		text-align: center;
		background-color: #344048;
		padding: 8px 0;
	}
</style>