<script>
	import { calculateBmi } from '../../utils'
	import { PAY_TYPE } from '../../config'

	export default {
		data () {
			return {
				pop: {
					paytype: '',
					archives: {}
				},
				pidInfo: {},
				pidIsValidi: false,
				paytype: PAY_TYPE,
				currentArchive: {
					result: {
						aid: {
							blood: {},
							kidney: {},
							lipids: {},
							liver: {},
							urine: {}
						},
						common: {}
					}
				}
			}
		},
		computed: {
			bmi () {
				return calculateBmi(this.currentArchive.result.common.weight, this.currentArchive.result.common.height)
			}
		},
		watch: {
			'$router': 'loadData'
		},
		created () {
			this.loadData()
		},
		methods: {
			loadData () {
				this.$store.commit('loading')
				// axios.get('pop/' + this.$route.params.id + '/edit')
				// .then((response) => {
				// 	this.pop = response.data.data
				// 	this.checkPid ()
				// 	this.$store.commit('loaded')
				// })
				// .catch((error) => {
				// 	swal('出现错误', _.values(error.response.data)[0], 'error')
				// })
			}
		}
	}
</script>