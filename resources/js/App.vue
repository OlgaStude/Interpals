<template>
    <div class="container">
        <div v-if="isLogged"></div>
        <div class="nav-bar" v-else>
            <router-link class="router logo" to="/"><img :src="'/storage/imgs/logo.png'" alt=""><span><span class="green_txt">Inter</span>Pals</span></router-link>
            <div id="nav_right">
                <router-link class="router" to="/login">Войти</router-link>
                <router-link class="router" to="/registration"><span class="green_txt">Присоедениться</span></router-link>
            </div>
        </div>
        <router-view></router-view>
        <div class="nav-bar nav-bar-logged" v-if="isLogged">
            <a class="router logo" :href="$router.resolve({name: 'Dashboard'}).href"><img :src="'/storage/imgs/logo.png'" alt=""><span><span class="green_txt">Inter</span>Pals</span></a>
            <div id="nav_right_logged">
                <a :href="$router.resolve({name: 'Possible_chats'}).href"><img :src="'/storage/imgs/people_icon.png'" alt=""></a>
                <a :href="$router.resolve({name: 'Users_chats'}).href"><img :src="'/storage/imgs/speach_bubble_icon.png'" alt=""></a>
                <a class="lower" @click="logout"><img :src="'/storage/imgs/logout_icon.png'" alt=""></a>
                <a :href="$router.resolve({name: 'UserPage', params: { id: user_id }}).href"><img class="pfp_nav" :src="'/storage/profile_pics/' + user_pfp" alt=""></a>
            </div>
        </div>
        
        <div class="footer">
            <div class="fooer_container">
                <div>
                    <p>О нас</p>
                    <p>Часто задаваемые вопросы</p>
                    <p>Обратная связь</p>
                </div>
                <div>
                    <img class="footer_img_1" src="/storage/imgs/youtube_icon.png" alt="">
                    <img class="footer_img_2" src="/storage/imgs/telegram_icon.png" alt="">
                </div>
            </div>
            <div id="credit_footer">
                <p>Использованиые изображения:</p>
                <p><a href="https://ru.freepik.com/free-photo/beautiful-lesbian-couple-celebrating-their-wedding-day-outdoors_57314062.htm">Изображение от freepik</a></p>
                <p><a href="https://ru.freepik.com/free-photo/medium-shot-contemplative-man-seaside_47696347.htm">Изображение от freepik</a></p>
                <p><a href="https://ru.freepik.com/free-photo/man-trekking-mountains_4145953.htm">Изображение от freepik</a></p>
                <p><a href="https://ru.freepik.com/free-photo/freedom-concept-with-hiker-mountain_4149026.htm">Изображение от freepik</a></p>
                <p><a href="https://ru.freepik.com/free-photo/molodaa-zensina-rabotausaa-iz-doma_20546284.htm">Изображение от freepik</a></p>
                <p><a href="https://ru.freepik.com/free-photo/medium-shot-man-reading-digital-magazine_38308664.htm">Изображение от freepik</a></p>
            </div>
            <p>© 2023 InterPals - <span class="diff_font">Онлайн сервис языкового обмена</span></p>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    

    body{
        padding: 0px;
        margin: 0px;
        overflow-x: hidden;
    }
    .container{
        z-index: -2;
        width: 99vw;
    }
    .pfp_nav{
        width: 100px;
        height: 100px;
        border-radius: 50px;
    }
        .nav-bar{
            display: flex;
            justify-content: space-between;
            padding: 48px 110px;
            font-family: "Roboto";
        }
        .nav-bar-logged{
            box-shadow: -8px 0px 12px 1px rgba(11, 14, 11, 0.41);
            box-sizing: border-box;
            height: 108px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
        }
        
            .router:visited, .router{
                color: black;
                text-decoration: none;
                font-size: 30px;
            }
                .green_txt{
                    color: #0D890D;
                }
                
                .logo{
                    display: flex;
                    align-items: center;
                    font-family: 'Roboto';
                }
                    .logo>span{
                        padding-top: 10px;
                    }
            #nav_right .router{
                margin-left: 54px;
            }
            #nav_right_logged{
                display: flex;
                align-items: center;
            }
            #nav_right_logged a{
                margin-left: 54px;
                display: block;
            }
            .lower{
                cursor: pointer;

            }
            #nav_right .router:hover{
                text-decoration: underline;
                text-underline-offset: 5px;
            }
            #nav_right .green_txt:hover{
                color: #0D890D;
                text-decoration: underline;
            }

    .footer{
        width: 100%;
        height: fit-content;
        padding-bottom: 20px;
        background-color: black;
        color: white;
        font-family: "Roboto";
    }
    #credit_footer{
        margin-left: 120px;
    }
    #credit_footer a:visited, #credit_footer a{
        color: #fff;
    }
        .fooer_container{
            display: flex;
            justify-content: space-between;
            padding: 67px 120px;
            font-size: 30px;
        }
        .fooer_container div{
            display: flex;
            column-gap: 81px;
        }
            .footer_img_1{
                width: 77px;
                height: 77px;
            }
            .footer_img_2{
                width: 67px;
                height: 67px;
                margin-top: 5px;
            }
        .footer>p{
            font-size: 24px;
            margin-left: 120px;
            margin-bottom: 51px;
        }
        .diff_font{
            font-family: 'jejuM';
        }

</style>

<script>

    export default{
        name: 'App',
        data(){
            return{
                isLogged: false,
                user_id: null,
                user_pfp: null
            }
        },
        created(){
            if(window.Laravel.isLogged){
                this.isLogged = true,
                this.user_id = window.Laravel.user.id,
                this.user_pfp = window.Laravel.user.pfp
            }
        }, methods: {
            logout (e) {
              e.preventDefault();
              this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/logout').then(response => {
                    if(response.data.status == 200){
                        window.location.href = '/';
                    } else {
                        console.log(response)
                    }
                }).catch(error => {
                    console.error(error)
                })
              });  
            }

        },
    }

</script>

