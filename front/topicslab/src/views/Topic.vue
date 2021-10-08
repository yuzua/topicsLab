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
        <div v-if="skeleton"><Skeleton height="2rem" class="p-mb-2" /></div>
        <div v-else>{{topic.title}}</div>
      </template>
      <template #content>
        <div class="body-text" v-if="skeleton">
          <Skeleton class="p-mb-2" />
        </div>
        <div class="body-text" v-else>
          {{topic.body}}
        </div>

        <!-- いいね数を表示 -->
        <!-- <span class="like-btn__text">{{ comment.likes_count }}</span> -->
        <!-- いいねボタンを表示 -->
        <Button @click="favorite()" label="いいね"  iconPos="right" class="p-button p-component p-button-icon-only p-button-rounded p-button-help p-button-outlined pi pi-heart" type="button"/>

      </template>
      <template #footer>
        <span>
          <router-link :to="`/user/${user.id}`">{{user.name}}</router-link>
        </span>
      </template>
    </Card>
    <Comments :comments="this.comments" />
    <CommentForm :topicId="this.topic.id" @sentComment="receiveComment" />
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Comments from '@/components/Comments'
import CommentForm from '@/components/CommentForm'

// 21番 ダイアログのインポート
import Dialog from 'primevue/dialog'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'Topic',
  // 21番 ダイアログ
  components: {
    Comments,
    CommentForm,
    Dialog,
    Skeleton
  },
  data () {
    return {
      // 21番 ダイアログ
      display: false,
      skeleton: true,
      topic: {},
      user: {},
      comments: [],
      message: '',
      id: null
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('login')
      return
    }
    this.id = this.$route.params.id
    if (!this.id) {
      alert('不正なIDです。')
    }
    this.getTopic()
  },
  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    getTopic () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/topic/${this.id}`)
            .then((res) => {
              if (res.status === 200) {
                this.topic = res.data
                this.user = this.topic.user
                this.comments.splice(0)
                this.comments.push(...this.topic.comments)
                this.skeleton = false
                // this.skeleton.push(...this.topic.skeleton)
                // this.skeleton.push(...this.skeleton)
              } else {
                // console.log('取得失敗')
                // 21番 ダイアログ
                this.message = '取得失敗'
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
    },
    receiveComment (comment) {
      this.comments.push(comment)
    }
    // receiveSkelton (skeleton) {
    //   this.skeleton.push(skeleton)
    // }
  }
}
</script>

<style scoped>
.body-text {
  white-space:pre-wrap;
}
.p-card-footer span {
  text-align: right;
  display: block;
}

.p-button {
  display: flex;
  margin-top: 20px;
}
</style>
