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
						<a href="#" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> 导出表格</a>
					</div>
				</div>
				<div class="box-body">
					<vuetable ref="vuetable"
					api-url="pop"
					:fields="fields"
					@vuetable:cell-clicked="onCellClicked"
					:append-params="moreParams"
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
					name: 'id',
					title: "#",
					titleClass: 'text-center',
					dataClass: 'text-center',
					sortField: 'id'
				},
				{
					name: 'name',
					title: '姓名'
				},
				{
					name: 'identify',
					title: '身份证'
				},
				{
					name: 'phone',
					title: '电话号码'
				},
				{
					name: 'livetype',
					title: '户籍类型',
					titleClass: 'text-center',
					dataClass: 'text-center',
					callback: 'livetypeLabel'
				},
				{
					name: 'sex',
					sortField: 'sex',
					title: '性别',
					titleClass: 'text-center',
					dataClass: 'text-center',
					callback: 'sexLabel'
				},
				{
					name: 'birthday',
					sortField: 'birthday',
					title: '生日',
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
			livetypeLabel (value) {
				return value === 1
				? '<span class="label label-success">常驻</span>'
				: '<span class="label label-warning">非常驻</span>'
			},
			sexLabel (value) {
				return value === 1
				? '<span class="label label-primary"><i class="fa fa-mars"></i> 男</span>'
				: '<span class="label label-danger"><i class="fa fa-venus"></i> 女</span>'
			},
			onCellClicked (data) {
				this.$router.push('/dashboard/pops/' + data.id + '/edit')
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
					this.$router.push('/dashboard/pops/' + data.id + '/edit')
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