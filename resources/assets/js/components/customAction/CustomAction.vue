<template>
    <div>
        <span :style="{color: color}" @click="setStatus(rowData)"><i class="fa fa-circle"></i></span>
    </div>
</template>

<script>
    export default{
        props: {
            rowData: {
                type: Object,
                required: true
            },
            apiUrl: {
                type: String,
                required: true
            }
        },
        computed: {
            color() {
                return this.rowData.status ? '#8eb4cb' : '#bf5329'
            }
        },
        methods: {
            setStatus(rowData) {
                let index = this
                swal({
                    title: '修改用户状态',
                    text: '本次操作将影响用户登录状态，请三思而行',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: '取消操作',
                    closeOnConfirm: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: '确定我的操作',
                },
                function () {
                    index.postData(rowData)
                })
            },
            postData(rowData) {
                this.$http.post(this.apiUrl + '/' + rowData.id + '/status', {status: !rowData.status})
                    .then(response => {
                        if (response.data) {
                            this.rowData.status = !this.rowData.status
                            if (this.rowData.status) {
                                toastr.success('You changed a record of the status success!')
                            } else {
                                toastr.warning('You changed a record of the status, Please check again!')
                            }
                        }
                    }, (response) => {
                        if (response.data.error) {
                            toastr.error(response.data.error.message)
                        } else {
                            toastr.error(response.status + ' : Resource ' + response.statusText)
                        }
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    span {
        cursor:pointer;
    }
</style>
