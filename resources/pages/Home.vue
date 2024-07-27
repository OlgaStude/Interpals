<template>
    
    <div class="">
        <section class="banner">
            <h1><span class="green_txt">Inter</span>Pals</h1>
            <p>Онлайн-сервис языкового обмена</p>
            <a :href="$router.resolve({name: 'Registration'}).href">Начни уже сейчас!</a>
            <img class="banner_bg_img" src="storage/imgs/main_map.png" alt="">
        </section>
        <section class="explanation">
            <h1>Что же такое <span class="green_txt">Inter</span>Pals?</h1>
            <p class="p_1">Полноценное изучение иностранного языка невозможно без общения.</p>
            <p class="p_2">Но где найти хорошего собеседника, не имея возможности путешествовать<br>за границу?</p>
            <p class="p_3"><span class="green_txt">Inter</span>Pals предоставляет вам возможность находить собеседников по всему миру.</p>
            <p class="p_4">Ведите диалог и помогайте друг другу в изучении языков.</p>
            <p class="p_5">Выберите язык, который вы хотите изучать.</p>
            <p class="p_6">Затем выберите язык, с которым вы сможете помочь другим людям.</p>
            <p class="p_7">Общайтесь, тренируйте языковые навыки, исправляйте ошибки друг друга, давайте советы!</p>
            <img src="storage/imgs/exp_bg.png" class="explanation_bg" alt="">
        </section>
        <section class="find_people">
            <h1>Сотни комбинаций языков по свей планете</h1>
            <div id="fp_container">
                <div>
                    <div>
                    <img src="storage/imgs/user_main_1.png" alt="">
                        <div>
                            <p class="fp_title">Maria</p>
                            <p class="fp_p">Изучает:</p>
                            <p class="fp_underline fp_p">Русский</p>
                            <p class="fp_p">Обучает:</p>
                            <p class="fp_underline fp_p">Испанский</p>
                        </div>
                    </div>

                </div>
                <div>
                    <div>
                    <img src="storage/imgs/user_main_2.png" alt="">
                        <div>
                            <p class="fp_title">Артём</p>
                            <p class="fp_p">Изучает:</p>
                            <p class="fp_underline fp_p">Немецкий</p>
                            <p class="fp_p">Обучает:</p>
                            <p class="fp_underline fp_p">Русский</p>
                        </div>
                    </div>

                </div>
                <div>
                    <div>
                    <img src="storage/imgs/user_main_3.png" alt="">
                        <div>
                            <p class="fp_title">Nadia</p>
                            <p class="fp_p">Изучает:</p>
                            <p class="fp_underline fp_p">Китайский</p>
                            <p class="fp_p">Обучает:</p>
                            <p class="fp_underline fp_p">Итальянский</p>
                        </div>
                    </div>

                </div>
                <div>
                    <div>
                    <img src="storage/imgs/user_main_4.png" alt="">
                        <div>
                            <p class="fp_title">John</p>
                            <p class="fp_p">Изучает:</p>
                            <p class="fp_underline fp_p">Арабский</p>
                            <p class="fp_p">Обучает:</p>
                            <p class="fp_underline fp_p">Греческий</p>
                        </div>
                    </div>

                </div>
                <div>
                    <div>
                    <img src="storage/imgs/user_main_5.png" alt="">
                        <div>
                            <p class="fp_title">Lily</p>
                            <p class="fp_p">Изучает:</p>
                            <p class="fp_underline fp_p">Африкаанс</p>
                            <p class="fp_p">Обучает:</p>
                            <p class="fp_underline fp_p">Французский</p>
                        </div>
                    </div>

                </div>
                <div>
                    <div>
                    <img src="storage/imgs/user_main_6.png" alt="">
                        <div>
                            <p class="fp_title">Thomas</p>
                            <p class="fp_p">Изучает:</p>
                            <p class="fp_underline fp_p">Английский</p>
                            <p class="fp_p">Обучает:</p>
                            <p class="fp_underline fp_p">Шведский</p>
                        </div>
                    </div>

                </div>
                
            </div>
        </section>
        <section class="people_love_us">
            <h1>Люди нас любят!</h1>
            <div class="plu_container">
                <div>
                    <p class="plu_tittle">Артём</p>
                    <p class="plu_text">Обожаю этот сайт!Он очень удобный и мне удалось сильно прокачать свой уровень немецкого всего за пару недель. Мне всегда говорили: общение - ключ к обучению, но где его найти? Теперь я знаю где!</p>
                    <img src="storage/imgs/stars.png" alt="">
                </div>
                <div>
                    <p class="plu_tittle">Thomas</p>
                    <p class="plu_text">Just two years ago I couldn't even drem of writing like that in english! This platform helped me to find confidence, to practice, to be better. And I found new friends along the way!</p>
                    <img src="storage/imgs/stars.png" alt="">
                </div>
                <div>
                    <p class="plu_tittle">Maria</p>
                    <p class="plu_text">Я учю Русский язык недавно. Много чего ещё не знаю и не умею. Но я выучила здесь больше, чем где-либо ещё. Другие места медленее.</p>
                    <img src="storage/imgs/stars.png" alt="">
                </div>
                <div>
                    <p class="plu_tittle">Nadia</p>
                    <p class="plu_text">Imparare il cinese sembrava un sogno impossibile. Ero così disperato per una buona risorsa completamente gratuita, e poi l'ho trovata! Grazie mille!</p>
                    <img src="storage/imgs/stars.png" alt="">
                </div>
            </div>
        </section>
    </div>
</template>


<script>

    export default{
        name: 'Home',
        data(){
            return[

            ]
        }, created(){

        }, methods: {
            
        }
    }

</script>

<style>
    .banner{
        height: 974px;
        position: relative;
        color: black;
    }
        .banner>*{
            position: absolute;
        }
        .banner_bg_img{
            z-index: 0;
            top: 100px;
            width: 99vw;

        }
        .banner h1{
            font-family: 'Roboto';
            font-size: 128px;
            left: 20vw;
            top: 20px;
        }
            .green_txt{
                color: #0D890D;
            }
        .banner p{
            font-family: 'jejuM';
            font-size: 90px;
            width: 700px;
            left: 17vw;
            top: 160px;
        }
        .banner a, .banner a:visited{
            padding: 17px 42px;
            border: 4px solid black;
            border-radius: 16px;
            color: #0D890D;
            text-decoration: none;
            font-family: 'Roboto';
            font-size: 2vw;
            left: 31vw;
            bottom: 340px;
            z-index: 1;
        } 
            .banner a:hover{
                border: 6px solid black;
                text-decoration: underline;
            }


    .explanation{
        position: relative;
        font-family: 'Roboto';
        height: 110vw;
        margin-top: 400px;
        width: 99vw;
    }
        .explanation>img, .explanation>p{
            position: absolute;
        }
        .explanation img{
            top: 220px;
            width: 99vw;
        }
        .explanation h1{
            font-size: 65px;
            width: fit-content;
            margin: auto;
        }
        .explanation p{
            font-size: 36px;
        }
            .p_1{
                width: 835px;
                left: 412px;
                top: 10vw;
            }
            .p_2{
                width: 875px;
                left: 130px;
                top: 20vw;
            }
            .p_3{
                width: 455px;
                left: 270px;
                top: 35vw;
            }
            .p_4{
                width: 30vw;
                left: 330px;
                top: 50vw;
            }
            .p_5{
                width: 876px;
                right: 2vw;
                top: 62vw;
            }
            .p_6{
                width: 45vw;
                right: 10px;
                top: 70vw;
            }
            .p_7{
                width: 45vw;
                right: 10px;
                top: 85vw;
            }


    .find_people{
        margin-top: 200px;
        width: 99vw;
    }
    .find_people>h1{
        font-family: 'Roboto';
        font-size: 65px;
        margin: auto;
        width: fit-content;
    }
        #fp_container{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 30px 40px;
        }
            #fp_container>div{
                width: 487px;
                height: 278px;
                background-color: #E24A6F;
                border-radius: 17px;
                padding: 11px 9px;
                box-sizing: border-box;
                margin: 110px 80px 0 80px;

            }
            #fp_container>div>div{
                width: 466px;
                height: 260px;
                background-color: #fff;
                border-radius: 17px;
                display: flex;
                justify-content: space-between;
                font-family: 'jejuM';
                font-size: 24px;
                line-height: 0;
                text-align: right;
            }
                #fp_container>div>div img{
                    height: 174px;
                    width: 174px;
                    margin-top: 43px;
                    margin-left: 31px;
                }
                .fp_title{
                    font-family: 'Roboto';
                    font-size: 40px;
                    margin-right: 51px;
                    margin-top: 63px;
                }
                .fp_p{
                    margin-top: 35px;
                    margin-right: 51px;
                }
                    .fp_underline{
                        text-decoration: underline;
                    }



    .people_love_us{
        margin-top: 200px;
        width: 99vw;
    }
    .plu_container{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin: 30px 40px;
    }
        .people_love_us h1{
            font-family: 'Roboto';
            font-size: 65px;
            width: fit-content;
            margin: auto;
        }
        .people_love_us>div>div{
            width: 558px;
            text-align: center;
            margin: 110px 80px 0 80px;
        }
            .plu_tittle{
                font-family: 'Roboto';
                font-size: 40px;
                margin-bottom: 0;
            }
            .plu_text{
                font-family: 'jejuM';
                font-size: 24px;
                line-height: 30px;
            }
            .people_love_us>div img{
                margin-top: -30px;
            }


</style>