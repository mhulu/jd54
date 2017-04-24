<template>
<div>
	<input type="text" class="form-control" ref="el" :value = "date" v-on:input="update($event.target.value)"  id="datepicker" placeholder="输入日期格式" required>
</div>
</template>

<script>
	require ('bootstrap-datepicker')
	export default {
		props: ['date'],
		mounted () {
			this.showPicker()
		},
		methods: {
			update (value) {
				this.$refs.el.value = value
				this.$emit('input', value)
			},
			showPicker () {
				$('#datepicker').datepicker({
					'format': 'yyyy-mm-dd',
					'orientation': 'bottom',
					'autoclose': true
				}).on('changeDate', () => {
					this.update(document.getElementById('datepicker').value)
				})
			}
		}
	}
</script>