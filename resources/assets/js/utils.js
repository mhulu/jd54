import {RANGE} from './config'

export function calculateBmi (weight, height) {
	let h = height/100
	let bmi = parseInt(weight / (h * h))
	if (bmi < 18) {
		return bmi + '(偏瘦)'
	} else if (bmi > 28) {
		return bmi + '(肥胖)'
	} else {
		return bmi + '(正常)'
	}
}

export function checkData (data) {

	var diff = _.difference(_.keys(RANGE), _.keys(data))
	return _.mergeWith(_.cloneDeep(data), _.omit(RANGE, diff), function(d, r) {
	  if(!_.isObject(d)) {
	    return _(r).split(',').map(_.trim).map(Number)
	      .thru(_.spread(function(x, y) {
	      	// 0为正常 1偏高 -1偏低
	      if(d>y){
	      return d + '(↑)'
	      } else if (d < x) {
	      return d + '(↓)'
	      } else {
	      return d
	      }
	      })).value()
	  }
	})
}