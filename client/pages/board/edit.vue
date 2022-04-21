<template>
  <card>
    <div class="">
      <label for="title" class="text">제목 </label>
      <input v-model="title" type="text" class="form-control">
    </div>
    <div class="">
      <label for="contents" class="text mt-2">내용</label>
      <textarea v-model="contents" class="form-control" rows="10" />
    </div>
    <div class="mt-3 text-center">
      <el-button type="primary" icon="el-icon-check" round @click="update">
        저장
      </el-button>
      <el-button type="danger" icon="el-icon-refresh-left" round @click="cancel">
        취소
      </el-button>
    </div>
  </card>
</template>

<script>
// import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'Edit',
  data: () => ({
    edit: {},
    title: '',
    contents: ''

  }),
  mounted () {
    this.getEdit()
  },
  methods: {
    getEdit () {
      const editId = this.$route.params.id
      axios.get(`/board/edit/${editId}`)
        .then((res) => {
          // console.log(res)
          this.edit = res.data
          this.title = this.edit.title
          this.contents = this.edit.contents
          // console.log(this.edit.title)
        })
    },
    cancel () {
      this.$router.push({ name: 'board.list' })
    },
    update () {
      axios.put(`/board/${this.$route.params.id}`, {
        title: this.title,
        contents: this.contents
      })
        .then((res) => {
          // console.log(res)
          this.$message({
            type: 'success',
            message: '정상적으로 작성이 완료되었습니다.'
          })
          this.$router.push({ name: 'board.list' })
        })
    }
  }
}

</script>

<style>

</style>
