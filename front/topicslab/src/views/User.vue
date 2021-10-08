<template>
  <div>
    <!-- 21番 ダイアログの処理 -->
    <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{message}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
    </Dialog>
    <Card>
      <template #content>
        <div v-if="skeleton">
          <Skeleton width="10rem" class="p-mb-2" />
        </div>
        <div v-else>
          {{user.name}}
        </div>
        <TabView>
          <TabPanel header="トピック">
            <div v-if="skeleton">
                <p v-for="n of 3" :key="n">
                  <Skeleton width="10rem" class="p-mb-2" />
                  <br>
                </p>
            </div>
            <div v-else>
              <div v-if="user.topics.length">
                <p v-for="topic in user.topics" :key="topic.id">
                  <router-link :to="`/topic/${topic.id}`">
                    {{topic.body}}
                  </router-link>
                </p>
              </div>
              <div v-else>
                <p>何もありません</p>
              </div>
            </div>
          </TabPanel>
          <TabPanel header="コメント">
            <div v-if="skeleton">
              <p v-for="n of 5" :key="n">
                <Skeleton width="10rem" class="p-mb-2" />
                <br>
              </p>
            </div>
            <div v-else>
              <div v-if="user.comments.length">
                <p v-for="comment in user.comments" :key="comment.id">
                  <router-link :to="`/topic/${comment.topic_id}`">
                    {{comment.body}}
                  </router-link>
                </p>
              </div>
              <div v-else>
                <p>何もありません</p>
              </div>
            </div>
          </TabPanel>
        </TabView>
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
  name: 'user',
  components: {
  // 21番 ダイアログ
    Dialog,
    TabView,
    Skeleton,
    TabPanel
  },
  data () {
    return {
      id: null,
      // 21番 ダイアログ
      comment: '',
      display: false,
      skeleton: true,
      user: []
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }

    this.id = this.$route.params.id
    if (!this.id) {
      alert('不正なIDです。')
    }
    this.getUser()
  },
  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/user/${this.id}`)
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
