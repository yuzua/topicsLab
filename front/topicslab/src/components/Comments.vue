<template>
  <div>
    <Fieldset v-for="comment in comments" :key="comment.id">
      <template #legend>
        <span v-if="skeleton">
          <Skeleton width="5rem" class="p-mb-2" />
        </span>
        <span v-else>
          <router-link :to="`/user/${comment.user.id}`">{{comment.user.name}}</router-link>
        </span>
        <!-- <span>{{comment.user.name}}</span> -->
      </template>
      <div class="comment-text" v-if="skeleton">
        <Skeleton class="p-mb-2" />
      </div>
      <div class="comment-text" v-else>
        {{comment.body}}
      </div>

      <!-- いいねボタンを表示 -->
      <Button label="いいね"  iconPos="right" class="p-button p-component p-button-icon-only p-button-rounded p-button-help p-button-outlined pi pi-heart" type="button"/>

    </Fieldset>
  </div>
</template>

<script>
import Skeleton from 'primevue/skeleton'
export default {
  name: 'Comments',
  components: {
    Skeleton
  },
  props: {
    comments: Array
  }
  // data () {
  //   return {
  //     skeleton: true
  //   }
  // }
}
</script>

<style lang="scss" scoped>
.p-fieldset {
  margin-top: 20px;
}

.comment-text {
  white-space:pre-wrap;
}

.p-button {
  display: flex;
  margin-top: 20px;
}

</style>
