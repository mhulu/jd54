<template>
	<div class="input-group">
		<input type="text" class="form-control" placeholder="搜索..." v-model="filterText" @keyup.enter="doFilter">
		<span class="input-group-btn">
			<button class="btn btn-default" type="button"@click="doFilter"><i class="fa fa-search"></i></button>
			<button class="btn btn-default" type="button" @click="resetFilter"><i class="fa fa-refresh"></i></button>
		</span>
	</div>
</template>

<script>
	export default {
		data () {
			return {
				filterText: ''
			}
		},
		methods: {
			doFilter () {
				this.$events.fire('filter-set', this.filterText)
			},
			resetFilter () {
				this.filterText = ''
				this.$events.fire('filter-reset')
			}
		}
	}
</script>