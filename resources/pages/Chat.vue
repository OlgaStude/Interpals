<template>
    <div class="chat_container">
        <h1>Диалог с {{ penpal.name }} {{ penpal.surname }}:</h1>
        <div class="dialog" id="dialog">
        <div v-for="chat_message in messages" v-if="messages.length != 0">
            <div v-if="chat_message.messager_id == penpal_id" class="other_user">
                <img :src="'/storage/profile_pics/'+chat_message.messager_pfp" alt="">
                <div class="text">
                    <div class="upper_line">
                        <p class="name">{{ chat_message.messager_name }} {{ chat_message.messager_surname }}</p>
                        <p class="date">{{ chat_message.time }}</p>
                    </div>
                    <p class="lower_line">
                        {{ chat_message.text }}
                    </p>
                </div>
            </div>
            <div v-else class="this_user">
                <img class="user_pfp_chat" :src="'/storage/profile_pics/'+chat_message.messager_pfp" alt="">
                <div class="text">
                    <div class="upper_line">
                        <p class="name">{{ chat_message.messager_name }} {{ chat_message.messager_surname }}</p>
                        <p class="date">{{ chat_message.time }}</p>
                        <img v-if="chat_message.read_msg" src="/storage/imgs/read_msg.png" alt="">
                        <img v-else src="/storage/imgs/unread_msg.png" alt="">
                    </div>
                    <p class="lower_line">
                        {{ chat_message.text }}
                    </p>
                </div>
            </div>
        </div>
        <div class="empty" v-else>
            <p>Вы ещё не начали переписку</p>
        </div>
        </div>
    </div>
    <div class="buttom_form" id="buttom_form">
        <textarea v-model="chat_message" placeholder="Ваше сообщение здесь" name="" id="" cols="30" rows="10"></textarea>
        <p>{{ errors.message }}</p>
        <button  @click="send_message">Отправить</button>
    </div>
  </template>
  



  <script>
  import axios from "axios"
  export default {
    name: "Chat",
    data() {
      return {
        'user_pfp': window.Laravel.user.pfp,
        user_id: window.Laravel.user.id,
        penpal_id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
        chat_message: '',
        errors: {
            message: null
        },
        messages: null,
        penpal: null,
        invalid_user: false
      }
    },
    created() {
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {

            // Вывод сообщений при загрузке

            this.$axios.get('api/readmessages/0/'+this.penpal_id+'/'+this.user_id).then(response => {
                this.messages = response.data.data
                setTimeout(() => {
                        const element = document.getElementById('dialog');
                        element.scrollTop = element.scrollHeight;
                    }, 500);
            })

            // Данные о собеседнике

            this.$axios.get('api/user/'+this.penpal_id).then(response => {
                this.penpal = response.data;
            })

        });   

        // Принимает сигнал о появлении вовых сообщений
        // проверяет, если они предназначенны для этой
        // переписки, отправляет запрос на получение,
        // если да

        Echo.private(`chat`)
            .listen('NewMessage', (e) => {
                if(this.penpal_id == e.user_1 && this.user_id == e.user_2 || this.penpal_id == e.user_2 && this.user_id == e.user_1){
                    this.$axios.get("/sanctum/csrf-cookie").then((response) => {                  
                        this.$axios.get('api/readmessages/1/'+this.penpal_id+'/'+this.user_id).then(response => {
                        
                        })
                    });                      
                    
                }
                
        });    
          
        // Получает новые сообщения и выводит их

        Echo.private('chat_read').listen('ReadMessage', (e) => {
            if(this.penpal_id == e.chatMessages.reciewer_id && this.user_id == e.chatMessages.sender_id || this.penpal_id == e.chatMessages.sender_id && this.user_id == e.chatMessages.reciewer_id){                
                this.messages = e.chatMessages
                setTimeout(() => {
                    const element = document.getElementById('dialog');
                    element.scrollTop = element.scrollHeight;
                }, 500);
            }                   
        });
        
    },
    methods: {
        send_message(){
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.post("api/sendmessage", {
              message: this.chat_message,
              receiver_id: this.penpal_id,
            })
            .then((response) => {
                this.chat_message = ''
                this.$axios.get("api/getmessages/"+this.penpal_id)
                .then((response) => {})
            })
            .catch((err) => {
              if (err.response.data.errors.message) {
                this.errors.message = err.response.data.errors.message[0];
              }
            });
         });                       
        },
        
    },
    beforeRouteEnter(to, from, next) {

        // Если пользователь не авторизован

        if(!window.Laravel.user){
            return next("/");
        }

        // Если id пользователя и собеседника совпадают

        if(window.Laravel.user.id == window.location.href.substring(window.location.href.lastIndexOf('/') + 1)){
            return next("user/" + window.Laravel.user.id);
        }

        // Если нет такого пользователя(собеседника)

        axios.get("/sanctum/csrf-cookie").then((response) => {        
            axios.get('api/user/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                if (response.data == 'no_user_found' ) {
                    return next("user/" + window.Laravel.user.id);
                }
                next();
            })
        });                
  },
  };
  </script>

<style>

.dialog{
    height: 65vh;
    overflow-y: scroll;
}
.empty{
    width: fit-content;
    margin: auto;
    margin-top: 100px;
    font-size: 24px;
}
    .chat_container{
        margin-top: 108px;
        font-family: "Roboto";
        padding: 60px 0 0 115px;
        min-height: 500px;
    }
        .chat_container h1{
            font-size: 40px;
            margin: 0;
        }
        .other_user{
            width: 40vw;
            background-color: #A6E1A6;
            border-radius: 16px;
            margin-left: 5vw;
            padding: 21px 35px;
            display: flex;
            column-gap: 25px;
            margin-bottom: 40px;
        }
            .other_user img{
                height: 60px;
                width: 60px;
                border-radius: 50px;
                margin-top: 20px;
            }
            .other_user .upper_line{
                display: flex;
                align-items: center;
                margin-top: 20px;
                column-gap: 30px;
            }
                .other_user .name{
                    font-size: 28px;
                }
                .other_user .date{
                    font-size: 18px;
                    margin-top: 20px;
                }
            .other_user .lower_line{
                font-size: 22px;
                margin-top: 20px;
            }

            .this_user{
                width: 40vw;
                background-color: #fff;
                border: 2px solid black;
                border-radius: 16px;
                margin-left: 40vw;
                padding: 21px 35px;
                display: flex;
                column-gap: 25px;
                margin-bottom: 40px;
                
            }
                .this_user>img{
                    height: 60px;
                    width: 60px;
                    margin-top: 20px;
                }
                .user_pfp_chat{
                    width: 100px;
                    border-radius: 130px;
                }
                .this_user .upper_line{
                    display: flex;
                    align-items: center;
                    margin-top: 20px;
                    column-gap: 77px;
                }
                    .this_user .upper_line img{
                        height: 20px;
                        width: 20px;
                        margin-left: -50px;
                    }
                    .this_user .name{
                        font-size: 28px;
                    }
                    .this_user .date{
                        font-size: 18px;
                        margin-top: 20px;
                    }
                .this_user .lower_line{
                    font-size: 22px;
                    margin-top: 20px;
                }

    .buttom_form{
        border-top: 2px solid black;
        padding: 7px 170px;
        box-sizing: border-box;
        display: flex;
        column-gap: 70px;
        width: 99vw;
        bottom: 0;
        background-color: #fff;
    }
        .buttom_form textarea{
            height: 80px;
            width: 887px;
            padding: 10px 23px;
            box-sizing: border-box;
        }
        .buttom_form button{
            height: 80px;
            width: 406px;
            box-sizing: border-box;
            color: #0D890D;
            font-family: "Roboto";
            font-size: 32px;
            background-color: #fff;
            border: 2px solid black;
            border-radius: 16px;
            margin-top: 20px;
        }
        .buttom_form button:hover{
            outline: 6px solid black;
            text-decoration: underline;
            cursor: pointer;
        }


</style>