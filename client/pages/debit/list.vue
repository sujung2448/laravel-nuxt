<template>
  <div>
    <table class="table table-bordered">
      <thead>
        <tr class="text-center">
          <th>
            <input id="checkAll" type="checkbox">
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
            <input type="checkbox" name="chk[]" class="chkbox" value="">
          </td>
          <td class="text-center" scope="row">
            {{ $moment(item.created_at).format("YYYY-MM-DD kk:mm:ss") }}
          </td>
          <td class="text-right">
            {{ item.amount }}
          </td>
          <td class="text-center">
            {{ item.status }}
          </td>
          <td class="text-center">
            <span class="" onclick="DeleteItem()" style="cursor:pointer">
              <i class="fa-solid fa-trash-can" />
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,
  computed: mapGetters({
    user: 'auth/user'
  }),

  data: () => ({
    list: []
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
    getList () {
      axios.get('/debit/list').then((res) => {
        // console.log(res.data.data)
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
