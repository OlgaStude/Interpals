<template>
   
    <div class="form_to_add form_user" v-if="form_is_on">
        <div>
            <div class="inside">
                <textarea  name="text"  id="text" v-model="form_text" cols="30" rows="10"></textarea>
                <strong>{{ errors.text }}</strong>
                <div>
                    <select @change="onChangeLang" name="" id="">
                        <option>Выберите язык</option>
                        <option :value="user.lang_s">{{ user.lang_s }}</option>
                        <option :value="user.lang_t">{{ user.lang_t }}</option>
                    </select>
                    <strong>{{ errors.lang }}</strong>
                    <button  @click="create_post">Создать пост</button>
                </div>
            </div>
            <img @click="close_form" src="/storage/imgs/close_btn.png" alt="">
        </div>
    </div>

<div class="container_user">
    
    <div class="user_info_container">
        
        <div class="user_info">
            <img :src="'/storage/profile_pics/' + user.pfp" alt="">
            <div>
                <p class="title">{{ user.name }} {{ user.surname }}</p>
                <p class="text">Изучает: {{ user.lang_s }}</p>
                <p class="text">Говорит: {{ user.lang_t }}</p>
            </div>
        </div>
        <a v-if="user.id != user_id" class="btn" :href="$router.resolve({name: 'Chat', params: { id: user.id }}).href">
            Начать переписку
        </a>
        <button v-if="user.id == user_id" @click="open_form">
            Создать пост
        </button>
    </div>
    <p class="posts_tittle">Посты пользователя:</p>
    <div class="posts">
        <div class="no_posts" v-if="posts.length < 1">
            <p>Постов пока нет</p>
        </div>
        <div v-for="index in posts.length" :key="index">
        <div class="post">
            <div class="post_body">
                <a :href="$router.resolve({name: 'UserPage', params: { id: posts[index-1].user_id }}).href"><img :src="'/storage/profile_pics/' + posts[index-1].pfp" alt=""></a>
                <div>
                    <p class="upper_line"><a :href="$router.resolve({name: 'UserPage', params: { id: posts[index-1].user_id }}).href">{{ posts[index-1].name }} {{ posts[index-1].surname }}</a><span>{{posts[index-1].created_at}}</span></p>
                    <p class="lower_line">{{ posts[index-1].text }}</p>
                </div>
            </div>
            <div class="leave_comment">
                <textarea ref="comment_text" name="" id="" cols="30" rows="10"></textarea>
                <strong>{{ errors.comment_text[index-1] }}</strong>
                <button @click="create_comment(posts[index-1].id, index)">Прокомметировать</button>
            </div>
            <div class="comments">
                <div v-if="posts[index-1].comments.length > 0" class="filter">
                    <select @change="filterComments(index, posts[index-1].id)" name="" ref="comment_filter" id="">
                        <option value="usefullness">Сначала популярные</option>
                        <option value="time">Сначала свежие</option>
                    </select>
                </div>
                <input type="text" v-else hidden ref="comment_filter">
                <div class="comment" v-for="commnet in posts[index-1].comments" :key="commnet">
                    <div class="comment_text">
                        <img class="comment_user_pfp" :src="'/storage/profile_pics/'+commnet.user_pfp" alt="">
                        <div>
                            <p class="upper_line"><a :href="$router.resolve({name: 'UserPage', params: { id: commnet.user_id }}).href">{{ commnet.user_name }} {{ commnet.user_surname }}</a></p>
                            <p class="lower_line">{{ commnet.text }}</p>
                        </div>
                    </div>
                    <div class="rate">
                            <span class="counter">{{ commnet.likes }}</span>
                            <img @click="like_comment(commnet.id, $event)" v-if="commnet.liked" class="liked" src="/storage/imgs/Like_red.png" alt="">
                            <img @click="like_comment(commnet.id, $event)" v-else class="not_liked" src="/storage/imgs/Like.png" alt="">
                            <span class="counter">{{ commnet.dislikes }}</span>
                            <img @click="dislike_comment(commnet.id, $event)" v-if="commnet.disliked" class="disliked dislike" src="/storage/imgs/Dislike_red.png" alt="">
                            <img @click="dislike_comment(commnet.id, $event)" v-else class="not_disliked dislike" src="/storage/imgs/Disike.png" alt="">
                    </div>
                </div>
                
            </div>
        </div>
        </div>
    </div>
</div>
</template>



<script>
  import axios from "axios"

export default{
    name: 'UserPage',
    data(){
        return{
            isLogged: false,
            user: {
                name: '',
                surname: '',
                lang_s: '',
                lang_t: '',
                id: 0,
            },
            owner_id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
            user_id: window.Laravel.user.id,
            form_is_on: false,
            form_text: '',
            form_lang: '',
            errors: {
                text: null,
                lang: null,
                comment_text: []
            },
            posts: null,
            write_comment: false
        }
    }, created(){
        if(window.Laravel.isLogged){
            this.isLogged = true
        }
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios.get('api/user/'+this.owner_id).then(response => {
            this.user = response.data;
        })
        this.$axios.get('api/posts/'+this.owner_id).then(response => {
            this.posts = response.data.data;
            for(let i = 0; i < this.posts.length; i++){
                this.errors.comment_text[i] = '';
            }
        })
        });        
        this.owner_id = window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
    }, methods: {
        write_comment_f(){
            this.write_comment = true;
        },
        open_form(){
            this.form_is_on = true
        },
        close_form(){
            this.form_is_on = false
        },
        onChangeLang(e){
            if (e.target.value != 'Выберите язык') {
                this.form_lang = e.target.value;
            } else {
                this.form_lang = null
            }
        },
        create_post(e){
            e.preventDefault();
            this.errors = {
                text: null,
                lang: null,
                comment_text: []
            }
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.post('api/makepost', {
                lang: this.form_lang,
                text: this.form_text
            }).then(response => {
                this.form_text = ''
                this.close_form();
                this.$axios.get('api/posts/'+this.owner_id).then(response => {
                    this.posts = null;
                    this.posts = response.data.data;
                })
            }).catch(err => {
                if (err.response.data.errors.text) {
                    this.errors.text = err.response.data.errors.text[0];
                }
                if (err.response.data.errors.lang) {
                    this.errors.lang = err.response.data.errors.lang[0];
                }
            })
         });           
        },
        create_comment(post_id, index) {
            this.errors.comment_text = [];
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.post('api/makecomment',
                {
                    post_id: post_id,
                    text: this.$refs['comment_text'][index-1].value,
                }
            ).then(response => {
            this.$axios.get('api/posts/'+this.owner_id).then(response => {
                this.posts = response.data.data;
                this.$refs['comment_text'][index -1].value = '';
            })
        }).catch((err) => {
            if (err.response.data.errors.text) {
                this.errors.comment_text[index-1] = err.response.data.errors.text[0];

            }
            });
         });           
        },
        like_comment(id, e){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/likecomment',
                    {
                        id: id
                    }
                ).then(response => {
                    if(e.target.classList.contains('not_liked')){
                        e.target.classList.remove('not_liked')
                        e.target.classList.add('liked')
                        e.target.setAttribute('src', '/storage/imgs/Like_red.png')
                        e.target.previousElementSibling.innerHTML = parseInt(e.target.previousElementSibling.innerHTML) + 1;
                        if(e.target.nextElementSibling.nextElementSibling.classList.contains('disliked')){
                            e.target.nextElementSibling.nextElementSibling.classList.remove('disliked')
                            e.target.nextElementSibling.nextElementSibling.classList.add('not_disliked')
                            e.target.nextElementSibling.nextElementSibling.setAttribute('src', '/storage/imgs/Disike.png')
                            e.target.nextElementSibling.innerHTML = parseInt(e.target.nextElementSibling.innerHTML) - 1;
                        }
                    }else{
                        e.target.classList.remove('liked')
                        e.target.classList.add('not_liked')
                        e.target.setAttribute('src', '/storage/imgs/Like.png')
                        e.target.previousElementSibling.innerHTML = parseInt(e.target.previousElementSibling.innerHTML) - 1;
                    }
                })
            });               
            },
            dislike_comment(id, e){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/dislikecomment',
                        {
                            id: id
                        }
                    ).then(response => {
                        if(e.target.classList.contains('not_disliked')){
                            e.target.classList.remove('not_disliked')
                            e.target.classList.add('disliked')
                            e.target.setAttribute('src', '/storage/imgs/Dislike_red.png')
                            e.target.previousElementSibling.innerHTML = parseInt(e.target.previousElementSibling.innerHTML) + 1;
                            if(e.target.previousElementSibling.previousElementSibling.classList.contains('liked')){
                                e.target.previousElementSibling.previousElementSibling.classList.remove('liked')
                                e.target.previousElementSibling.previousElementSibling.classList.add('not_liked')
                                e.target.previousElementSibling.previousElementSibling.setAttribute('src', '/storage/imgs/Like.png')
                                e.target.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML = parseInt(e.target.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML) - 1;
                            }
                        }else{
                            e.target.classList.remove('disliked')
                            e.target.classList.add('not_disliked')
                            e.target.setAttribute('src', '/storage/imgs/Disike.png')
                            e.target.previousElementSibling.innerHTML = parseInt(e.target.previousElementSibling.innerHTML) - 1;
                        }

                    })
                });             
            },
            filterComments(index, id){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/filtercomments',
                        {
                            post_id: id,
                            filter: this.$refs['comment_filter'][index -1].value
                        }).then(response => {
                            
                            this.posts[index-1].comments = response.data.data

                    })
                });             
            }
    },
    beforeRouteEnter(to, from, next) {

        // Если пользователь не авторизован

        if(!window.Laravel.user){
            return next("/");
        }

        // Если нет такого пользователя

        axios.get("/sanctum/csrf-cookie").then((response) => {     
            axios.get('api/user/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                if (response.data == 'no_user_found' ) {
                    return next("dashboard");
                }
                next();
            })
        });    
  },
}

</script>


<style>


.container_user{
margin-top: 188px;
font-family: "Roboto";
z-index: 1;
min-height: 600px;

}
.form_user{
    margin-top: -190px;
}
.form_to_add{
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: rgb(0, 0, 0, 0.2);
        z-index: 100;
    }
    .form_to_add>div{
        width: 1098px;
        height: fit-content;
        box-sizing: border-box;
        padding: 52px 73px;
        background-color: #fff;
        border-radius: 16px;
        margin: auto;
        margin-top: 100px;
        position: relative;
    }
    .form_to_add>div>img{
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }
    .form_to_add>div textarea{
        width: 954px;
        height: 40vh;
        margin: 0;
        margin-left: -15px;
        margin-bottom: 31px;
    }
    .form_to_add .inside div{
        display: flex;
        justify-content: space-between;
        position: relative;
    }
        .form_to_add .inside div strong{
            position: absolute;
            bottom: -20px;
            font-family: 'Roboto';
        }
    .form_to_add>div select{
        width: 518px;
        height: 57px;
        box-sizing: border-box;
        font-size: 32px;
        background-color: #fff;
        margin-left: -15px;
    }
    .form_to_add>div button{
        width: 345px;
        height: 57px;
        box-sizing: border-box;
        font-size: 32px;
        color: #0D890D;
        background-color: #fff;
        border: 2px solid black;
        border-radius: 16px;
        margin-right: -15px;
    }

.user_info_container{
    display: flex;
    justify-content: space-between;
    padding: 0px 115px;
}
    .user_info{
        display: flex;
        column-gap: 36px;
    }
        .user_info img{
            width: 218px;
            height: 218px;
            border-radius: 130px;
        }
        .user_info .title{
            font-size: 40px;
            margin-bottom: 52px;
            margin-top: 62px;
            line-height: 0;
        }
        .user_info .text{
            font-size: 28px;
            margin-bottom: 35px;
            line-height: 0;
        }
    .user_info_container button,.user_info_container .btn{
        color: #0D890D;
        background-color: #fff;
        border: 2px solid black;
        border-radius: 16px;
        font-size: 32px;
        padding: 5px 90px;
        height: 57px;
        font-family: "Roboto";
        box-sizing: border-box;
        margin-top: 30px;
        text-decoration: none;
    }
    .user_info_container button:hover, .user_info_container .btn:hover{
        outline: 6px solid black;
        text-decoration: underline;
        cursor: pointer;
    }
.posts_tittle{
font-size: 48px;
margin-left: 115px;
}

.posts{
    width: 95vw;
}
.post{
    padding-left: 115px;
    margin-bottom: 50px;
}
    .post_body{
        display: flex;
        column-gap: 32px;
    }
        .post_body img{
            height: 100px;
            width: 100px;
            border-radius: 50px;
            margin-top: 10px;
        }
        .upper_line{
            font-size: 40px;
            line-height: 0;
        }
            .upper_line a, .upper_line a:visited{
                color: #000;
                text-decoration: none;
            }
            .upper_line span{
                color: #9F9F9F;
                font-size: 28px;
                margin-left: 50px;
            }
        .lower_line{
            font-size: 32px;
            margin-top: 60px;
        }
    .leave_comment{
        display: flex;
        align-items: end;
        position: relative;
    }
    .leave_comment strong{
        position: absolute;
        bottom: -30px;
        left: 150px;
    }
    .post button{
        color: #0D890D;
        background-color: #fff;
        border: 2px solid black;
        border-radius: 16px;
        font-size: 32px;
        padding: 5px 40px;
        height: 57px;
        font-family: "Roboto";
        margin-top: 30px;
        margin-left: 130px;
    }
    .no_posts p{
        font-size: 30px;
        margin: auto;
        width: fit-content;
        margin-bottom: 90px;
        margin-top: 20px;        
    }
    textarea{
        width: 800px;
        height: 147px;
        border: 2px solid black;
        border-radius: 16px;
        margin-left: 130px;
        font-size: 32px;
        padding: 10px;
        margin-top: 20px;
    }
    
        .comments{
            margin-left: 120px;
            margin-right: 50px;
            margin-top: 70px;
        }
            .filter{
                margin-bottom: 40px;
            }
            .comment{
                display: flex;
                justify-content: space-between;
                margin-bottom: 74px;
            }
                .comment_text{
                    display: flex;
                    column-gap: 23px;
                }
                .comment_user_pfp{
                    height: 75px;
                    width: 75px;
                    border-radius: 50px;
                }
                .rate{
                    display: flex;
                }
                    .rate img{
                        width: 94px;
                        height: 70px;
                    }
                    .dislike{
                        margin-top: 15px;
                    }
                .counter{
                    font-size: 32px;
                    margin-top: 20px;
                    margin-left: 20px;
                }


</style>