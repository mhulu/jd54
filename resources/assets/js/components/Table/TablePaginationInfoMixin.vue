<script>
export default {
  props: {
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
}
</script>
