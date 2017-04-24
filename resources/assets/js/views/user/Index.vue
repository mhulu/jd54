<template>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="row box-header">
					<div class="col-md-3">
						<filterbar></filterbar>
					</div>
					<div class="col-md-9 right">
						<router-link to="/dashboard/users/create" class="btn btn-success"><i class="fa fa-user-plus"></i> 创建用户</router-link>
						<button @click.prevent = "exportExcel" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> 导出表格</button>
					</div>
				</div>
				<div class="box-body">
					<vuetable ref="vuetable"
					api-url="user"
					:fields="fields"
					:append-params="moreParams"
					@vuetable:cell-clicked="onCellClicked"
					@vuetable:pagination-data="onPaginationData"
					>
	</vuetable>
</div>
<div class="box-footer no-padding">
<div class="col-md-6">
	<vuetable-pagination-info ref="paginationInfo"
	     ></vuetable-pagination-info>
</div>
<div class="col-md-6">
		<vuetable-pagination ref="pagination"
	      @vuetable-pagination:change-page="onChangePage"
	    ></vuetable-pagination>
</div>
</div>
</div>
</div>
</div>
</template>

<script>
	import Vuetable from '../../components/Table/Table'
	import VuetablePagination from '../../components/Table/TablePagination'
	import VuetablePaginationInfo from '../../components/Table/TablePaginationInfo'
	import Filterbar from '../../components/Table/TableFilterBar'
	export default {
		data () {
			return {
				fields: [
				{
					name: '__checkbox',
					titleClass: 'text-center',
					dataClass: 'text-center',
				},
				{
					name: 'name',
					title: '姓名'
				},
				{
					name: 'email',
					title: '邮箱'
				},
				{
					name: 'mobile',
					title: '手机号码'
				},
				{
					name: 'unit',
					title: '所属部门'
				},
				{
					name: 'status',
					sortField: 'status',
					title: '用户状态',
					titleClass: 'text-center',
					dataClass: 'text-center',
					callback: 'statusLabel'
				},
				{
					name: 'last_login',
					sortField: 'last_login',
					title: '上次登录',
					titleClass: 'text-center',
					dataClass: 'text-center'
				},
				],
				sortOrder: [
				{
					field: 'id',
					sortField: 'id',
					direction: 'asc'
				}
				],
				moreParams: {}
			}
		},
		methods: {
			statusLabel (value) {
				return value === 1
				? '<span class="label label-success"><i class="fa fa-check-circle"></i> 正常</span>'
				: '<span class="label label-warning"><i class="fa fa-times-circle"></i> 冻结</span>'
			},
			onCellClicked (data) {
				this.$router.push('/dashboard/users/' + data.id + '/edit')
			},
			onPaginationData (paginationData) {
				this.$refs.pagination.setPaginationData(paginationData)
				this.$refs.paginationInfo.setPaginationData(paginationData)
			},
			onChangePage (page) {
				this.$refs.vuetable.changePage(page)
			},
			onAction (action, data) {
				if (action === 'edit-item') {
					this.$router.push('/dashboard/users/' + data.id + '/edit')
				}
			},
			onFilterSet (filterText) {
				this.moreParams = {
					'filter': filterText
				}
				Vue.nextTick(() => this.$refs.vuetable.refresh())
			},
			onFilterReset () {
				Vue.nextTick(() => this.$refs.vuetable.refresh())
			},
			exportExcel () {
				this.$store.commit('loading')
				axios.post('users/excel', {title: 'title'})
					.then((response) => {
						window.location.assign(response.data.data.url)
						this.$store.commit('loaded')
					})
					.catch((error) => {
						window.console.log(error.response)
					})
			}
		},
		mounted () {
			this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
			this.$events.$on('filter-reset', e => this.onFilterReset())
		},
		components: {
			Vuetable,
			VuetablePagination,
			VuetablePaginationInfo,
			Filterbar
		}
	}
</script>