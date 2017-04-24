<template>
	<!-- START TIMELINE -->
	<p v-if="timeline.length === 0">您最近无活动事件</p>
	<ul class="timeline timeline-inverse" v-else>
		<template  v-for="(events, key, index) in timeline">
			<!-- timeline time label -->
			<li class="time-label">
				<span :class="'bg-' + colors[index%colors.length]">
					{{key}}
				</span>
			</li>
			<!-- /.timeline-label -->
			<!-- timeline item -->
			<li v-for="event in events">
				<i class="fa bg-blue" :class="'fa-' + icons[event.type]"></i>
				<div class="timeline-item">
					<!-- <span class="time"><a class="btn btn-default btn-xs" v-on:click.stop.prevent="onDelete(event.id)"><i class="fa fa-search-plus"></i></a></span> -->
					<span class="time"><i class="fa fa-clock-o"></i> {{event.time}}</span>
					<h3 class="timeline-header no-border" :class="'bg-' + event.color">{{event.title}}</h3>
					<div v-show="event.content !== undefined && event.content !== ''" class="timeline-body">
						<p><i class="fa fa-clock-o"></i> 操作时间：{{event.time}}</p>
						<p><i class="fa fa-map-marker"></i> {{event.content}}</p>
					</div>
				</div>
			</li>
		</template>
	</ul>
	<!-- END TIMELINE -->
</template>
<script>
	import { COLOR_BG, ICON_MAP } from '../config'
	export default {
		data () {
			return {
				timeline: {},
				colors: COLOR_BG,
				icons: ICON_MAP
			}
		},
		methods: {
			// onDelete (id) {
			// 	axios.delete('home/notification')
			// 	.then( function (response) {
			// 		toastr.success(response.data.data)
			// 		this.loadData()
			// 	})
			// 	.catch( function (error) {
			// 		toastr.error(response.data.result)
			// 	})
			// },
			loadData () {
				this.$store.commit('loading')
				axios.get('home/timeline')
					.then((response) => {
						this.timeline = response.data
						this.$store.commit('loaded')
					})
					.catch((error) => {
						toastr.error(error)
					})
			}
		},
		created () {
			this.loadData()
		}
	}
</script>