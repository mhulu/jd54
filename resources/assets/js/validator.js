export function required (val) {
  if (Array.isArray(val)) {
    if (val.length !== 0) {
      let valid = true
      for (let i = 0, l = val.length; i < l; i++) {
        valid = required(val[i])
        if (!valid) {
          break
        }
      }
      return valid
    } else {
      return false
    }
  } else if (typeof val === 'number' || typeof val === 'function') {
    return true
  } else if (typeof val === 'boolean') {
    return val
  } else if (typeof val === 'string') {
    return val.length > 0
  } else if (val !== null && typeof val === 'object') {
    return Object.keys(val).length > 0
  } else if (val === null || val === undefined) {
    return false
  }
}

/**
 * minlength
 *
 * This function validate whether the minimum length.
 *
 * @param {String|Array} val
 * @param {String|Number} min
 * @return {Boolean}
 */

export function minlength (val, min) {
  if (typeof val === 'string') {
    return isInteger(min, 10) && val.length >= parseInt(min, 10)
  } else if (Array.isArray(val)) {
    return val.length >= parseInt(min, 10)
  } else {
    return false
  }
}
/**
 * maxlength
 *
 * This function validate whether the maximum length.
 *
 * @param {String|Array} val
 * @param {String|Number} max
 * @return {Boolean}
 */

export function maxlength (val, max) {
  if (typeof val === 'string') {
    return isInteger(max, 10) && val.length <= parseInt(max, 10)
  } else if (Array.isArray(val)) {
    return val.length <= parseInt(max, 10)
  } else {
    return false
  }
}
/**
 * min
 *
 * This function validate whether the minimum value of the numberable value.
 *
 * @param {*} val
 * @param {*} arg minimum
 * @return {Boolean}
 */

export function min (val, arg) {
  return !isNaN(+(val)) && !isNaN(+(arg)) && (+(val) >= +(arg))
}

/**
 * max
 *
 * This function validate whether the maximum value of the numberable value.
 *
 * @param {*} val
 * @param {*} arg maximum
 * @return {Boolean}
 */

export function max (val, arg) {
  return !isNaN(+(val)) && !isNaN(+(arg)) && (+(val) <= +(arg))
}
/**
 * isInteger
 *
 * This function check whether the value of the string is integer.
 *
 * @param {String} val
 * @return {Boolean}
 * @private
 */

function isInteger (val) {
  return /^(-?[1-9]\d*|0)$/.test(val)
}

export function isMobile (val) {
  return /^1[3|4|5|7|8]\d{9}$/.test(val)
}
export function isChinese (val) {
  return /^[\u4e00-\u9fa5]+$/.test(val)
}
export function isPostcode (val) {
  return /^[0-9]{6}$/.test(val)
}
export function isEmail (val) {
  return /\S+@\S+\.\S+/.test(val)
}
export function isDate (val) {
  return /^\d{4}-\d{2}-\d{2}$/.test(val)
}
export function isPhone (val) {
  return /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/.test(val)
}
export function isIdentify (val) {
  return /^\d{6}(((19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])\d{3}([0-9]|x|X))|(\d{2}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])\d{3}))$/.test(val)
}
export function parseIdentify (id) {
  if (!isIdentify(id)) {
    return null
  }
  var parse = function (id, sexStr, birthStr) {
    var res = {}
    var sex = 1 - id.substr(sexStr, 1) % 2
    res.sex = sex === '1' ? '女' : '男'

    var year = (birthStr === 2 ? '19' : '') + id.substr(6, birthStr)
    var month = id.substr(6 + birthStr, 2)
    var day = id.substr(8 + birthStr, 2)
    res.birthday = year + '-' + month + '-' + day
    return res
  }
  return parse(id, id.length === 15 ? 14 : 16, id.length === 15 ? 2 : 4)
}
