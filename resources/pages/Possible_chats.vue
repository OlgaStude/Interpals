<template>
    <div class="PP_container">
        <h1>Вы можете начать диалог:</h1>
        <div class="possible_people" v-for="user in users">
            <div class="person">
                <div>
                    <a :href="$router.resolve({name: 'UserPage', params: { id: user.id }}).href"><img :src="'/storage/profile_pics/'+user.pfp" alt=""></a>
                    <p><a :href="$router.resolve({name: 'UserPage', params: { id: user.id }}).href">{{ user.name }} {{ user.surname }}</a></p>
                </div>
                <a class="btn" :href="$router.resolve({name: 'Chat', params: { id: user.id }}).href">Написать</a>
            </div>
        </div>
    </div>
  </template>
  



  <script>
export default {
    name: "P_chats",
    data() {
      return {
        users: null
      }
    },
    created() {
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {     
            this.$axios.get('api/getusers').then(response => {
                this.users = response.data;
            })
        });           
    },
    beforeRouteEnter(to, from, next) {

        // Если пользователь не авторизован

        if(!window.Laravel.user){
            return next("/");
        }
        next();

    }
  };
  </script>

<style>

    .PP_container{
        margin-top: 108px;
        font-family: "Roboto";
        padding: 60px 115px;
        min-height: fit-content;
        min-height: 500px;
    }
    .PP_container h1{
        font-size: 48px;
    }
        .person{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
            .person>div{
                display: flex;
                align-items: center;
                column-gap: 33px;
                font-size: 40px;
            }
            .person img{
                width: 100px;
                height: 100px;
                border-radius: 50px;
            }
            .person p a, .person p a:visited{
                text-decoration: none;
                color: #000;
            }
            .person .btn{
                color: #0D890D;
                width: 308px;
                height: 60px;
                box-sizing: border-box;
                font-size: 32px;
                background-color: #fff;
                border: 2px solid black;
                border-radius: 16px;
                text-decoration: none;
                text-align: center;
                padding-top: 7px;
            }
            .person .btn:hover{
                outline: 6px solid black;
                text-decoration: underline;
            }
        


</style>