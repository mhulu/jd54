<script>
export default {
  props: {
    css: {
      type: Object,
      default () {
        return {
          wrapperClass: 'pagination pull-right',
          activeClass: 'btn-primary',
          disabledClass: 'disabled',
          pageClass: 'btn btn-border',
          linkClass: 'btn btn-border'
        }
      }
    },
    icons: {
      type: Object,
      default () {
        return {
          first: 'fa fa-fast-backward',
          prev: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          last: 'fa fa-fast-forward',
        }
      }
    },
    onEachSide: {
      type: Number,
      default () {
        return 2
      }
    },
    paginationInfoTemplate: {
      type: String,
      default() {
        return "共 {total} 条记录"
      }
    },
    paginationInfoNoDataTemplate: {
      type: String,
      default() {
        return '无相关数据'
      }
    },
  },
  data: function() {
      return {
        tablePagination: null
      }
  },
  computed: {
    totalPage () {
      return this.tablePagination === null
        ? 0
        : this.tablePagination.total_pages
    },
    isOnFirstPage () {
      return this.tablePagination === null
        ? false
        : this.tablePagination.current_page === 1
    },
    isOnLastPage () {
      return this.tablePagination === null
        ? false
        : this.tablePagination.current_page === this.tablePagination.total_pages
    },
    notEnoughPages () {
      return this.totalPage < (this.onEachSide * 2) + 4
    },
    windowSize () {
      return this.onEachSide * 2 +1;
    },
    windowStart () {
      if (!this.tablePagination || this.tablePagination.current_page <= this.onEachSide) {
        return 1
      } else if (this.tablePagination.current_page >= (this.totalPage - this.onEachSide)) {
        return this.totalPage - this.onEachSide*2
      }

      return this.tablePagination.current_page - this.onEachSide
    },
  },
  methods: {
    loadPage (page) {
      this.$emit('vuetable-pagination:change-page', page)
    },
    isCurrentPage (page) {
      return page === this.tablePagination.current_page
    },
    setPaginationData (tablePagination) {
      this.tablePagination = tablePagination
    },
    registerEvents () {
      let self = this

      this.$on('vuetable-pagination:set-options', (options) => {
        for (var n in options) {
          Vue.set(self, n, options[n])
        }
      })
    }
  },
  created () {
    this.registerEvents()
  }
}
</script>
