<script setup>
import {ref, onMounted} from "vue"
import { gsap } from "gsap"

const backgrounds = ref(false)
const test = ref()

const getBackgrounds = async() => {
    try{
        await fetch("/api/backgrounds/get", {"method": "GET"})
        .then(response => response.json())
        .then(result => {
            backgrounds.value = result
            console.log(backgrounds.value)
            console.log(JSON.stringify(backgrounds.value[0].description))
        })
    } catch(error){
        console.log(error)
    }
}

const expand = (event) => {
    const backgroundElements = gsap.utils.toArray(".background")
    const background = event.target
    if(!background.classList.contains("expanded")){
        backgroundElements.forEach((background) => {
            if(background.classList.contains("expanded")){
                background.backgroundAnimation.reverse()
                background.classList.remove('expanded')
                background.hoverAnimation.reverse()
            }
        })
        background.classList.add("expanded")
        background.backgroundAnimation.play()
    }
}

const hover = (event) => {
    const background = event.target
    if(!background.classList.contains("expanded")) background.hoverAnimation.play()
}

const leave = (event) => {
    const background = event.target
    if(!background.classList.contains("expanded")) background.hoverAnimation.reverse()
}

onMounted(async () => {
    await getBackgrounds()
    const backgroundElements = gsap.utils.toArray(".background")
    backgroundElements.forEach((background, index) => {
        let hoverAnimation = gsap.timeline({
            paused: true
        })

        const aside = background.querySelector(".aside") 
        hoverAnimation.to(aside, {
            background: "#036",
            color: "white"
        })
        background.hoverAnimation = hoverAnimation

        let backgroundAnimation = gsap.timeline({
            defaults:{
                duration: 0.5,
                ease: "linear",
            },
            paused: index === 0 ? false : true
        })

        backgroundAnimation.to(background, {
            maxWidth: "100%",
        })

        background.backgroundAnimation = backgroundAnimation
    })
})
</script>
<template>
    <div class="sec sec3">
        <h1>Expériences</h1>
        <div class="content backgrounds">
            <div class="background" v-for="(background, index) in backgrounds" :class='index === 0 ? "expanded" : ""' @click="expand" @mouseover="hover" @mouseleave="leave">
                <div class="aside">
                    <div class="label" :class=background.job>
                        <h2>{{ background.company }}</h2>
                        <div class="date">{{ background.date }}</div>
                    </div>
                </div>
                <div class="wrapper">
                    <div class="content">
                        <div class="job">{{ background.job }}</div>
                        <span class="description" v-html=(background.description)></span>
                        <div class="tags">
                            <span class="tag" v-for="tag in background.environment">{{ tag }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.background .content .job{
    color: var(--color4);
    font-weight: bold;
    font-size: 1.4rem;
    margin-bottom: 0.5rem;
}

</style>