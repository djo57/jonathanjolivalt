<script setup>
import {ref, onMounted} from "vue"
import { gsap } from "gsap"

const skills = ref(false)
const emit = defineEmits(['updateDom'])

const getSkills = async() => {
    try{
        await fetch("/api/skills/get", {"method": "GET"})
        .then(response => response.json())
        .then(result => {
            skills.value = result
        })
    } catch(error){
        console.log(error)
    }
}

onMounted(async () => {
    let stars = document.querySelectorAll(".stars")
    stars.forEach((star) => {
        for(let i=0;i<100;i++){
            let {height, width} = star.getBoundingClientRect()

            let newDiv = document.createElement("div")
            newDiv.classList.add("paralax")
            const size = Math.random() * 10
            newDiv.style.height = size+"px"
            newDiv.style.width = size+"px"
            newDiv.style.marginLeft = (Math.random() * width - 10) + "px"
            newDiv.style.marginTop = ((Math.random() * height) - 10) + "px"
            newDiv.style.opacity = (Math.random() / 2 + 0.01)
            star.appendChild(newDiv)
        }

        gsap.to(star, {
            rotation: 360,
            duration: Math.random() * 32 + 64,
            repeat: -1,
            ease: "linear"
        })
    })

    await getSkills()
    emit('updateDom', skills.value)
})
</script>
<template>
    <div class="sec skills">
        <h1>Compétences</h1>
        <div class="content">
            <div class="stars"></div>
            <div class="stars"></div>
            <div class="stars"></div>
            <div class="stars"></div>
            <div class="stars"></div>
        </div>
    </div>
</template>

<style scoped>
.skills{
    /*background-color: black;*/
    color: var(--color8);
    overflow: hidden;
    min-height: 200vh;
}
.skills .content{
    min-height: 200vh;
    background: rgb(0,0,0);
    background: -moz-radial-gradient(circle, rgba(0,0,0,1) 0%, rgba(255,255,255,1) 80%);
    background: -webkit-radial-gradient(circle, rgba(0,0,0,1) 0%, rgba(255,255,255,1) 80%);
    background: var(--gsap-skills-gradiant);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#000000",endColorstr="#ffffff",GradientType=1);
    perspective: 800px;
    perspective-origin: 50% 100%;
}
</style>