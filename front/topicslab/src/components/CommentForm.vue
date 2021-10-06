<template>
  <div>
    <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{message}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
    </Dialog>
    <Card>
      <template #content>
        <div class="p-field">
          <Textarea v-model="comment" :autoResize="true" rows="5" />
          <p>{{message}}</p>
        </div>
        <div class="p-field">
          <Button icon="pi pi-check" label="コメントする" v-on:click="submit" />
        </div>
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Dialog from 'primevue/dialog'

export default {
  name: 'CommentForm',
  components: {
    Dialog
  },
  props: {
    topicId: Number
  },
  data () {
    return {
      display: false,
      comment: '',
      message: ''
    }
  },
  methods: {
    closeBasic () {
      this.display = false
    },
    submit () {
      const comment = this.comment.trim()
      if (!comment) {
        this.message = '未記入(空白のみ)は送信できません。'
        return
      }

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/comment', {
            topicId: this.topicId,
            body: comment
          })
            .then((res) => {
              if (res.status === 201) {
                this.comment = ''
                this.$emit('sentComment', res.data)
              } else {
                this.message = '送信に失敗しました。'
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.message = '送信に失敗しました。'
              this.display = true
            })
        })
        .catch((err) => {
          alert(err)
          this.message = err
          this.display = true
        })
    }
  }
}
</script>

<style scoped>
.p-card.p-component {
  margin-top:  20px;
}
.p-field * {
  display: block;
  width: 100%;
}
</style>
