<template>
  <card :title="$t('your_info')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')" />

      <!-- Name -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">신청금액</label>
        <div class="col-md-7 mb-3">
          <input v-model="form.amount"
                 :class="{ 'is-invalid': form.errors.has('amount') }"
                 type="text" name="amount"
                 class="form-control"
          >
          <has-error :form="form" field="amount" />
        </div>

        <div class="col-md-7 offset-md-3">
          <span class="btn btn-info btn-sm"
                @click="addAmount(10000)"
          >10000
          </span>
          <span class="btn btn-info btn-sm"
                @click="addAmount(10000)"
          >10000
          </span>
          <span class="btn btn-info btn-sm"
                @click="addAmount(10000)"
          >10000
          </span>
          <span class="btn btn-info btn-sm"
                @click="resetAmount"
          >Reset
          </span>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="success">
            신청
          </v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  data: () => ({
    form: new Form({
      amount: 10000
    })

  }),

  head () {
    return { title: this.$t('settings') }
  },

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    // this.form.keys().forEach((key) => {
    //   this.form[key] = this.user[key]
    // })
  },

  methods: {
    update () {
      this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(() => {
        if (this.form.amount < 10000) {
          this.$message({
            type: 'error',
            message: '신청 금액을 확인해주세요'
          })
          return
        }
        this.form.patch('/credit/store').then(({ data: user }) => {
          this.$store.dispatch('auth/updateUser', { user })
          this.$message({
            type: 'success',
            message: 'sdfds.'
          // duration: 0,
          // showClose:true
          })
          this.form.amount = 10000
          this.$router.push({ name: 'credit.list' })
        })
      }).catch(() => {

      })
    },
    addAmount (val) {
      this.form.amount = parseInt(this.form.amount || 0) + parseInt(val)
    },
    resetAmount () {
      this.form.amount = 0
    }

  }
}
</script>
