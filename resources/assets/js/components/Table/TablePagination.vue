<template>
  <div class="pagination pull-right">
    <a @click="loadPage(1)"
      :class="[{'btn btn-flat btn-default': true}, isOnFirstPage ? 'disabled' : '']">
        首页
    </a>
    <a @click="loadPage('prev')"
      :class="[{'btn btn-flat btn-default': true}, isOnFirstPage ? 'disabled' : '']">
        <i class="fa fa-chevron-left"></i>
    </a>
    <template v-if="notEnoughPages">
      <template v-for="n in totalPage">
        <a @click="loadPage(n)"
          :class="['btn btn-flat', isCurrentPage(n) ? 'btn-primary' : 'btn-default']"
          v-html="n">
        </a>
      </template>
    </template>
    <template v-else>
      <template v-for="n in windowSize">
        <a @click="loadPage(windowStart+n-1)"
          :class="['btn btn-flat', isCurrentPage(windowStart+n-1) ? 'btn-primary' : 'btn-default']"
          v-html="windowStart+n-1">
        </a>
      </template>
    </template>
    <a @click="loadPage('next')"
      :class="[{'btn btn-flat btn-default': true}, isOnLastPage ? 'disabled' : '']">
      <i class="fa fa-chevron-right"></i>
    </a>
    <a @click="loadPage(totalPage)"
      :class="[{'btn btn-flat btn-default': true}, isOnLastPage ? 'disabled' : '']">
      末页
    </a>
  </div>
</template>

<script>
import PaginationMixin from './TablePaginationMixin.vue'

export default {
  mixins: [PaginationMixin]
}
</script>
