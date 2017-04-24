<template>
  <table v-if="tableData.length > 0" class="table table-striped table-hover dataTable">
    <thead>
      <tr>
        <template v-for="field in fields">
          <template v-if="field.visible">
            <template v-if="isSpecialField(field.name)">
              <th v-if="extractName(field.name) == '__checkbox'"
                :class="['vuetable-th-checkbox-'+trackBy, field.titleClass]">
                <input type="checkbox" @change="toggleAllCheckboxes(field.name, $event)"
                  :checked="checkCheckboxesState(field.name)">
              </th>
              <th v-if="extractName(field.name) == '__component'"
                  @click="orderBy(field, $event)"
                  :class="['vuetable-th-component-'+trackBy, field.titleClass, {'sortable': isSortable(field)}]">
                  {{ field.title || '' }}
                  <i v-if="isInCurrentSortGroup(field) && field.title"
                     :class="sortIcon(field)"
                     :style="{opacity: sortIconOpacity(field)}"></i>
              </th>
              <th v-if="extractName(field.name) == '__slot'"
                  @click="orderBy(field, $event)"
                  :class="['vuetable-th-slot-'+extractArgs(field.name), field.titleClass, {'sortable': isSortable(field)}]">
                  {{ field.title || '' }}
                  <i v-if="isInCurrentSortGroup(field) && field.title"
                     :class="sortIcon(field)"
                     :style="{opacity: sortIconOpacity(field)}"></i>
              </th>
              <th v-if="extractName(field.name) == '__sequence'"
                  :class="['vuetable-th-sequence', field.titleClass || '']" v-html="field.title || ''">
              </th>
              <th v-if="notIn(extractName(field.name), ['__sequence', '__checkbox', '__component', '__slot'])"
                  :class="[field.titleClass || '']" v-html="field.title || ''">
              </th>
            </template>
            <template v-else>
              <th @click="orderBy(field, $event)"
                :id="'_' + field.name"
                :class="[field.titleClass,  sortClass(field)]">
                {{  getTitle(field) }}&nbsp;

              </th>
            </template>
          </template>
        </template>
      </tr>
    </thead>
    <tbody v-cloak>
      <template v-for="(item, index) in tableData">
        <tr @dblclick="onRowDoubleClicked(item, $event)" @click="onRowClicked(item, $event)" :render="onRowChanged(item)" :class="onRowClass(item, index)">
          <template v-for="field in fields">
            <template v-if="field.visible">
              <template v-if="isSpecialField(field.name)">
                <td v-if="extractName(field.name) == '__sequence'" :class="['vuetable-sequence', field.dataClass]"
                  v-html="tablePagination.current_page * tablePagination.per_page - tablePagination.count + index + 1">
                </td>
                <td v-if="extractName(field.name) == '__handle'" :class="['vuetable-handle', field.dataClass]">
                  <i :class="['sort-handle', css.sortHandleIcon]"></i>
                </td>
                <td v-if="extractName(field.name) == '__checkbox'" :class="['vuetable-checkboxes', field.dataClass]">
                  <input type="checkbox"
                    @change="toggleCheckbox(item, field.name, $event)"
                    :checked="rowSelected(item, field.name)">
                </td>
                <td v-if="extractName(field.name) === '__component'" :class="['vuetable-component', field.dataClass]">
                    <component :is="extractArgs(field.name)" :row-data="item" :row-index="index"></component>
                </td>
                <td v-if="extractName(field.name) === '__slot'" :class="['vuetable-slot', field.dataClass]">
                  <slot :name="extractArgs(field.name)" :row-data="item" :row-index="index"></slot>
                </td>
              </template>
              <template v-else>
                <td v-if="hasCallback(field)" :class="field.dataClass"
                  @click="onCellClicked(item, index, $event)"
                  @dblclick="onCellDoubleClicked(item, field, $event)"
                  v-html="callCallback(field, item)"
                >
                </td>
                <td v-else :class="field.dataClass"
                  @click="onCellClicked(item, index, $event)"
                  @dblclick="onCellDoubleClicked(item, field, $event)"
                  v-html="getObjectValue(item, field.name, '')"
                >
                </td>
              </template>
            </template>
          </template>
        </tr>
        <template v-if="useDetailRow">
          <transition :name="detailRowTransition">
            <tr v-if="isVisibleDetailRow(item[trackBy])"
              @click="onDetailRowClick(item, $event)"
              :class="[css.detailRowClass]"
            >
              <td :colspan="countVisibleFields">
                <component :is="detailRowComponent" :row-data="item" :row-index="index"></component>
              </td>
            </tr>
          </transition>
        </template>
      </template>
    </tbody>
  </table>
  <div v-else>
    <p>暂无数据</p>
  </div>
</template>

<script>
export default {
  props: {
    fields: {
      type: Array,
      required: true
    },
    loadOnStart: {
      type: Boolean,
      default: true
    },
    apiUrl: {
        type: String,
        default: ''
    },
    dataPath: {
        type: String,
        default: 'data'
    },
    paginationPath: {
        type: [String],
        default: 'meta.pagination'
    },
    queryParams: {
      type: Object,
      default: function() {
        return {
          sort: 'sort',
          page: 'page',
          perPage: 'per_page'
        }
      }
    },
    appendParams: {
      type: Object,
      default: function() {
        return {}
      }
    },
    httpOptions: {
      type: Object,
      default: function() {
        return {}
      }
    },
    perPage: {
        type: Number,
        default: function() {
            return 15
        }
    },
    sortOrder: {
      type: Array,
      default: function() {
        return []
      }
    },
    rowClassCallback: {
      type: String,
      default: ''
    },
    detailRowComponent: {
      type: String,
      default: ''
    },
    detailRowTransition: {
      type: String,
      default: ''
    },
    trackBy: {
      type: String,
      default: 'id'
    },
    silent: {
      type: Boolean,
      default: false
    }
  },
  data: function() {
    return {
      eventPrefix: 'vuetable:',
      tableData: [],
      tablePagination: null,
      currentPage: 1,
      selectedTo: [],
      visibleDetailRows: [],
    }
  },
  destroyed: function () {
  this.$events.off('filter-set');
  this.$events.off('filter-reset');
  },
  created: function() {
    this.normalizeFields()
    if (this.loadOnStart) {
      this.loadData()
    }
  },
  computed: {
    useDetailRow: function() {
      if (this.tableData && this.tableData[0] && typeof this.tableData[0][this.trackBy] === 'undefined') {
        this.warn('You need to define "detail-row-id" in order for detail-row feature to work!')
        return false
      }

      return this.detailRowComponent !== ''
    },
    countVisibleFields: function() {
      return this.fields.filter(function(field) {
        return field.visible
      }).length
    }
  },
  methods: {
    sortClass: function (field) {
      if (this.isInCurrentSortGroup(field)) {
        return 'sorting_' + this.sortOrder[0].direction
      } else if (this.isSortable(field)) {
        return 'sortable'
      }
    },
    normalizeFields: function() {
      if (typeof(this.fields) === 'undefined') {
        this.warn('You need to provide "fields" prop.')
        return
      }

      let self = this
      let obj
      this.fields.forEach(function(field, i) {
        if (typeof (field) === 'string') {
          obj = {
            name: field,
            title: self.setTitle(field),
            titleClass: '',
            dataClass: '',
            callback: null,
            visible: true,
          }
        } else {
          obj = {
            name: field.name,
            title: (field.title === undefined) ? self.setTitle(field.name) : field.title,
            sortField: field.sortField,
            titleClass: (field.titleClass === undefined) ? '' : field.titleClass,
            dataClass: (field.dataClass === undefined) ? '' : field.dataClass,
            callback: (field.callback === undefined) ? '' : field.callback,
            visible: (field.visible === undefined) ? true : field.visible,
          }
        }
        Vue.set(self.fields, i, obj)
      })
    },
    setTitle: function(str) {
      if (this.isSpecialField(str)) {
        return ''
      }

      return str
    },
    getTitle: function(field) {
      if (typeof field.title === 'undefined') {
        return field.name.replace('.', ' ')
      }

      return field.title
    },
    isSpecialField: function(fieldName) {
      return fieldName.slice(0, 2) === '__'
    },
    camelCase: function(str, delimiter = '_') {
      let self = this
      return str.split(delimiter).map(function(item) {
        return item
      }).join('')
    },
    notIn: function(str, arr) {
      return arr.indexOf(str) === -1
    },
    loadData: function(success = this.loadSuccess, failed = this.loadFailed) {
      // this.fireEvent('loading')
      this.$store.commit('loading')
      this.httpOptions['params'] = this.getAllQueryParams()
      this.$http.get(this.apiUrl, this.httpOptions).then(
        success,
        failed
      )
    },
    loadSuccess: function(response) {
      this.fireEvent('load-success', response)
      let body = response.data
      this.tableData = this.getObjectValue(body, this.dataPath, null)
      this.tablePagination = this.getObjectValue(body, this.paginationPath, null)

      if (this.tablePagination === null) {
        this.warn('vuetable: pagination-path "' + this.paginationPath + '" not found. '
          + 'It looks like the data returned from the sever does not have pagination information '
          + 'or you may have set it incorrectly.'
        )
      }

      this.$nextTick(function() {
        this.fireEvent('pagination-data', this.tablePagination)
        // this.fireEvent('loaded')
        this.$store.commit('loaded')
      })
    },
    loadFailed: function(response) {
      toastr.error('数据读取错误，请重试')
      this.$store.commit('loaded')
    },
    parentFunctionExists: function(func) {
      return (func !== '' && typeof this.$parent[func] === 'function')
    },
    fireEvent: function(eventName, args) {
      this.$emit(this.eventPrefix + eventName, args)
    },
    warn: function(msg) {
      if (!this.silent) {
        console.warn(msg)
      }
    },
    getAllQueryParams: function() {
      let params = {}
      params[this.queryParams.sort] = this.getSortParam()
      params[this.queryParams.page] = this.currentPage
      params[this.queryParams.perPage] = this.perPage

      for (let x in this.appendParams) {
        params[x] = this.appendParams[x]
      }

      return params
    },
    getSortParam: function() {
      if (!this.sortOrder || this.sortOrder.field == '') {
        return ''
      }

      if (typeof this.$parent['getSortParam'] == 'function') {
        return this.$parent['getSortParam'].call(this.$parent, this.sortOrder)
      }

      return this.getDefaultSortParam()
    },
    getDefaultSortParam: function() {
      let result = '';

      for (let i = 0; i < this.sortOrder.length; i++) {
        let fieldName = (typeof this.sortOrder[i].sortField === 'undefined')
          ? this.sortOrder[i].field
          : this.sortOrder[i].sortField;

        result += fieldName + '|' + this.sortOrder[i].direction + ((i+1) < this.sortOrder.length ? ',' : '');
      }

      return result;
    },
    extractName: function(string) {
      return string.split(':')[0].trim()
    },
    extractArgs: function(string) {
      return string.split(':')[1]
    },
    isSortable: function(field) {
      return !(typeof field.sortField === 'undefined')
    },
    isInCurrentSortGroup: function(field) {
      return this.currentSortOrderPosition(field) !== false;
    },
    currentSortOrderPosition: function(field) {
      if ( ! this.isSortable(field)) {
        return false
      }

      for (let i = 0; i < this.sortOrder.length; i++) {
        if (this.fieldIsInSortOrderPosition(field, i)) {
          return i;
        }
      }

      return false;
    },
    fieldIsInSortOrderPosition(field, i) {
      return this.sortOrder[i].field === field.name && this.sortOrder[i].sortField === field.sortField
    },
    orderBy: function(field, event) {
      if ( ! this.isSortable(field)) return
        this.singleColumnSort(field)
      this.currentPage = 1    // reset page index
      this.loadData()
    },
    singleColumnSort: function(field) {
      if (this.sortOrder.length === 0) {
        this.clearSortOrder()
      }

      this.sortOrder.splice(1); //removes additional columns

      if (this.fieldIsInSortOrderPosition(field, 0)) {
        // change sort direction
        this.sortOrder[0].direction = this.sortOrder[0].direction === 'asc' ? 'desc' : 'asc'
      } else {
        // reset sort direction
        this.sortOrder[0].direction = 'asc'
      }
      this.sortOrder[0].field = field.name
      this.sortOrder[0].sortField = field.sortField
    },
    clearSortOrder: function() {
      this.sortOrder.push({
        field: '',
        sortField: '',
        direction: 'asc'
      });
    },
    hasCallback: function(item) {
      return item.callback ? true : false
    },
    callCallback: function(field, item) {
      if ( ! this.hasCallback(field)) return

      if(typeof(field.callback) == 'function') {
       return field.callback(this.getObjectValue(item, field.name))
      }

      let args = field.callback.split('|')
      let func = args.shift()

      if (typeof this.$parent[func] === 'function') {
        let value = this.getObjectValue(item, field.name)

        return (args.length > 0)
          ? this.$parent[func].apply(this.$parent, [value].concat(args))
          : this.$parent[func].call(this.$parent, value)
      }

      return null
    },
    getObjectValue: function(object, path, defaultValue) {
      defaultValue = (typeof defaultValue === 'undefined') ? null : defaultValue

      let obj = object
      if (path.trim() != '') {
        let keys = path.split('.')
        keys.forEach(function(key) {
          if (obj !== null && typeof obj[key] !== 'undefined' && obj[key] !== null) {
            obj = obj[key]
          } else {
            obj = defaultValue
            return
          }
        })
      }
      return obj
    },
    toggleCheckbox: function(dataItem, fieldName, event) {
      let isChecked = event.target.checked
      let idColumn = this.trackBy

      if (dataItem[idColumn] === undefined) {
        this.warn('__checkbox field: The "'+this.trackBy+'" field does not exist! Make sure the field you specify in "track-by" prop does exist.')
        return
      }

      let key = dataItem[idColumn]
      if (isChecked) {
        this.selectId(key)
      } else {
        this.unselectId(key)
      }
      // this.$emit('vuetable:checkbox-toggled', isChecked, dataItem)
      this.$emit('vuetable:checkbox-toggled', this.selectedTo)
    },
    selectId: function(key) {
      if ( ! this.isSelectedRow(key)) {
        this.selectedTo.push(key)
      }
    },
    unselectId: function(key) {
      this.selectedTo = this.selectedTo.filter(function(item) {
        return item !== key
      })
    },
    isSelectedRow: function(key) {
      return this.selectedTo.indexOf(key) >= 0
    },
    rowSelected: function(dataItem, fieldName){
      let idColumn = this.trackBy
      let key = dataItem[idColumn]

      return this.isSelectedRow(key)
    },
    checkCheckboxesState: function(fieldName) {
      if (! this.tableData) return

      let self = this
      let idColumn = this.trackBy
      let selector = 'th.vuetable-th-checkbox-' + idColumn + ' input[type=checkbox]'
      let els = document.querySelectorAll(selector)

      // count how many checkbox row in the current page has been checked
      let selected = this.tableData.filter(function(item) {
        return self.selectedTo.indexOf(item[idColumn]) >= 0
      })

      // count == 0, clear the checkbox
      if (selected.length <= 0) {
        els.forEach(function(el) {
          el.indeterminate = false
        })
        return false
      }
      // count > 0 and count < perPage, set checkbox state to 'indeterminate'
      else if (selected.length < this.perPage) {
        els.forEach(function(el) {
          el.indeterminate = true
        })
        return true
      }
      // count == perPage, set checkbox state to 'checked'
      else {
        els.forEach(function(el) {
          el.indeterminate = false
        })
        return true
      }
    },
    toggleAllCheckboxes: function(fieldName, event) {
      let self = this
      let isChecked = event.target.checked
      let idColumn = this.trackBy

      if (isChecked) {
        this.tableData.forEach(function(dataItem) {
          self.selectId(dataItem[idColumn])
        })
      } else {
        this.tableData.forEach(function(dataItem) {
          self.unselectId(dataItem[idColumn])
        })
      }
      this.$emit('vuetable:checkbox-toggled-all', this.selectedTo)
    },
    gotoPreviousPage: function() {
      if (this.currentPage > 1) {
        this.currentPage--
        this.loadData()
      }
    },
    gotoNextPage: function() {
      if (this.currentPage < this.tablePagination.total_pages) {
        this.currentPage++
        this.loadData()
      }
    },
    gotoPage: function(page) {
      if (page != this.currentPage && (page > 0 && page <= this.tablePagination.total_pages)) {
        this.currentPage = page
        this.loadData()
      }
    },
    isVisibleDetailRow: function(rowId) {
      return this.visibleDetailRows.indexOf( rowId ) >= 0
    },
    showDetailRow: function(rowId) {
      if (!this.isVisibleDetailRow(rowId)) {
        this.visibleDetailRows.push(rowId)
      }
    },
    hideDetailRow: function(rowId) {
      if (this.isVisibleDetailRow(rowId)) {
        this.visibleDetailRows.splice(
          this.visibleDetailRows.indexOf(rowId),
          1
        )
      }
    },
    toggleDetailRow: function(rowId) {
      if (this.isVisibleDetailRow(rowId)) {
        this.hideDetailRow(rowId)
      } else {
        this.showDetailRow(rowId)
      }
    },
    onRowClass: function(dataItem, index) {
      let func = this.rowClassCallback.trim()

      if (func !== '' && typeof this.$parent[func] === 'function') {
          return this.$parent[func].call(this.$parent, dataItem, index)
      }
      return ''
    },
    onRowChanged: function(dataItem) {
      this.fireEvent('row-changed', dataItem)
      return true
    },
    onRowClicked: function(dataItem, event) {
      this.$emit(this.eventPrefix + 'row-clicked', dataItem, event)
      return true
    },
    onRowDoubleClicked: function(dataItem, event) {
      this.$emit(this.eventPrefix + 'row-dblclicked', dataItem, event)
    },
    onDetailRowClick: function(dataItem, event) {
      this.$emit(this.eventPrefix + 'detail-row-clicked', dataItem, event)
    },
    // onCellClicked: function(dataItem, field, event) {
    onCellClicked: function(dataItem, index, event) {
      this.$emit(this.eventPrefix + 'cell-clicked', dataItem, index, event)
    },
    onCellDoubleClicked: function(dataItem, field, event) {
      this.$emit(this.eventPrefix + 'cell-dblclicked', dataItem, field, event)
    },
    /*
     * API for externals
     */
    changePage: function(page) {
      if (page === 'prev') {
        this.gotoPreviousPage()
      } else if (page === 'next') {
        this.gotoNextPage()
      } else {
        this.gotoPage(page)
      }
    },
    reload: function() {
      this.loadData()
    },
    refresh: function() {
      this.currentPage = 1
      this.loadData()
    },
    reject: function (index) {
      this.tableData.splice(this.tableData.indexOf(index), 1)
    }
  }
}
</script>
<style>
  .table-hover > tbody > tr:hover {
    background-color: #e5e9ea;
    cursor: pointer;
  }
  .sortable, .sorting_asc, .sorting_desc {
    cursor: pointer;
  }
  .sortable:after {
    opacity: 0.2;
    font-family: 'FontAwesome';
    content: "\f0dc";
  }

  .sorting_asc:after {
    font-family: 'FontAwesome';
    content: "\f0de";
  }
  .sorting_desc:after {
    font-family: 'FontAwesome';
    content: "\f0dd";
  }
</style>