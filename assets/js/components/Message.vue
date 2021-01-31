<template>
  <div class="d-flex justify-content-end mb-4" v-if="yoda">
    <div class="msg_container yoda" v-html="content">
      {{ content }}
      <span class="msg_time">{{ time }}</span>
    </div>
    <div class="img_cont_msg">
      <img src="/img/yoda.png" class="rounded-circle avatar" alt="Yoda">
    </div>
  </div>
  <div class="d-flex justify-content-start mb-4" v-else>
    <div class="img_cont_msg">
      <img v-bind:src="avatarPath" class="rounded-circle avatar" v-bind:alt="avatar">
    </div>
    <div class="msg_container">
      {{ content }}
      <span class="msg_time" v-text="time">{{ time }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props:{
    content: {
      type: String,
      required: true
    },
    yoda: {
      type: Boolean,
      required: false,
      default: true
    },
    avatar: {
      type: String,
      required: false,
      default: 'yoda'
    },
    timestamp: {
      type: Number,
      required: false
    }
  },
  data() {
    return {
      avatarPath : '',
      time: ''
    };
  },
  mounted() {
    this.avatarPath = '/img/'+this.avatar+'.png'
    this.time = this.getTime()
  },
  methods: {
      getAvatar() {
          return '/img/yoda.png'
      },
      getTime() {
        if (!this.timestamp) {
          return ''
        }
        // update time in a script
        let time = new Date(this.timestamp * 1000)
        let now = new Date()
        let seconds = Math.round((now.getTime() - time.getTime())/1000);
        if (seconds < 60) {
          let self = this;
          setTimeout(function() {
            // give some extra time to writing message
            self.getTime()
          }, 1000)
          this.time = seconds < 10 ? 'now' : seconds + ' seconds ago';
        } else if (seconds < 60 * 60) {
          let minutes = Math.round(seconds / 60)
          let self = this;
          setTimeout(function() {
            // give some extra time to writing message
            self.getTime()
          }, 60*1000)
          this.time = minutes + ' minutes ago';
        } else {
          // show only date, for include date add time.toLocalDateString() concatenated
          this.time = time.toLocaleTimeString();
        }
        return this.time
      }
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
  .img_cont_msg{
    height: 40px;
    width: 40px;
  }
  .user_info span{
    font-size: 20px;
    color: white;
  }
  .user_info p {
    font-size: 10px;
    color: rgba(255,255,255,0.6);
    text-align: left;
  }
  .msg_container {
    margin-top: auto;
    margin-bottom: auto;
    margin-left: 10px;
    border-radius: 10px;
    background-color: #82ccdd;
    padding: 10px;
    position: relative;
    min-width: 150px;
    text-align: left;
  }
  .msg_container.yoda {
    margin-right: 10px;
    background-color: #78e08f;
  }
  .msg_time {
    position: absolute;
    left: 10px;
    right: unset;
    bottom: -17px;
    color: white;
    font-size: 12px;
    font-style: italic;
  }
  .msg_container.yoda .msg_time {
    left: unset;
    right: 10px;
  }
</style>