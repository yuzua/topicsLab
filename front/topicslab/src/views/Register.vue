<template>
  <div>
    <!-- 21番　ダイアログの処理 -->
    <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{message}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
    </Dialog>
    <Card>
      <template #title>
        登録
      </template>
      <template #content>
        <div class="fields">
          <div class="p-field">
            <label for="name">ユーザー名</label>
            <InputText id="name" type="text" v-model="name" />
          </div>
          <div class="p-field">
            <label for="email">メールアドレス</label>
            <InputText id="email" type="email" v-model="email" />
          </div>
          <div class="p-field">
            <label for="password">パスワード</label>
            <InputText id="password" type="password" v-model="password" />
          </div>
        </div>
        <span>{{message}}</span>
        <div class="p-field">
          <Button icon="pi pi-check" label="登録" v-on:click="register" />
        </div>
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
// 21番 ダイアログのインポート
import Dialog from 'primevue/dialog'

export default {
  name: 'Register',
  // 21番 ダイアログ
  components: {
    Dialog
  },
  data () {
    return {
      // 21番 ダイアログ
      display: false,
      name: '',
      email: '',
      password: '',
      message: ''
    }
  },
  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    register () {
      const name = this.name.trim()
      const email = this.email.trim()
      const password = this.password.trim()
      if (!name || !email || !password) {
        this.message = '全て必須項目です。'
        return
      }

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/register', {
            name: this.name,
            email: this.email,
            password: this.password
          })
            .then((res) => {
              if (res.status === 201) {
                alert('ユーザー登録成功')
                this.$router.push('/login')
              } else {
                // 21番 ダイアログ
                this.message = 'ユーザー登録に失敗しました。'
                this.display = true
              }
            })
            .catch((err) => {
              // 21番 ダイアログ
              // console.log(err)
              this.message = 'ユーザー登録に失敗しました。'
              this.display = true
            })
        })
        .catch((err) => {
          // alert(err)
          // 21番 ダイアログ
          this.message = err
          this.display = true
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.p-card-content {
  .fields {
    text-align: center;
  }

  .p-field {
    display: block;

    label {
      display: inline-block;
      width: 10em;
      margin-bottom: 10px;
    }

    .p-button {
      margin-top: 20px;
      display: block;
      width: 100%;
    }
  }
}
</style>
