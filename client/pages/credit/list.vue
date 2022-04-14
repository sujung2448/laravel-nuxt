<template>
  <div>
    <table class="table table-bordered">
      <thead>
        <tr class="text-center">
          <th>
            <!-- <input id="checkAll" type="checkbox"> -->
            <el-checkbox v-model="checkAll" :indeterminate="isIndeterminate"
                         @change="handleCheckAllChange"
            >
              Check all
            </el-checkbox>
          </th>
          <th>신청일</th>
          <th>금액</th>
          <th>상태</th>
          <th />
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in list" :key="index">
          <td class="text-center">
            <!-- <input type="checkbox" name="chk[]" class="chkbox" value=""> -->
            <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
              <el-checkbox v-for="city in cities" :key="city" :label="city">
                <!-- {{ city }} -->
              </el-checkbox>
            </el-checkbox-group>
          </td>
          <td class="text-center" scope="row">
            {{ $moment(item.created_at).format("YYYY-MM-DD kk:mm:ss") }}
          </td>
          <td class="text-right">
            {{ item.amount }}
          </td>
          <td class="text-center">
            <span v-if="item.status === 1" class="badge badge-success px-2">완료</span>
            <span v-else-if="item.status === 2" class="badge badge-danger px-2">취소</span>
            <span v-else-if="item.status === 0" class="badge badge-primary px-2">대기</span>
          </td>
          <td class="text-center">
            <!-- <span class="" onclick="DeleteItem()" style="cursor:pointer"> -->
            <i class="el-icon-delete-solid" />
            <!-- </span> -->
          </td>
        </tr>
      </tbody>
    </table>
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
  scrollToTop: false,
  data: () => ({
    list: [],
    laravelData: {}
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
      axios.get('/credit/list?page=' + page)
        .then((res) => {
          this.laravelData = res.data
          this.list = res.data.data
        })
    }

    // update () {
    //   this.form.patch('/settings/profile').then(({ data: user }) => {
    //     this.$store.dispatch('auth/updateUser', { user })
    //   })
    // }
  }
}
</script>
