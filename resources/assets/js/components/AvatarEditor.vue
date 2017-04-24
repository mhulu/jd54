<template>
  <div class="center">
    <div class="user-image">
      <div class="thumbnail">
      <template v-if="newImg === ''">
        <img :src="imgPath">
      </template>
      <template v-else>
        <img :src="newImg">
      </template>
      </div>
      <div class="user-image-buttons">
        <span class="btn btn-primary btn-file"><i class="fa fa-pencil"></i><input type="file"  accept="image/png, image/jpeg, image/gif, image/jpg" v-on:change="uploadChanges"></span>
        <span class="btn btn-danger" @click="removeAvatar"><i class="fa fa-times"></i></span>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        newImg: '',
        tempImg: ''
      }
    },
    props: {
      imgPath: String,
      apiUrl: String
    },
    methods: {
      uploadChanges (e) {
        this.$store.commit('loading')
        let files = e.target.files || e.dataTransfer.files
        if (!files.length) {
          return
        }
        this.newImg === '' ? this.tempImg = this.imgPath : this.tempImg = this.newImg
        this.newImg = window.URL.createObjectURL(e.target.files[0])
        let formData = new FormData()
        formData.append('id', window.Wemesh.id)
        formData.append('file', files[0])
        axios.post(this.apiUrl, formData)
                  .then((response) => {
                    this.$store.commit('loaded')
                    window.location.reload()
                  })
      },
      removeAvatar () {
        this.newImg = this.tempImg
      }
    }
  }
</script>
<style lang="scss">
.user-image {
  position: relative;
  display: inline-block;
  img{
    max-width: 150px;
    border: 0;
    vertical-align: middle;
  }
}
.thumbnail {
    padding: 4px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    -webkit-transition: border .2s ease-in-out;
    -o-transition: border .2s ease-in-out;
    transition: border .2s ease-in-out;
    margin-bottom: 20px;
    display: block;
}
.thumbnail a>img, .thumbnail>img {
    margin-right: auto;
    margin-left: auto;
    display: block;
    height: auto;
}
.user-image:hover .user-image-buttons {
    display: block;
}
.user-image .user-image-buttons {
  position: absolute;
  top: 10px;
  right: 10px;
  display: none;
}
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: 0;
    background: #fff;
    cursor: inherit;
    display: block;
}
</style>