<template>
    <div class="form_to_add form_dash" v-if="form_is_on">
        <div>
            <div class="inside">
                <textarea  name="text"  id="text" v-model="form_text" cols="30" rows="10"></textarea>
                <strong>{{ errors.text }}</strong>
                <div>
                    <select @change="onChangeLanginform" name="" id="">
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
    <div class="dashboard_content">
        <div class="lang_filter">
            <div>
                <p>Показывать в летне посты...</p>
                <select @change="onChangeLang" name="" id="" >
                    <option value="no_filter">на всех моих языках</option>
                    <option :value="user.lang_s">только {{ user.lang_s }}</option>
                    <option :value="user.lang_t">только {{ user.lang_t }}</option>
                </select>
            </div>
            <button @click="form_is_on = true">
                Создать пост
            </button>
        </div>
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
                    <strong>{{ errors.comment_txt[index-1] }}</strong>
                    <button @click="create_comment(posts[index-1].id, index)">Прокомметировать</button>
            </div>
                <div v-if="posts[index-1].comments.length > 0" class="comments">
                    <div class="filter">
                        <select @change="filterComments(index, posts[index-1].id)" name="" ref="comment_filter" id="">
                            <option value="usefullness">Сначала популярные</option>
                            <option value="time">Сначала свежие</option>
                        </select>
                    </div>
                    <div class="comment" v-for="commnet in posts[index-1].comments" :key="commnet">
                        <div class="comment_text">
                            <img class="comment_user_pfp" :src="'/storage/profile_pics/'+ commnet.user_pfp" alt="">
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


<style>

.make_green{
    background-color: greenyellow;
}

.form_dash{
    margin-top: -110px;
}
.inside button:hover{
    outline: 6px solid black;
    text-decoration: underline;
    cursor: pointer;
}
.inside strong{
    font-family: 'Roboto';
}
.dashboard_content{
    margin-top: 108px;
    font-family: "Roboto";
    min-height: 600px;
}
    .lang_filter{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 60px 115px;
    }
        option{
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            color: black;
        }
        .lang_filter>div{
            display: flex;
            justify-content: space-between;
            align-items: center;
            column-gap: 6px;
        }
        .lang_filter>div p{
            width: fit-content;
            font-size: 32px;
        }
        .lang_filter>div select{
            font-size: 32px;
            width: 30vw;
            font-family: "Roboto";
            color: #0D890D;
        }
        .lang_filter button{
            color: #0D890D;
            background-color: #fff;
            border: 2px solid black;
            border-radius: 16px;
            font-size: 32px;
            padding: 5px 70px;
            height: 57px;
            font-family: "Roboto";
        }
        .lang_filter button:hover{
            outline: 6px solid black;
            text-decoration: underline;
            cursor: pointer;
        }
        
                        .counter{
                            font-size: 32px;
                            margin-top: 20px;
                            margin-left: 20px;
                        }


</style>


<script>

    export default{
        name: 'Home',
        data(){
            return {
                posts: {},
                user: window.Laravel.user,
                filter_is_on: false,
                filter_lang: null,
                comment_text: null,
                write_comment: false,
                form_is_on: false,
                form_text: '',
                form_lang: '',
                errors: {
                    text: null,
                    lang: null,
                    comment_txt: []
                },
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.get('api/posts').then(response => {
                    this.posts = response.data.data;
                    for(let i = 0; i < this.posts.length; i++){
                        this.errors.comment_txt[i] = '';
                    }
                })
            });            
        }, methods: {
            close_form(){
                this.form_is_on = false
            },
            onChangeLanginform(e) {
                if (e.target.value != 'Выберите язык') {
                    this.form_lang = e.target.value;
                } else {
                    this.form_lang = null
                }
            },

            // Вывод постов на определённом языке

            onChangeLang(e) {
                this.errors.comment_txt = [];
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    if (e.target.value == 'no_filter') {
                        this.filter_lang = null;
                        this.filter_is_on = false;
                        this.posts = '';
                        this.$axios.get('api/posts/').then(response => {
                            this.posts = response.data.data;
                        })
                    } else {
                        this.filter_lang = e.target.value;
                        this.filter_is_on = true;
                        this.posts = [];
                        this.$axios.get('api/posts/').then(response => {
                            for(let i = 0; i<response.data.data.length; i++){
                                if(response.data.data[i].lang == this.filter_lang){
                                    this.posts.push(response.data.data[i]);
                                }
                            }
                        })
                    }
                });               
            },
            create_post(e){
                e.preventDefault();
                this.errors = {
                    text: null,
                    lang: null,
                    comment_txt: []
                }
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/makepost', {
                        lang: this.form_lang,
                        text: this.form_text
                    }).then(response => {
                        this.form_text = ''
                        this.close_form();
                        this.$axios.get('api/posts/').then(response => {
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
                this.errors.comment_txt = [];
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/makecomment',
                        {
                            post_id: post_id,
                            text: this.$refs['comment_text'][index-1].value,
                        }
                    ).then(response => {
                    this.$axios.get('api/posts').then(response => {
                        this.posts = response.data.data;
                        this.$refs['comment_text'][index -1].value = '';
                    })
                    }).catch((err) => {
                        if (err.response.data.errors.text) {
                            this.errors.comment_txt[index-1] = err.response.data.errors.text[0];
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
        },beforeRouteEnter(to, from, next) {

            // Если пользователь не авторизован

            if(!window.Laravel.user){
                return next("/");
            }
            next();
        }
    }

</script>

<style>
    .comment{
        padding-left: 30px;
    }
</style>