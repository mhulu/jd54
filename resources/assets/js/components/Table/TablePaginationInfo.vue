<template>
  <div class="dataTables_info" 
    v-html="paginationInfo">
  </div>
</template>

<script>
import PaginationInfoMixin from './TablePaginationInfoMixin.vue'

export default {
  name: 'pagination-info',
  mixins: [PaginationInfoMixin],
  computed: {
    paginationInfo () {
      if (this.tablePagination == null || this.tablePagination.total == 0) {
        return this.paginationInfoNoDataTemplate
      }

      return this.paginationInfoTemplate
        .replace('{total}', this.tablePagination.total || 0)
    },
  },
  data: function() {
    return {
      tablePagination: null
    }
  },
  methods: {
    setPaginationData (tablePagination) {
      this.tablePagination = tablePagination
    },
  },
}
</script>
<style>
  .dataTables_info {
    white-space: nowrap;
    line-height: 74px;
  }
</style>
