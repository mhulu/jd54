<template>
	<div>
		<top-navbar></top-navbar>
		<sidebar></sidebar>
		<div class="content-wrapper">
		    <section class="content-header">
		        <h4>{{$route.name}}</h4>
		        <breadcrumbs></breadcrumbs>
		    </section>
			<section class="content" :class="{loading: loading}">
				<transition name="fade" mode="out-in">
					<router-view></router-view>
				</transition>
			</section>
		</div>
		
	</div>
</template>

<script>
	import TopNavbar from './partials/Header'
	import Sidebar from './partials/Sidebar'
	import Breadcrumbs from '../components/Breadcrumbs'
	export default {
		computed: {
			loading () {
				return this.$store.state.loading.fired
			}
		},
		mounted () {
			this.$store.commit('loading')
			axios.get('home')
						.then((response) => {
							this.$store.commit('userInfo', response.data.data)
							this.$store.commit('loaded')
						})
						.catch((error) => {
							swal('出错了', '无法获取数据，请刷新页面或者联系管理员', 'error')
						})
		},
		components: {
			TopNavbar,
			Sidebar,
			Breadcrumbs
		}
	}
</script>