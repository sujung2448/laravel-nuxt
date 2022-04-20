<template>
  <div>
    <table class="table table-bordered">
      <thead>
        <tr class="text-center">
          <th>
            <input
              v-model="allChecked"
              type="checkbox"
              @click="checkedAll($event.target.checked)"
            >
          </th>
          <th>신청일</th>
          <th>신청금액</th>
          <th>처리상태</th>
          <th />
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in list" :key="item.id">
          <td class="text-center">
            <input :id="'check_' + item.id"
                   v-model="item.selected"
                   type="checkbox"
                   :value="item.id"
                   @change="selected($event)"
            >
          </td>
          <td class="text-center" scope="row">
            {{ $moment(item.created_at).format("YYYY-MM-DD kk:mm:ss") }}
          </td>
          <td class="text-right">
            {{ item.amount | comma }}
          </td>
          <td class="text-center">
            <span v-if="item.status === 1" class="badge badge-success px-2">완료</span>
            <span v-else-if="item.status === 2" class="badge badge-danger px-2">취소</span>
            <span v-else-if="item.status === 0" class="badge badge-primary px-2">대기</span>
          </td>
          <td class="text-center">
            <i class="el-icon-delete-solid" @click="clickdelete(item.id)" />
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mb-2 flex">
      <span class="btn btn-secondary btn-sm" @click="allDelete">
        선택삭제
      </span>
    </div>
    <div class="">
      <Pagination :data="laravelData" align="center"
                  @pagination-change-page="getList"
      />
    </div>
  </div>
</template>

<script>
import LaravelVuePagination from 'laravel-vue-pagination'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  components: {
    Pagination: LaravelVuePagination
  },
  filters: {
    comma (v) {
      return String(v).replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',')
    }
  },
  scrollToTop: false,
  data: () => ({
    list: [],
    laravelData: {},
    allChecked: false

  }),
  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    // this.form.keys().forEach((key) => {
    //   this.form[key] = this.user[key]
    // })
  },
  mounted () {
    this.getList()
  },

  methods: {
    getList (page = 1) {
      axios.get('/debit/list?page=' + page)
        .then((res) => {
          this.laravelData = res.data
          this.list = res.data.data
        })
    },
    allDelete () {
      console.log('test')
      const ids = this.getSelected()
      console.log(ids)
      axios.post('/debit/alldelete', {
        ids
      })
        .then((res) => {
          console.log(res)
          if (res.data === 'success') {
            this.$message({
              type: 'success',
              message: '정상적으로 삭제되었습니다.'
            })
            this.getList()
          }
          if (res.data === 'error') {
            this.$message({
              type: 'error',
              message: '처리상태가 "대기"일 경우에는 삭제 할 수 없습니다.'
            })
          }
        })
    },
    clickdelete (ids) {
      axios.post('/debit/delete', {
        id: ids
      })
        .then((res) => {
          // console.log(res)
          if (res.data === 'success') {
            this.$message({
              type: 'success',
              message: '정상적으로 삭제되었습니다.'
            })
            this.getList()
          }
          if (res.data === 'error') {
            this.$message({
              type: 'error',
              message: '처리상태가 "대기"일 경우에는 삭제 할 수 없습니다.'
            })
          }
        })
    },
    checkedAll (checked) {
      this.allChecked = checked
      for (const i in this.list) {
        this.list[i].selected = this.allChecked
      }
      this.getSelected()
    },
    selected (e) {
      console.log(this.list)
      for (const i in this.list) {
        if (!this.list[i].selected) {
          this.allChecked = false
          return
        } else {
          this.allChecked = true
        }
      }
    },
    getSelected () {
      const listIds = this.list.filter(item => item.selected === true)
      console.log(this.listIds)
      // for (const i in this.List) {
      //   if (this.list[i].selected) {
      //     listIds.push(this.list[i].listId)
      //   }
      // }
      return listIds
    }
    // update () {
    //   this.form.patch('/settings/profile').then(({ data: user }) => {
    //     this.$store.dispatch('auth/updateUser', { user })
    //   })
    // }
  }
}
</script>
