<template>
    <div class="PP_container">
        <h1>Ваши диалоги:</h1>
        <div class="no_chats" v-if="users.length < 1 || users == null">
            <p>Пока нет диалогов</p>
        </div>
        <div class="possible_people" v-for="user in users">
            <div v-if="user != null" class="person">
                <div>
                    <img :src="'/storage/profile_pics/'+user.pfp" alt="">
                    <p><a :href="$router.resolve({name: 'UserPage', params: { id: user.id }}).href">{{ user.name }} {{ user.surname }}</a></p>
                </div>
                <a class="btn" :href="$router.resolve({name: 'Chat', params: { id: user.id }}).href">Написать</a>
            </div>
        </div>
    </div>
  </template>
  



  <script>
  export default {
    name: "E_chats",
    data() {
      return {
        users: null
      }
    },
    created() {
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {     
            this.$axios.get('api/getexistantchats').then(response => {
                this.users = response.data;
            })
        });                  
    },beforeRouteEnter(to, from, next) {

        // Если пользователь не авторизован

        if(!window.Laravel.user){
            return next("/");
        }
        next();
    }
  };
  </script>

<style>

        .no_chats{
            font-size: 24px;
            font-weight: 600;
            width: fit-content;
            margin: 80px auto;
        }


</style>