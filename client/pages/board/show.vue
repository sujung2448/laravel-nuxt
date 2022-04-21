<template>
  <card>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">
            <h4>{{ show.title }}</h4>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row" style="height:300px">
            {{ show.contents }}
          </th>
        </tr>
      </tbody>
    </table>
    <div v-if="show.user_id === user.id" class="mt-3 text-center">
      <el-button type="success" icon="el-icon-edit" round @click="edit(show.id)">
        수정
      </el-button>
      <el-button type="danger" icon="el-icon-delete" round @click="destroy(show.id)">
        삭제
      </el-button>
    </div>
  </card>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'Show',
  data: () => ({
    show: {}

  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  mounted () {
    this.getShow()
  },

  methods: {
    getShow () {
      const showId = this.$route.params.id
      axios.get(`/board/show/${showId}`)
        .then((res) => {
          // console.log(res)
          this.show = res.data
          // console.log(res.data)
        })
    },
    destroy (id) {
      axios.delete(`board/${this.$route.params.id}`)
        .then((res) => {
          // console.log(res)
          this.$message({
            type: 'success',
            message: '정상적으로 삭제되었습니다.'
          })
          this.$router.push({ name: 'board.list' })
        })
    },
    edit () {
      this.$router.push({ name: 'board.edit' })
    }
  }

}
</script>

<style>

</style>
