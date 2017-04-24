<template>
  <button class="btn" :class="'btn-' + color" id="getAuthcode" @click.prevent="getAuthcode($event)"><slot>获取短信验证码</slot></button>
</template>
<script>
  import {countdown} from '../plugins/utils'
  export default {
    props: {
      duration: {
        default: 60
      },
      color: {
        default: 'info'
      }
    },
    methods: {
      getAuthcode (e) {
        countdown(e.target, this.duration)
        this.$emit('event')
      }
    }
  }
</script>