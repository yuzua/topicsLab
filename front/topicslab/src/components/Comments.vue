<template>
  <div v-if="skeleton">
    <Fieldset>
      <template #legend>
        <span>
          <Skeleton width="5rem" class="p-mb-2" />
        </span>
      </template>
      <div class="comment-text" >
        <Skeleton class="p-mb-2" />
      </div>
    </Fieldset>
  </div>
  <div v-else>
    <Fieldset v-for="comment in comments" :key="comment.id">
      <template #legend>
        <span>
          <router-link :to="`/user/${comment.user.id}`">{{comment.user.name}}</router-link>
        </span>
        <!-- <span>{{comment.user.name}}</span> -->
      </template>
      <div class="comment-text">
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
    comments: Array,
    skeleton: Boolean
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
