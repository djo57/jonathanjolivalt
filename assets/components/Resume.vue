<script setup>
import {ref, onMounted} from "vue"
import { gsap } from "gsap"

const resumes = ref(false)
const emit = defineEmits(['updateResume'])

const getResume = async() => {
    try{
        await fetch("/api/resume/get", {"method": "GET"})
        .then(response => response.json())
        .then(result => {
            resumes.value = result
        })
    } catch(error){
        console.log(error)
    }
}

onMounted(async () => {
    await getResume()
    emit('updateResume', resumes.value)
    let boxes = gsap.utils.toArray(".boxes .box")
    var position = 0

    boxes.forEach((box) => {
        gsap.set(box, {
            backgroundPosition: position
        })
        position += 50
    })

    /* SQUARE */
    gsap.to(".boxes .box li", {
        "--gsap-rotation": "360deg",
        duration: 8,
        ease: "linear",
        stagger:{
            each: 0.1,
            repeat: -1,
            from: "random"
        }
    })
    gsap.to(".boxes .box li", {
        "--gsap-border-radius": "10px",
        duration: 4,
        ease: "linear",
        stagger:{
            each: 0.1,
            repeat: -1,
            from: "random",
            yoyo: true
        }
    })
})

</script>
<template>
    <div class="sec sec-resume">
        <h1>Résumé</h1>
        <div class="boxes content">
            <div class="square square1"></div>
            <div class="square square2"></div>
            <div class="square square3"></div>
            <div class="box" v-for="title, name in resumes">
                <h4>{{ name }}</h4>
                <ul>
                    <li v-for="resume, index in title">{{ resume }}<span class="delimiter" v-if="index < title.length - 1"></span></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
.boxes {
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    perspective:2400px;
    clear: both;
}
.boxes .box {
    flex: 1;
    margin: 50px 2%;
    border-radius: 15px;
    border: var(--box-border);
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background: rgb(255,255,255);
    background: -moz-linear-gradient(38deg, rgba(255,255,255,1) 0%, rgba(212,212,212,1) 100%);
    background: -webkit-linear-gradient(38deg, rgba(255,255,255,1) 0%, rgba(212,212,212,1) 100%);
    background: linear-gradient(38deg, rgba(255,255,255,1) 0%, rgba(212,212,212,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff",endColorstr="#d4d4d4",GradientType=1); 
}
.boxes .box h4{
    padding: 2rem 0;
    text-align: center;
    font-size: 2rem;
    color: white;
    border-bottom: var(--box-border);
    border-radius: 15px 15px 0 0;
    background: var(--color1);
    background: -moz-linear-gradient(38deg, var(--color1) 0%, var(--color3) 100%);
    background: -webkit-linear-gradient(38deg, var(--color1)  0%, var(--color3) 100%);
    background: linear-gradient(38deg, var(--color1)  0%, var(--color3) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="var(--color1)",endColorstr="var(--color3)",GradientType=1);
}
.boxes .box ul{
    padding: 2rem;
}
.boxes .box li{
    padding: 0;
    padding-top: 0.5rem;
    text-align: center;
}
.boxes .box li .delimiter{
    width: 16px;
    height: 16px;
    content: "";
    background: var(--color8);
    display: block;
    margin: 0 auto;
    margin-top: 0.5rem;
    transform: rotate(var(--gsap-rotation));
    border-radius: var(--gsap-border-radius);
}

@media only screen and (orientation: portrait){
    .boxes {
        padding: 50px 0;
      flex-direction: column;
      gap: 10px;
    }
    .boxes .box{
        margin: 2%;
    }
}

@media only print{
    .sec-resume{
        page-break-before: always;
    }
    .boxes .box {
        opacity: 1!important;
        display: block!important;
        transform: rotateY(0deg)!important;
    }
}
</style>