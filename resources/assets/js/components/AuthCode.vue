<template>
  <div>
    <count-down v-on:event="getAuthCode" :disabled="disabled" :color="color"><slot>获取短信验证码</slot></count-down>
  </div>
</template>
<script>
  import countDown from './CountDown.vue'
  export default {
    props: {
      data: {
        type: Object,
        require: true
      },
      disabled: {
        type: Boolean,
        require: true
      },
      color: {
        default: 'info'
      }
    },
    methods: {
      getAuthCode: function () {
        if (window.location.pathname === '/reset') {
          axios.post('getsms?findpass', this.data)
            .then((response) => {
              swal('操作成功', response.data.data, 'success')
            })
            .catch((error) => {
              let data = error.response.data
              swal('操作失败', data[Object.keys(data) [0]], 'error')
            })
        } else {
            axios.post('getsms', this.data)
              .then((response) => {
                swal('操作成功', response.data.data, 'success')
              })
              .catch((error) => {
                let data = error.response.data
                swal('操作失败', data[Object.keys(data) [0]], 'error')
              })
        }
      }
    },
    components: {
      countDown
    }
  }
</script>