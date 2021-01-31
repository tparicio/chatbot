<template>
  <div>
    <h2 class="center">Toni Paricio ChatBot</h2>
    <div class="center help-block">powered by <a href="https://www.inbenta.com/es/productos/chatbot/" target="_blank">Inbenta</a></div>

    <ChatBot
        :messages="messages"
        :alerts="alerts">
    </ChatBot>

  </div>
</template>

<script>
import ChatBot from "./ChatBot";

export default {
  components: {
    ChatBot
  },
  data() {
    return {
      alerts: [],
      messages: []
    }
  },
  methods: {

  }
};
</script>

<style scoped>
  .center {
    text-align: center;
  }

  body,html{
    height: 100%;
    margin: 0;
    background: #7F7FD5;
    background: -webkit-linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
    background: linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
  }

  .contacts li{
    width: 100% !important;
    padding: 5px 10px;
    margin-bottom: 15px !important;
  }

  .user_info span{
    font-size: 20px;
    color: white;
  }
  .user_info p{
    font-size: 10px;
    color: rgba(255,255,255,0.6);
    text-align: left;
  }
</style>