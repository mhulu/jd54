import store from './vuex/store'

export default function (...args)
{
	store.commit('loading') // 加载loading动画元素

	if (args.length < 2) {
		return window.console.error('You must send 2 params to use XHR at least.')
	}
	const type = args.shift() // The first argument is HTTP verbs.
	const requestUrl = args.shift() // The second argument is the URL which data send to.

	// If send 2 arguments only use defalut message content, else ...
	if (args.length === 0) {
		var hasSuccessMessage = false
		var hasFailedMessage = false
	} else {
		var message = (typeof args[args.length - 1] === 'object') ? args.pop() : null // The last argument is the content for message.
		var options = (args.length > 0) ? args.shift() : null // It is an optional argument, maybe params for GET, maybe data for POST, DEvarE ...
		var hasSuccessMessage = _.has(message, 'success') && _.has(message['success'], 'text')
		var hasFailedMessage = _.has(message, 'failed') && _.has(message['failed'], 'text')
	}

	switch (type.toUpperCase()) {
	  case 'GET':
	  axios.get(requestUrl, options)
	  .then((response) => {
	    hasSuccessMessage ?  showMessage('success', message['success'].text) : showMessage('success', response.data.data)
	  })
	  .catch((error) => {
	    hasFailedMessage ?  showMessage('failed', message['failed'].text) : showMessage('failed', _.has(error, 'response') ? _.values(error.response.data)[0] : error)
	  })
	  break

	  case 'POST':
	  if (_.isEmpty(options)) {
	    return window.console.error('You must send data to remote server when you used POST method.')
	  }
	  axios.post(requestUrl, options)
	  .then((response) => {
	    hasSuccessMessage ? showMessage('success', message['success'].text) : showMessage('success', response.data.data)
	  })
	  .catch((error) => {
	    hasFailedMessage ?  showMessage('failed', message['failed'].text) : showMessage('failed', _.has(error, 'response') ? _.values(error.response.data)[0] : error)
	  })
	  break

	  case 'PUT':
	  if (_.isEmpty(options)) {
	    return window.console.error('You must send data to remote server when you used PUT method.')
	  }
	  axios.post(requestUrl, options)
	  .then((response) => {
	    hasSuccessMessage ? showMessage('success', message['success'].text) : showMessage('success', response.data.data)
	  })
	  .catch((error) => {
	    hasFailedMessage ?  showMessage('failed', message['failed'].text) : showMessage('failed', _.has(error, 'response') ? _.values(error.response.data)[0] : error)
	  })
	  break

	  case 'DELETE':
	  if (_.isEmpty(options)) {
	    return window.console.error('You must send data to remote server when you used DELETE method.')
	  }
	  axios.post(requestUrl, options)
	  .then((response) => {
	    hasSuccessMessage ? showMessage('success', message['success'].text) : showMessage('success', response.data.data)
	  })
	  .catch((error) => {
	    hasFailedMessage ?  showMessage('failed', message['failed'].text) : showMessage('failed', _.has(error, 'response') ? _.values(error.response.data)[0] : error)
	  })
	  break
	}

	const showMessage = function (type, text) {
		window.console.log(text)
		if (typeof(message) === 'undefined' || _.isEmpty(message)[type]) {
			var content = {
				title: '操作提示',
				btnText: '确定'
			}
		} else {
			var content = {
				title: _.has(message[type], 'title') ? message[type].title : '操作提示',
				btnText: _.has(message[type], 'btnText') ? message[type].btnText : '确定',
				redirectUrl: _.has(message[type], 'redirectUrl') ? message[type].title : '',
				text: text
			}
		}
		if (typeof(message) === 'undefined' || _.isEmpty(message)[alertType]) {
			var alertType = 'raw'
		} else {
			var alertType = message.alertType
		}
		store.commit('loaded')  // 取消loading元素
		return ALERT[type + '_' + alertType] (content)
	}
}

const ALERT = {
  success_swal: function (content) {
    swal({
      title: content.title,
      text: content.text,
      type: 'success',
      allowOutsideClick: false,
      confirmButtonText: content.btnText
    }, function () {
        if (_.has(content, 'redirectUrl') || content.redirectUrl !== '') {
          window.location.href = content.redirectUrl
        } else {
      	return false
        }
    })
  },
  failed_swal: function (content) {
    swal({
      title: content.title,
      text: content.text,
      type: 'error',
      allowOutsideClick: false,
      confirmButtonText: content.btnText
    }, function () {
      if (_.has(content, 'redirectUrl') || content.redirectUrl !== '') {
        window.location.href = content.redirectUrl
      } else {
    	return false
      }
    })
  },
  success_toastr: function (content) {
    toastr.success(content.text)
  },
  failed_toastr: function (content) {
    toastr.error(content.text)
  },
  success_raw: function () {
    return
  },
  failed_raw: function (content) {
  	toastr.error(content.text)
  }
}
