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
        マイページ
      </template>
      <template #content>
        <TabView>
          <TabPanel header="トピック">
            <div v-if="skeleton">
              <p v-for="n of 3" :key="n">
                <Skeleton width="10rem" class="p-mb-2" />
                <br>
              </p>
            </div>
            <p v-for="topic in user.topics" :key="topic.id" v-else>
              <router-link :to="`/topic/${topic.id}`">
                {{topic.body}}
              </router-link>
            </p>
          </TabPanel>
          <TabPanel header="コメント">
            <div v-if="skeleton">
              <p v-for="n of 5" :key="n">
                <Skeleton width="10rem" class="p-mb-2" />
                <br>
              </p>
            </div>
            <p v-for="comment in user.comments" :key="comment.id" v-else>
              <router-link :to="`/topic/${comment.topic_id}`">
                {{comment.body}}
              </router-link>
            </p>
          </TabPanel>
        </TabView>
      </template>
      <template #footer>
        <Button label="トピックを作成" v-on:click="toNewTopic" />
        <Button label="ログアウト" class="p-button-warning" v-on:click="logout" />
        <Button label="アカウント削除" class="p-button-danger" v-on:click="withdraw" />
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
// 21番 ダイアログのインポート
import Dialog from 'primevue/dialog'

import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'Userself',
  components: {
    TabView,
    TabPanel,
    // 21番 ダイアログ
    Dialog,
    Skeleton
  },
  data () {
    return {
      user: [],
      // 21番 ダイアログ
      display: false,
      skeleton: true,
      message: '',
      id: null
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('login')
      return
    }
    this.getUser()
    // this.getData()
  },
  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    toNewTopic () {
      this.$router.push('topic')
    },
    logout () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/logout')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/login')
            })
            .catch(err => {
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
    withdraw () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/withdraw')
          // /api/withdrawに接続成功時
            .then(res => {
              console.log(res)
              // キー：'authenticated', バリュー：'false'を削除
              localStorage.removeItem('authenticated', 'false')
              // urlをホーム画面へ遷移
              this.$router.push('/')
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch((err) => {
          alert(err)
        })
    },
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/user')
            .then((res) => {
              console.log(res)
              if (res.status === 200) {
                this.user = res.data
                this.skeleton = false
              } else {
                // console.log('取得失敗')
                // 21番 ダイアログ
                this.message = '取得失敗'
                this.display = true
              }
            })
        })
        .catch((err) => {
          // alert(err)
          // 21番 ダイアログ
          this.message = err
          this.display = true
        })
    }
    // getData () {
    //   axios.get('/sanctum/csrf-cookie')
    //     .then(() => {
    //       axios.get(`/api/user/${this.id}`)
    //         .then((res) => {
    //           console.log(res)
    //           if (res.status === 200) {
    //             this.data = res.data
    //           } else {
    //             // console.log('取得失敗')
    //             // 21番 ダイアログ
    //             this.message = '取得失敗'
    //             this.display = true
    //           }
    //         })
    //         .catch((err) => {
    //           // console.log(err)
    //           // 21番 ダイアログ
    //           this.message = err
    //           this.message = '失敗'
    //           this.display = true
    //         })
    //     })
    //     .catch((err) => {
    //       // alert(err)
    //       // 21番 ダイアログ
    //       this.message = err
    //       this.message = 'ネットワークエラー'
    //       this.display = true
    //     })
    // }
  }
}
</script>

<style lang="scss" scoped>
.p-card-footer {
  .p-button {
    margin-right: 10px;
  }
}
</style>
