<template>
  <div>
  <div class="form-group">
      <label class="col-sm-3 control-label"><i class="fa fa-mobile"></i> 手机号码</label>
      <div class="col-sm-9">
          <input type="text" class="form-control" v-model="user.mobile" placeholder="输入手机号" required>
      </div>
  </div>

  <div class="form-group">
        <label class="col-sm-3 control-label"><i class="fa fa-commenting-o"></i> 验证短信</label>
        <div class="col-sm-5">
            <input type="text" name="authCode" class="form-control" v-model="user.authCode" placeholder="输入您收到的验证码">
        </div>
        <div class="col-sm-4">
            <auth-code :data="{mobile:this.user.mobile}" :disabled="!(validator.mobile)">获取验证码</auth-code>
        </div>
      </div>


      <div class="form-group">
          <label class="col-sm-3 control-label"><i class="fa fa-user-circle"></i> 真实姓名</label>
          <div class="col-sm-9">
              <input type="text" class="form-control" v-model="user.name" placeholder="输入您的真实姓名" required>
          </div>
      </div>

      <div class="form-group">
      <label class="col-sm-3 control-label"><i class="fa fa-key"></i> 输入密码</label>
      <div class="col-sm-9">
          <input type="password" class="form-control" name="password" v-model="user.password" placeholder="输入不少于6位的密码" required>
      </div>
  </div>

  <div class="form-group">
      <label class="col-sm-3 control-label"><i class="fa fa-key"></i> 密码确认</label>
      <div class="col-sm-9">
          <input type="password" class="form-control" v-model="user.repassword" placeholder="再次输入您的密码" required>
      </div>
  </div>
  <button @click.prevent="onSubmit" :disabled="!(validator.passed)" class="btn btn-lg btn-primary margin-bottom-10 btn-block"><i class="fa fa-sign-in"></i> 完成注册</button>
  </div>
   
</template>
<script>
  import AuthCode from './AuthCode.vue'
  import {isMobile, required} from '../validator'
  export default {
    data () {
      return {
        user: {
          mobile: '',
          authCode: '',
          password: '',
          repassword: '',
          name: ''
        }
      }
    },
    computed: {
      validator () {
        return {
          mobile: isMobile(this.user.mobile),
          passed: isMobile(this.user.mobile) && required(this.user.password) && required(this.user.name) && required(this.user.authCode) && this.user.password === this.user.repassword
        }
      }
    },
    methods: {
      onSubmit: function () {
        const user = {
          mobile: this.user.mobile,
          password: this.user.password,
          authCode: this.user.authCode,
          name: this.user.name
        }
        axios.post('signup', this.user)
          .then((response) => {
            swal({
              title: '注册成功',
              text: '您已经成功注册，现在可以登录了',
              type: 'success',
              confirmButtonText: '继续操作',
              allowOutsideClick: false
            }, () => {
              window.location.href = '/login'
            })
          })
          .catch((error) => {
            let data = error.response.data
            swal({
              title: '注册失败',
              text: data[Object.keys(data) [0]],
              type: 'error',
              confirmButtonText: '继续',
              allowOutsideClick: false
            }, () => {
              window.location.href = '/login'
            })
          })
      }
    },
    components: {
      AuthCode
    }
  }
</script>