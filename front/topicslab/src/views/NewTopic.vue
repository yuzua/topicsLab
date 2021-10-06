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
  <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{message.submit}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
  </Dialog>
</template>

<script>
import axios from '@/supports/axios'
import Dialog from 'primevue/dialog'

export default {
  name: 'NewTopic',
  components: {
    Dialog
  },
  data () {
    return {
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
            title: title,
            body: body
          })
            .then((res) => {
              if (res.status === 201) {
                this.$router.push(`/topic/${res.data.id}`)
              } else {
                this.messages.submit = '送信に失敗しました。'
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.messages.submit = '送信に失敗しました。'
              this.display = true
            })
        })
        .catch((err) => {
          alert(err)
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
