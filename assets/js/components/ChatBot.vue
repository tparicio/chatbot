<template>
  <div>
    <div class="card">
      <div class="card-header msg_head">
        <div class="d-flex bd-highlight">
          <div class="img_cont">
            <img src="/img/vader.png" class="rounded-circle avatar big">
            <span class="online_icon"></span>
          </div>
          <div class="user_info">
            <span>Chat with Yoda</span>
            <p><span>{{messagesCount}}</span> Messages</p>
          </div>
        </div>
      </div>
      <div id="messages_container" class="card-body msg_card_body">
        <div v-for="item in messages" class="messages_history">
          <Message
              :content="item.content"
              :avatar="item.avatar"
              :yoda="item.yoda"
              :timestamp="item.timestamp"
          ></Message>
        </div>
        <div v-for="item in mutableAlerts" class="message_alerts">
          <MessageAlert
              :type="item.type"
              :content="item.content"
          ></MessageAlert>
        </div>
      </div>
      <div class="card-footer">
        <div class="input-group">
          <div class="input-group-append">
            <a class="input-group-text force_btn" @click="forceMessage"><i class="fas fa-jedi"></i></a>
          </div>
          <textarea @keyup.enter="sendMessage" @keyup="updateCharCounter"
              id="message"
              name="message"
              class="form-control type_msg"
              placeholder="Type your message..."
              maxlength="500"
              v-model="content">
          </textarea>
          <div class="input-group-append">
            <a class="input-group-text send_btn" @click="sendMessage">
              <i class="far fa-paper-plane"></i>
            </a>
          </div>
        </div>
        <div class="chars-counter">
          <span v-text="chars">0</span>/500
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Message from "./Message";
import MessageAlert from "./MessageAlert";

export default {
  components: {
    Message,
    MessageAlert
  },
  data() {
    return {
      chars: 0,
      content: "",
      messagesCount: this.messages.length,
      mutableAlerts: this.alerts,
      quotes: [
          // get random quotes from another API or external source
          'May the force be with you.',
          'Fear is the path to the dark side. Fear leads to anger. Anger leads to hate. Hate leads to suffering.',
          'Once you start down the dark path, forever will it dominate your destiny. Consume you, it will',
          'Always pass on what you have learned',
          'Patience you must have my young Padawan',
          'In a dark place we find ourselves, and a little more knowledge lights our way.',
          'Powerful you have become, the dark side I sense in you.',
          'Train yourself to let go of everything you fear to lose.',
          'Feel the force!',
          'Truly wonderful the mind of a child is.',
          'Do or do not. There is no try.',
          'Great warrior. Wars not make one great.',
          'The dark side clouds everything. Impossible to see the light, the future is.',
          'To be Jedi is to face the truth, and choose. Give off light, or darkness, Padawan. Be a candle, or the night.',
          'Control, control, you must learn control!'
      ]
    }
  },
  props: {
    messages: {
      type: Array,
      required: true
    },
    alerts: {
      type: Array,
      required: true
    }
  },
  mounted() {
    this.initChatBot()
  },
  methods: {
    initChatBot: function() {
      let xmlHttp = new XMLHttpRequest();
      xmlHttp.open("get", "/chatbot/messages");
      let self = this
      xmlHttp.onreadystatechange = function() {
        console.log(xmlHttp)
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200)
        {
          let response = JSON.parse(xmlHttp.response)
          if (response.result && response.messages) {
            response.messages.forEach(function(message) {
              // add each message to chat
              self.addMessage(message)
            });
          }
        }
      }
      xmlHttp.send();
    },
    addAlert: function(alert) {
      this.mutableAlerts.push(alert)
    },
    sendMessage: function(event) {
      console.log(this.message)
      if (this.isEmptyOrSpaces()) {
        this.addAlert({
          type: 'content',
          content: 'You, send an empty message, cannot.'
        })
        return false;
      }
      let message = {
        yoda: false,
        content: this.content,
        avatar: 'vader',
        timestamp: (new Date()).getTime() / 1000
      }
      this.addMessage(message);
      this.submit(message);
      this.content = ''
      this.updateCharCounter()
    },
    submit: function(message) {
      this.addAlert({
        type: 'writing',
        content: 'Writing response, I am...'
      })

      // AJAX message submitted in pure javascript, alternatives with jQuery or axios
      let formData = new FormData();
      formData.append('content', message.content);

      let xmlHttp = new XMLHttpRequest();
      xmlHttp.open("post", "/chatbot/submit");
      let self = this
      xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200)
        {
          let response = JSON.parse(xmlHttp.response)
          if (response.result) {
            setTimeout(function() {
              // empty the writing messages
              self.mutableAlerts = [];
              let answers = response['answers'];
              // answers can be more than one
              answers.forEach(function(answer) {
                self.addMessage(answer);
              });
            }, 1000);
          }
        }
      }
      xmlHttp.send(formData);

    },
    forceMessage: function(event) {
      this.addMessage({
        yoda: true,
        content: this.getRandomYodaQuote(),
        avatar: 'yoda'
      })
    },
    addMessage: function(message) {
      this.messages.push(message)
      setTimeout(function() {
        // scroll chat but after message was added
        let container = document.getElementById("messages_container");
        container.scrollTop = container.scrollHeight;
      }, 100);
      this.messagesCount++
    },
    getRandomYodaQuote: function() {
      return this.quotes[Math.floor(Math.random() * this.quotes.length)];
    },
    isEmptyOrSpaces: function() {
      return this.content === null || this.content.match(/^ *$/) !== null;
    },
    updateCharCounter: function () {
      this.chars = this.content.length
    }
  }
};
</script>

<style scoped>
.avatar.big {
  width : 68px;
  height: 68px;
  border: 3px solid #CCCCCC;
}

.send_btn{
  border-radius: 0 15px 15px 0 !important;
  background-color: rgba(0,0,0,0.3) !important;
  border:0 !important;
  color: white !important;
  cursor: pointer;
  height: 100%;
  text-decoration: none;
}
.force_btn{
  border-radius: 15px 0 0 15px !important;
  background-color: rgba(0,0,0,0.3) !important;
  border:0 !important;
  color: white !important;
  cursor: pointer;
  height: 100%;
  text-decoration: none;
}

body,html{
  height: 100%;
  margin: 0;
  background: #7F7FD5;
  background: -webkit-linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
  background: linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
}

.card{
  height: 500px;
  border-radius: 15px !important;
  background-color: rgba(0,0,0,0.4) !important;
}
.msg_card_body{
  overflow-y: auto;
  padding: 2rem 2rem 0 2rem;
  scroll-behavior: smooth;
}
.card-header{
  border-radius: 15px 15px 0 0 !important;
  border-bottom: 0 !important;
}
.card-footer{
  border-radius: 0 0 15px 15px !important;
  border-top: 0 !important;
}
.type_msg{
  background-color: rgba(0,0,0,0.3) !important;
  border:0 !important;
  color:white !important;
  height: 60px !important;
  overflow-y: auto;
}
.type_msg:focus{
  box-shadow:none !important;
  outline:0px !important;
}

.contacts li{
  width: 100% !important;
  padding: 5px 10px;
  margin-bottom: 15px !important;
}
.avatar{
  height: 40px;
  width: 40px;
  border:1px solid #CCCCCC;
}
.img_cont{
  position: relative;
  height: 70px;
  width: 70px;
}
.online_icon{
  position: absolute;
  height: 15px;
  width:15px;
  background-color: #4cd137;
  border-radius: 50%;
  bottom: 0.2em;
  right: 0.4em;
  border:1px solid white;
}
.user_info{
  margin-top: auto;
  margin-bottom: auto;
  margin-left: 15px;
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
.msg_head{
  position: relative;
}
.chars-counter {
  text-align: left;
  margin-left: 42px;
  color: white;
  font-size: 10px;
}
</style>