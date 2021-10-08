<template>
  <div>
    <Dialog header="Header" v-model:visible="display" :style="{width: '50vw'}">
            <p>{{message}}</p>
            <template #footer>
                <Button label="No" icon="pi pi-times" @click="closeBasic" class="p-button-text"/>
                <Button label="Yes" icon="pi pi-check" @click="closeBasic" autofocus />
            </template>
    </Dialog>
    <div v-if="skeleton">
    <Card>
        <template #content>
          <div>
            <span class="topic-date">投稿日：<Skeleton width="10rem" class="p-mb-2" /></span>
            <h2>
                <Skeleton height="2rem" class="p-mb-2" />
            </h2>
          </div>
        </template>
    </Card>
    <Card>
        <template #content>
          <div>
            <span class="topic-date">投稿日：<Skeleton width="10rem" class="p-mb-2" /></span>
            <h2>
                <Skeleton height="2rem" class="p-mb-2" />
            </h2>
          </div>
        </template>
    </Card>
    </div>
    <div v-else>
    <Card v-for="topic in topics" :key="topic.id">
        <template #content>
          <div>
            <span class="topic-date">投稿日：{{moment(topic.created_at)}}</span>
            <h2>
              <router-link :to="`/topic/${topic.id}`">
                {{topic.title}}
              </router-link>
            </h2>
          </div>
        </template>
    </Card>
    </div>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import moment from 'moment'
// 21番 ダイアログのインポート
import Dialog from 'primevue/dialog'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'AllTopics',
  // 21番 ダイアログ
  components: {
    Dialog,
    Skeleton
  },
  data () {
    return {
      topics: [],
      // 21番 ダイアログ
      message: '',
      skeleton: true
    }
  },
  mounted () {
    this.getAllTopics()
  },
  methods: {
    // 21番 ダイアログ
    closeBasic () {
      this.display = false
    },
    moment: function (date) {
      return moment(date).format('YYYY/MM/DD HH:mm:SS')
    },
    getAllTopics () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/topics')
            .then((res) => {
              if (res.status === 200) {
                this.topics.splice(0)
                this.topics.push(...res.data)
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
  }
}
</script>

<style lang="scss" scoped>
.p-card.p-component {
  margin-bottom: 20px;
}
.p-card-content {
  .topic-date {
    font-size: 80%;
  }
}
</style>
