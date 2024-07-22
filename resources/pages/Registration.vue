<template>
    <div class="register_content">
        <img src="storage/imgs/map_simple.png" alt="">
        <h1>Зарегестрироваться</h1>
        <form action="" enctype="multipart/form-data">
            <input type="text" id="name" v-model="name" placeholder="name">
            <strong>{{ errors.name }}</strong>
            <input type="text" id="surname" v-model="surname" placeholder="surname">
            <strong>{{ errors.surname }}</strong>
            <input type="email" id="email" v-model="email" placeholder="email">
            <strong>{{ errors.email }}</strong>
            <input type="password" id="password" v-model="password" placeholder="password">
            <strong>{{ errors.password[0] }}</strong>
            <input type="password" id="password_confirmation" v-model="password_confirmation" placeholder="password_confirmation">
            <strong>{{ errors.password[1] }}</strong>
            <label class="custom-file-upload input-file">
            <input @change="show_name()" class="file_upload"  type="file" id="pfp" ref="pfp">
                <span id="file_selected">{{ file_name }}</span>
                </label>
            <strong>{{ errors.pfp }}</strong>
            <p>Я хочу учить</p>
            <select @change="onChangeLang_s" name="" id="lang_s">
                <option>Выберите язык</option>
                <option v-for="lang of langs" :value="lang.nang_name">{{ lang.lang_name }}</option>
            </select>
            <strong>{{ errors.lang_s }}</strong>
            <p>Я могу говорить</p>
            <select @change="onChangeLang_t" name="" id="lang_s">
                <option>Выберите язык</option>
                <option v-for="lang of langs" :value="lang.nang_name">{{ lang.lang_name }}</option>
            </select>
            <strong>{{ errors.lang_t }}</strong>
            <button type="submit" @click="register">Зарегестрироваться</button>
            <p class="to_login">Уже с нами? <router-link class="to_login" to="/login">Войдите</router-link>!</p>
        </form>
    </div>
</template>

<style>


.register_content{
        position: relative;
        height: 1220px;
        font-family: "Roboto";
        width: 99vw;
    }
        .register_content h1{
            font-size: 65px;
            left: 611px;
            width: fit-content;
            margin: 50px auto;
        }
        .register_content>form{
            position: relative;
            z-index: 10;
            width: 580px;
            margin: auto;
        }
            .register_content>form input{
                width: 100%;
                height: 43px;
                font-size: 32px;
                padding-left: 13px;
                border: 0;
                border-bottom: 2px solid black;
                background-color: rgb(240, 248, 255, 0);
                margin-top: 28px;
                margin-bottom: 4px;
            }
        .register_content>img{
            position: absolute;
            z-index: 0;
            width: 100%;
        }
        .file_upload{
            display: none;
        }
        label, form button{
            width: 100%;
            height: 48px;
            border: 2px solid black;
            border-radius: 16px;
            font-size: 32px;
            color: #0D890D;
            display: block;
            text-align: center;
            padding-top: 5px;
            cursor: pointer;
            margin-top: 28px;
            margin-bottom: 4px;
            box-sizing: border-box;
            background-color: #fff;
            margin-left: 7px;
        }
        .register_content>form p{
            font-size: 32px;
            margin-top: 32px;
            line-height: 0;
            margin-left: 7px;
        }
        select{
            width: 100%;
            height: 48px;
            font-size: 32px;
            padding-left: 19px;
            padding-right: 19px;
            border: 2px solid black;
            border-radius: 16px;
            -moz-appearance:none; /* Firefox */
            -webkit-appearance:none; /* Safari and Chrome */
            appearance:none;
            margin-left: 7px;
            margin-bottom: 4px;
        }
            select option{
                width: 0px;
                font-size: 14px;
            }
        form strong{
            padding-left: 10px;
            padding-top: 20px;
            box-sizing: border-box;
            color: #E24A6F;
            font-size: 24px;
        }

        p.to_login{
            padding-left: 105px;
        }
        .to_login, .to_login:visited{
            color: black;
            
        }

</style>


<script>

    export default{
        name: 'Registration',
        data(){
            return{
                name: '',
                surname: '',
                email: '',
                password: '',
                password_confirmation: '',
                errors: {
                    name: null,
                    surname: null,
                    email: null,
                    password: [],
                    password_confirmation: null,
                    pfp: null,
                    lang_s: null,
                    lang_t: null,
                },
                langs: {},
                lang_s: '',
                lang_t: '',
                file_name: 'Загрузите фото'
            }    
        }, created(){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('api/langs').then(response => {
                this.langs = response.data;
            })
                 })           
        }, methods: {
            show_name(){
                this.file_name = this.$refs.pfp.files[0].name
            },
            register(e){
                e.preventDefault();
                this.errors = {
                    name: null,
                    surname: null,
                    email: null,
                    password: [],
                    password_confirmation: null,
                    pfp: null,
                    lang_s: null,
                    lang_t: null,
                };
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/register', {
                        name: this.name,
                        surname: this.surname,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        pfp: this.$refs.pfp.files[0],
                        lang_s: this.lang_s,
                        lang_t: this.lang_t,
                    }, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    }).then(response => {
                        if(response.data.status == 200){
                            window.location.href = '/user/' + response.data.user_id
                        } else {
                            this.error = response.data.message
                        }
                    }).catch(err => {
                        if(err.response.data.errors.email){
                            this.errors.email = err.response.data.errors.email[0]
                        }
                        if(err.response.data.errors.password){
                            this.errors.password = err.response.data.errors.password
                        }
                        if(err.response.data.errors.name){
                            this.errors.name = err.response.data.errors.name[0]
                        }
                        if(err.response.data.errors.surname){
                            this.errors.surname = err.response.data.errors.surname[0]
                        }
                        if(err.response.data.errors.pfp){
                            this.errors.pfp = err.response.data.errors.pfp[0]
                        }
                        if(err.response.data.errors.lang_s){
                            this.errors.lang_s = err.response.data.errors.lang_s[0]
                        }
                        if(err.response.data.errors.lang_t){
                            this.errors.lang_t = err.response.data.errors.lang_t[0]
                        }
                    });
                })
            },
            onChangeLang_s(e){
                this.lang_s = e.target.value;
            },
            onChangeLang_t(e){
                this.lang_t = e.target.value;
            }
        },
        beforeRouteEnter(to, from, next) {

            // Если пользователь авторизован

            if (window.Laravel.isLogged) {
                return next("user/" + window.Laravel.user.id);
            }
            next();
        },
        
    }

</script>