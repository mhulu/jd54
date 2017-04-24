<template>
  <div class="form-group">
    <label>
      上传头像
    </label>
    <div>
      <div class="user-image">
        <div class="thumbnail margin-bottom-5">
          <img :src="avatar">
        </div>
      </div>
      <div class="user-image-buttons-edit">
        <span class="btn btn-primary btn-file">
          <i class="fa fa-pencil"></i>
          <input type="file" v-on:change="uploadChanges">
        </span>
        <span class="btn btn-danger" @click="removeImage()"><i class="fa fa-times"></i> </span>
      </div>
      <div class="row" id="btn" style="display:none">
        <button class="btn btn-primary">确定上传</button>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        avatar: window.userInfo.data.profile.avatar
      }
    },
    methods: {
      uploadChanges (e) {
        if (e.target.files[0]) {
          this.avatar = window.URL.createObjectURL(e.target.files[0])
          $('#btn').show()
        }
      },
      removeImage() {
        this.avatar = 'http://static.stario.net/images/avatar.png'
      }
    }
  }
</script>