<template>
  <div class="d-flex justify-content-end mb-4" v-bind:class="type">
    <div class="msg_container yoda">
      {{ content }}
    </div>
    <div class="img_cont_msg">
      <img src="/img/yoda.png" class="rounded-circle avatar" alt="Yoda">
    </div>
  </div>
</template>

<script>
export default {
  props:{
    type: {
      type: String,
      require: true,
      default: 'writing'
    },
    content: {
      type: String,
      required: true
    }
  },
  data() {
    return {

    };
  },
  methods: {

  },
  mounted() {

  }
};
</script>

<style>
  .avatar{
    height: 40px;
    width: 40px;
    border:1px solid #CCCCCC;
    background: #DDDDDD;
  }
  .message_alerts .writing .msg_container{
    margin-top: auto;
    margin-bottom: auto;
    margin-left: 10px;
    border-radius: 10px;
    background-color: #78e08f;
    padding: 10px;
    position: relative;
    min-width: 150px;
    text-align: left;
    opacity: 0.5;
  }
  .message_alerts .error .msg_container {
    background-color: #dc3545;
  }
</style>