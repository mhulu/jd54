	export const API_ROOT = (process.env.NODE_ENV === 'production')
		? 'http://jd.wemesh.cn/api/'
		: 'http://jd54.dev/api/'

	export const COLOR_BG = [
		'aqua', 'yellow', 'maroon', 'orange', 'purple', 'olive'
	]
	export const COLOR_BS = [
		'danger', 'success', 'info', 'warning', 'primary'
	]

	export const COLOR_SB = [
		'primary', 'warning', 'info', 'success', 'danger'
	]

	export const ICON_MAP = {
		'user': 'user',
		'post': 'edit'
	}

	export const PAY_TYPE = [
		'城镇职工基本医疗保险', '城镇居民基本医疗保险', '新型农村合作医疗', '贫困救助', '商业医疗保险', '全公费', '全自费'
	]

	export const RANGE = {
		blood: {
			hemoglobin: '110, 150',
			leukocyte: '4, 10',
			platelet: '100, 300'
		},
		blood_sugar: '3.89, 6.1',
		liver: {
			alt: '0, 40',
			ast: '0, 40',
			alb: '40, 55',
			tbl: '5, 19',
		},
		kidney: {
			scr: '45, 84'
		}
	}
