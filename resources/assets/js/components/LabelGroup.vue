<template>
	<p>
		<span v-for="(label, index) in lists" class="label" :class="'label-' + colors[index%colors.length]">
			{{label.label}}
		</span>
	</p>
</template>
<script>
	import { COLOR_SB } from '../config'
	export default {
		data () {
			return {
				colors: COLOR_SB
			}
		},
		props: {
			lists: Array
		}
	}
</script>