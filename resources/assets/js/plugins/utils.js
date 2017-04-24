import _ from 'lodash'

/**
 * 从对象中取出第一条
 */
export function findFirst (obj) {
  for (var k in obj) {
    if (obj[k].length) {
      return obj[k][0]
    }
  }
}
export function findObj (obj) {
  for (var k in obj) {
    if (obj[k].length) {
      return obj[k]
    }
  }
}

/**
 * 从当前的url中提取指定部分名称
 * @param 传入的路径,得有/.../这种斜杠喔
 * @param  {int} index [位置,通常0为父级]
 */
export function getPartFromPath (path = window.location.href, index = 0) {
  var re = /\/\w+/g
  var route = path.match(re)[index]
  return route.split('/').pop()
}

export function hasClass (el, className) {
  return el.classList ? el.classList.contains(className) : new RegExp('\\b' + className + '\\b').test(el.className)
}

export function addClass (el, className) {
  if (el.classList) el.classList.add(className)
  else if (!hasClass(el, className)) el.className += ' ' + className
}

export function removeClass (el, className) {
  if (el.classList) el.classList.remove(className)
  else el.className = el.className.replace(new RegExp('\\b' + className + '\\b', 'g'), '')
}

export function shake (el) {
  el.classList.add('shake')
  setTimeout(() => {
    el.classList.remove('shake')
  }, 650)
}

export function toggle (el) {
  var display = (window.getComputedStyle ? window.getComputedStyle(el, null) : el.currentStyle).display
  if (display === 'none') {
    el.style.display = 'block'
  } else {
    el.style.display = 'none'
  }
}

export function hasId (el) {
  var element = document.getElementById(el)
  return typeof (element) !== 'undefined' && element != null
}
/**
 * 在指定元素加上倒计时
 * @param  {[type]} ele      [DOM元素]
 * @param  {[type]} seconds [倒计时的秒数]
 * @return {[type]}        [description]
 */
export function countdown (ele, seconds) {
  var timer
  var originTxt = ele.innerHTML
  timer = setInterval(function () {
    seconds--
    if (seconds < 0) {
      ele.innerHTML = originTxt
      clearInterval(timer)
      ele.disabled = false
    } else {
      ele.innerHTML = '请等' + seconds.toString() + '秒'
      ele.disabled = true
    }
  }, 1000)
}

export function toggleClass (wrapperSelector, targetSelector, className) {
  var target = document.querySelectorAll(wrapperSelector)
  for (var i = 0; i < target.length; i++) {
    target[i].classList.remove(className)
    targetSelector.classList.add(className)
  }
}

export function pickDeep (collection, predicate, thisArg) {
  if (_.isFunction(predicate)) {
    predicate = _.iteratee(predicate, thisArg)
  } else {
    var keys = _.flatten(_.rest(arguments))
    predicate = function (val, key) {
      return _.contains(keys, key)
    }
  }
  return _.mapObject(collection, function (memo, val, key) {
    var include = predicate(val, key)
    if (!include && _.isObject(val)) {
      val = pickDeep(val, predicate)
      include = !_.isEmpty(val)
    }
    if (include) {
      _.isArray(collection) ? memo.push(val) : memo[key] = val
    }
  })
}

export function serialize (form) {
  var field = []
  var l = []
  var s = []
  if (typeof form === 'object' && form.nodeName === 'FORM') {
    var len = form.elements.length
    for (var i = 0; i < len; i++) {
      field = form.elements[i]
      if (field.name && !field.disabled && field.type !== 'file' && field.type !== 'reset' && field.type !== 'button' && field.type !== 'submit') {
        if (field.type === 'select-multiple') {
          l = form.elements[i].options.length
          for (var j = 0; j < l; j++) {
            if (field.options[j].selected) {
              s[s.length] = encodeURIComponent(field.name) + '=' + encodeURIComponent(field.options[j].value)
            } else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
              s[s.length] = encodeURIComponent(field.name) + '=' + encodeURIComponent(field.value)
            }
          }
        }
      }
      return s.join('&').replace(/%20/g, '+')
    }
  }
}
