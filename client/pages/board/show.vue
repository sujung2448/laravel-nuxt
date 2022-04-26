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
    <div class="comment">
      <p>댓글({{ commentList.length }})</p>
      <div class="commentItem">
        <el-input v-model="comment" maxlength="40" show-word-limit placeholder="댓글을 입력해 주세요." />
        <el-button type="primary" size="mini" round @click="commentSave">
          등 록
        </el-button>
      </div>
      <div v-for="item in commentList" :key="item.id" class="commentUser">
        <div class="">
          <i class="el-icon-user-solid">
            {{ item.user.name }}
            {{ $moment(item.created_at).format("YYYY-MM-DD kk:mm:ss") }}
          </i>
        </div>
        <div class="commentList row">
          <div>{{ item.comment }}</div>
          <div v-if="item.user_id === user.id" class="commentIcon">
            <i class="el-icon-edit mr-3" @click="toggleOnOff(item.id)" />
            <i class="el-icon-delete" @click="commentDelete(item.id)" />
          </div>
        </div>
        <div v-if="item.user_id === user.id" class="">
          <div v-if="isStatusOn == item.id" class="">
            <el-input v-model="commentEdit" type="textarea" :rows="5" maxlength="40" show-word-limit placeholder="댓글을 입력해 주세요." />
            <span class="commentBtn">
              <el-button type="success" size="mini" round @click="commentUpdate(item.id)">
                수정
              </el-button>
              <el-button type="danger" size="mini" round @click="commentCancel">
                취소
              </el-button>
            </span>
          </div>
        </div>
      </div>
    </div>
  </card>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
// import LaravelVuePagination from 'laravel-vue-pagination'

export default {
  name: 'Show',
  // components: {
  //   Pagination: LaravelVuePagination
  // },
  data: () => ({
    show: {},
    comment: '',
    commentList: [],
    isStatusOn: false,
    commentEdit: ''
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  mounted () {
    this.getShow()
    // this.getList()
  },

  methods: {
    getShow () {
      const showId = this.$route.params.id
      axios.get(`/board/show/${showId}`)
        .then((res) => {
          // console.log(res)
          // this.laravelData = res.data.comment
          this.show = res.data.board
          this.commentList = res.data.comment
          console.log(this.commentList.length)
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
    },
    commentSave () {
      axios.post('/comment/store', {
        comment: this.comment,
        boardId: this.$route.params.id // params.id를 같이보내야됨.
      })
        .then((res) => {
          // console.log(res)
          this.$message({
            type: 'success',
            message: '정상적으로 작성이 완료되었습니다.'
          })
          this.comment = ''
          this.getShow()
        })
    },
    // getList (page = 1) {
    //   axios.get('/comment/list?page=' + page)
    //     .then((res) => {
    //       // console.log(res)
    //       // this.laravelData = res.data
    //       this.commentList = res.data.data
    //       // console.log(this.commentList)
    //     })
    // }
    commentDelete (id) {
      // const commentListId = this.commentList.id
      axios.delete(`comment/${id}`)
        .then((res) => {
          // console.log(res)
          if (res.data === 'success') {
            this.$message({
              type: 'success',
              message: '정상적으로 삭제되었습니다.'
            })
            this.getShow()
          }
          // if (res.data === 'error') {
          //   this.$message({
          //     type: 'error',
          //     message: 'error'
          //   })
          // }
        })
    },
    toggleOnOff (id) {
      this.commentEdit = ''
      if (this.isStatusOn === id) {
        this.isStatusOn = false
      } else {
        this.isStatusOn = id
      }
    },
    commentUpdate (id) {
      axios.put(`/comment/${id}`, {
        comment: this.commentEdit
      })
        .then((res) => {
          // console.log(res)
          this.$message({
            type: 'success',
            message: '정상적으로 작성이 완료되었습니다.'
          })
          this.isStatusOn = false
          this.getShow()
        })
    },
    commentCancel () {
      this.isStatusOn = false
    }
  }
}

</script>

<style>
.el-input {
  margin-right: 10px;
  width: 70%;
  /* display: block; */
}
.comment {
  margin-top: 50px;
}
.commentItem {
  margin-top: 10px;
  display: flex;
  align-items: baseline;
}
.commentUser {
  font-size: 15px;
  border-bottom: 1px solid rgb(175, 175, 175);
  margin-top: 10px;
}
.commentList {
  margin-left: 5px;
  justify-content: space-between;
}
.commentIcon {
  margin-right: 20px;
  cursor: pointer;
}
.commentBtn {
  justify-content: end;
  display: flex;
  margin-bottom: 10px;
  margin-top: 10px;
}

</style>
