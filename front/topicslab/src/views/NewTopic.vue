<template>
  <Card>
    <template #title>
      新しいトピックを投稿しよう
    </template>
    <template #content>
      <div class="p-field">
        <label for="title">トピックタイトル</label>
        <InputText v-model="title" id="title" type="text" aria-describedby="title-help" />
        <small id="title-help">タイトルを入力してください。</small>
        <span class="messages">{{messages.title}}</span>
      </div>
      <div class="p-field">
        <label for="title">トピック内容</label>
        <Textarea v-model="body" :autoResize="true" rows="10" />
        <span class="messages">{{messages.body}}</span>
      </div>
      <div class="p-field">
        <Button icon="pi pi-check" label="保存" v-on:click="submit" />
        <span class="messages">{{messages.submit}}</span>
      </div>
    </template>
  </Card>
  <!-- 21番ダイアログの処理 -->
  <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{messages.submit}}</p>
            <p>{{messages.title}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
  </Dialog>
</template>

<script>
import axios from '@/supports/axios'
// 21番 ダイアログのインポート
import Dialog from 'primevue/dialog'

export default {
  name: 'NewTopic',
  // 21番 ダイアログ
  components: {
    Dialog
  },
  data () {
    return {
      // 21番 ダイアログ
      display: false,
      title: '',
      body: '',
      messages: {
        submit: '',
        title: '',
        body: ''
      }
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('login')
    }
  },
  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    submit () {
      const title = this.title.trim()
      if (!title) {
        this.messages.title = '未記入(空白のみ)は送信できません。'
      }
      const body = this.body.trim()
      if (!body) {
        this.messages.body = '未記入(空白のみ)は送信できません。'
      }

      if (!title || !body) return

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/topic', {
            // ここがデータを渡している
            title: title,
            body: body
          })
            .then((res) => {
              if (res.status === 201) {
                this.$router.push(`/topic/${res.data.id}`)
              } else {
                // 21番 ダイアログ
                this.messages.submit = '送信に失敗しました。'
                this.display = true
              }
            })
            .catch((err) => {
              // console.log(err)
              // 21番 ダイアログ
              this.messages.submit = err
              this.messages.title = '実験中'
              this.display = true
            })
        })
        .catch((err) => {
          // alert(err)
          // 21番 ダイアログ
          this.messages.submit = err
          this.display = true
        })
    }
  }
}
</script>

<style scoped>
.p-field * {
  display: block;
  width: 100%;
}

.messages {
  color: red;
}
</style>
