<template>
  <div>
    <!-- 21番ダイアログの処理 -->
    <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{message}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
    </Dialog>
    <Card>
      <template #title>
        ログイン
      </template>
      <template #content>
        <div class="fields">
          <div class="p-field">
            <label for="email">メールアドレス</label>
            <InputText id="email" type="email" v-model="email" />
          </div>
          <div class="p-field">
            <label for="password">パスワード</label>
            <InputText id="password" type="password" v-model="password" />
          </div>
        </div>
        <span class="message">{{message}}</span>
        <div class="p-field">
          <Button icon="pi pi-check" label="ログイン" v-on:click="login" />
        </div>
        <div class="p-field">
          <a href="http://localhost:8080/register" class="p-button" >ユーザー登録</a>
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
  name: 'Login',
  // 21番 ダイアログ
  components: {
    Dialog
  },
  data () {
    return {
      // 21番 ダイアログ
      display: false,
      email: '',
      password: '',
      error: false,
      message: ''
    }
  },

  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    login () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/login', {
            email: this.email,
            password: this.password
          })
            .then((res) => {
              if (res.status === 200) {
                console.log('ログイン成功')
                localStorage.setItem('authenticated', 'true')
                this.$router.push('/')
              } else {
                // 21番 ダイアログ
                this.message = 'ログインに失敗しました。'
                this.display = true
              }
            })
            .catch((err) => {
              // console.log(err)
              // 21番 ダイアログ
              this.message = err
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

  .message {
    color: red;
  }
}
</style>
