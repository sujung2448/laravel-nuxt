<template>
  <div>
    <table class="table table-bordered">
      <thead>
        <tr class="text-center">
          <th class="col-1">
            #
          </th>
          <th class="col-5">
            제목
          </th>
          <th class="col-1">
            작성자
          </th>
          <th class="col-3">
            작성일시
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in list" :key="item.id">
          <td class="text-center">
            {{ item.id }}
          </td>
          <td class="detail text-center" @click="detail(item.id)">
            {{ item.title }}
          </td>
          <td class="text-center">
            {{ item.user.name }}
          </td>
          <td class="text-center" scope="row">
            {{ $moment(item.created_at).format("YYYY-MM-DD kk:mm:ss") }}
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
import { mapGetters } from 'vuex'
import LaravelVuePagination from 'laravel-vue-pagination'
import axios from 'axios'

export default {
  name: 'List',
  components: {
    Pagination: LaravelVuePagination
  },
  data: () => ({
    list: [],
    laravelData: {}

  }),
  computed: mapGetters({
    user: 'auth/user'
  }),
  mounted () {
    this.getList()
  },
  methods: {
    getList (page = 1) {
      axios.get('/board/list?page=' + page)
        .then((res) => {
          // console.log(res)
          this.laravelData = res.data
          this.list = res.data.data
        })
    },
    detail (id) {
      this.$router.push({ name: 'board.show', params: { id } })
    }
  }
}
</script>

<style>
.detail {
  text-decoration: underline;
  cursor: pointer;
}

</style>
