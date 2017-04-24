<template>
	<div class="row">
		<div :class="[{'col-xs-12': true}, loading]">
			<div class="box">
				<div class="box-header with-border">
					<div class="col-md-4">
						<button type="button" class="btn btn-danger btn-sm" @click.prevent="onClear"><i class="fa fa-trash-o"></i> 全部清空</button>
						<button type="button" class="btn btn-default btn-sm" @click.prevent="reload"><i class="fa fa-refresh"></i></button>
						<!-- <button type="button" class="btn btn-success btn-sm" @click.prevent="onMarkRead"><i class="fa  fa-check-square-o"></i> 标记为已读</button> -->
					</div>

					<!-- <div class="col-md-3 pull-right">
						<filterbar></filterbar>
					</div> -->
				</div>
				<div class="box-body">
					<vuetable ref="vuetable"
					api-url="home/notification"
					paginationPath=""
					:fields="fields"
					@vuetable:pagination-data="onPaginationData"
					@vuetable:cell-clicked="onCellClicked"
					@vuetable:loading="showLoader"
					@vuetable:loaded="hideLoader"
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
<modal :show="showModal" title="消息详情" v-on:cancel="toggleModal">
	<slot>
		<div class="box-body no-padding">
			<div class="mailbox-read-info">
				<h3>{{detail.data.title}}</h3>
				<h5>来自: {{detail.type}}
					<span class="mailbox-read-time pull-right">{{detail.created_at}}</span></h5>
				</div>
				<!-- /.mailbox-read-info -->
				<div class="mailbox-read-message" v-show="detail.data.content !== ''">
					<p>{{detail.data.content}}</p>
				</div>
				<!-- /.mailbox-read-message -->
				<div class="mailbox-controls text-center">
					<button type="button" class="btn btn-danger btn-sm" @click.prevent="onDelete(detail.id)">
							<i class="fa fa-trash"></i> 删除
						</button>
						<!-- <button type="button" class="btn btn-info btn-sm" @click.prevent="reload">
								<i class="fa fa-home"></i> 返回
							</button> -->
				</div>
			</div>
		</slot>
	</modal>
</div>
</template>

<script>
	import Vuetable from '../components/Table/Table'
	import VuetablePagination from '../components/Table/TablePagination'
	import VuetablePaginationInfo from '../components/Table/TablePaginationInfo'
	// import Filterbar from '../components/Table/TableFilterBar'
	import Modal from '../components/Modal'	
	export default {
		data () {
			return {
				fields: [
				// {
				// 	name: '__checkbox',
				// 	titleClass: 'text-center',
				// 	dataClass: 'text-center',
				// },
				{
					name: 'type',
					title: '消息来源',
					titleClass: 'text-center',
					dataClass: 'text-center'
				},
				{
					name: 'data',
					title: '消息标题',
					callback: 'titleStyle'
				},
				{
					name: 'created_at',
					title: '发送时间'
				},
				{
					name: 'read_at',
					title: '阅读状态',
					titleClass: 'text-center',
					dataClass: 'text-center',
					callback: 'readState'
				},
				],
				detail: {
					data: {}
				},
				// selectedTo: [],
				showModal: false,
				loading: '',
				currentCellIndex: 0
			}
		},
		methods: {
			titleStyle (value) {
				return value.type === 'danger' 
								? value.title + ' <i class="fa fa-exclamation-triangle text-danger"><i>'
								: value.title
			},
			readState (value) {
				return value ? '<span class="label label-default">已读</span>' : '<span class="label label-success">未读</span>'
			},
			showLoader () {
				this.loading = 'loading'
			},
			hideLoader: function() {
			  this.loading = ''
			},
			onPaginationData (paginationData) {
				this.$refs.pagination.setPaginationData(paginationData)
				this.$refs.paginationInfo.setPaginationData(paginationData)
			},
			onChangePage (page) {
				this.$refs.vuetable.changePage(page)
			},
			// onFilterSet (filterText) {
			// 	this.moreParams = {
			// 		'filter': filterText
			// 	}
			// 	Vue.nextTick(() => this.$refs.vuetable.refresh())
			// },
			// onFilterReset () {
			// 	this.moreParams = {}
			// 	Vue.nextTick(() => this.$refs.vuetable.refresh())
			// },
			onCellClicked (data, index) {
				this.showDetail(data)
				this.currentCellIndex = index
				if (!data.read_at) {
					data.read_at = true
					axios.post('home/notification/mark', { ids: data.id})
								.catch((error) => {
									toastr.error(error)
								})
				}
			},
			onClear () {
				axios.post('home/notification/clear', {id: window.Wemesh.id})
					.then((response) => {
						this.reload()
					})
					.catch((error) => {
						toastr.error(error)
					})
			},
			onDelete (id) {
				axios.post('home/notification/delete', { ids: id })
							.then((response) => {
								this.reload()
								this.toggleModal()
							})
							.catch((error) => {
								toastr.error(error)
							})
			},
			// onMarkRead () {
			// 	let that = this
			// 	axios.post('notification/mark', { ids: that.selectedTo, id: window.Wemesh.id })
			// 				.then((response) => {
			// 					window.location.reload()
			// 				})
			// 				.catch((error) => {
			// 					toastr.error(error)
			// 				})
			// },
			reload () {
				this.$refs.vuetable.refresh()
			},
			// onChecked (selectedTo) {
			// 	this.selectedTo = selectedTo
			// },
			showDetail (data) {
				this.toggleModal()
				this.detail = data
			},
			toggleModal () {
				this.showModal = !this.showModal
			},

		},
		// mounted () {
		// 	this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
		// 	this.$events.$on('filter-reset', e => this.onFilterReset())
		// },
		components: {
			Vuetable,
			VuetablePagination,
			VuetablePaginationInfo,
			// Filterbar,
			Modal
		}
	}
</script>
<style>
	.text-info {
		color: #0ca7f3;
	}
	.text-success {
		color: #60b149;
	}
	.text-danger {
		color: #d83b37;
	}
</style>