<template>
  <header class="main-header">
    <a href="/" class="logo">
      <span class="logo-mini">logo</span>
      <span class="logo-lg"><i class="fa fa-podcast"></i> {{app}}</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">切换菜单</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- <li class="dropdown messages-menu">
            <a href="" data-toggle="dropdown" class="dropdown-toggle">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">你收到了消息</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="http://static.stario.net/images/avatar.png" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        测试消息
                        <small><i class="fa fa-clock-o"></i> 5 分钟前</small>
                      </h4>
                      <p>嫩不能看一下？</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">全部消息</a></li>
            </ul>
          </li> -->
          <!-- <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" v-show="unreadNotifications.length > 0">{{unreadNotifications.length}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" v-if="unreadNotifications.length > 0">
              您有{{unreadNotifications.length}}条未读提醒
              </li>
              <li class="header" v-else>
              您没有未读的提醒
              </li>
              <li>
                <ul class="menu">
                <li v-for="item in unreadNotifications">
                  <a href="" data-toggle="tooltip" :title="item.data.title">
                  <i class="fa fa-user-circle" :class="'text-' + item.data.type" v-if="notificationType(item.type) === 'User'"></i> 
                  <i class="fa fa-street-view" :class="'text-' + item.data.type" v-else-if="notificationType(item.type) === 'Pop'"></i> 
                  <i class="fa fa-blind" :class="'text-' + item.data.type" v-else-if="notificationType(item.type) === 'Aged'"></i> 
                  <i class="fa fa-cog" :class="'text-' + item.data.type" v-else="notificationType(item.type) === 'Site'"></i> 
                  {{item.data.title}}
                  </a>
                </li>
                </ul>
              </li>
              <li class="footer"><router-link to="/dashboard/me/event">查看通知中心</router-link></li>
            </ul>
          </li> -->
          <!-- <li class="dropdown tasks-menu"><a href="" data-toggle="dropdown" class="dropdown-toggle">
            <i class="fa fa-flag-o"></i>
            <span class="label label-danger">9</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">您有9项任务正在进行中</li>
            <li>
              <ul class="menu">
                <li><a href="">
                  <h3>编写年终报告
                  <small class="pull-right">20%</small>
                  </h3>
                  <div class="progress xs">
                    <div class="progress-bar progress-bar-aqua" style="width:20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                      <span class="sr-only">已完成20%</span>
                    </div>
                  </div>
                </a>
                </li>
              </ul>
            </li>
            <li class="footer">
              <a href="">全部任务</a>
            </li>
          </ul>
          </li> -->
          <li class="dropdown user user-menu">
            <a href="" data-toggle="dropdown" class="dropdown-toggle">
              <img :src="profile.avatar" alt="用户头像" class="user-image">
              <span class="hidden-xs">{{userInfo.name}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img :src="profile.avatar" alt="用户头像" class="img-circle">
                <p>
                  {{profile.nickname}} - {{userInfo.unit}}
                  <small>自{{userInfo.created_at}}加入</small>
                </p>
              </li>
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <i class="fa fa-birthday-cake text-red"></i> 您的生日在 <span class="text-blue">{{userInfo.diffMyDays}}</span> 
                  </div>
                </div>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                <router-link to="/dashboard/me/profile" class="btn btn-default btn-flat">个人资料</router-link>
                </div>
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat">退出系统</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

  </header>
</template>

<script>
  export default {
    data () {
      return {
        app: window.app
      }
    },
    computed: {
      userInfo () {
        return this.$store.getters.getUserInfo
      },
      profile () {
        return this.$store.getters.getProfile
      }
      // unreadNotifications() {
      //   return this.$store.getters.getNotification
      // }
    },
    // methods: {
    //   notificationType (str) {
    //     return _.last(_.split(str, '\\'))
    //   }
    // }
  }
</script>