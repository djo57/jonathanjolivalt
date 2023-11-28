<script setup>
import {ref, onMounted} from "vue"
import { gsap } from "gsap"

const backgrounds = ref(false)
const test = ref()
var mobile = ref(false)
const emit = defineEmits(['updateBg'])

const getBackgrounds = async() => {
    try{
        await fetch("/api/backgrounds/get", {"method": "GET"})
        .then(response => response.json())
        .then(result => {
            backgrounds.value = result
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

const isMobile = () => {
    mobile = window.innerHeight > window.innerWidth;
}

onMounted(async () => {
    isMobile()
    await getBackgrounds()
    emit('updateBg', backgrounds.value)
    const backgroundElements = gsap.utils.toArray(".background")
    let maxHeight = 400
    backgroundElements.forEach((background) => {
        maxHeight = background.offsetHeight > maxHeight ? background.offsetHeight : maxHeight
    })
    backgroundElements.forEach((background, index) => {
        
        let hoverAnimation = gsap.timeline({
            paused: true
        })

        const aside = background.querySelector(".aside") 
        if(!mobile){
            hoverAnimation.to(aside, {
                background: "#036",
                color: "white"
            })
        }
        background.hoverAnimation = hoverAnimation

        let backgroundAnimation = gsap.timeline({
            defaults:{
                duration: 0.5,
                ease: "linear",
            },
            paused: index === 0 ? false : true
        })

        if(!mobile){
            background.style.maxWidth = "50px"
            gsap.set(background, {
                height: maxHeight,
            })
            backgroundAnimation.to(background, {
                maxWidth: "100%",
            })
        }
        else{
            const wrapper = background.querySelector(".wrapper")
            const oh = wrapper.offsetHeight
            gsap.set(wrapper, {
                height: 0
            })
            backgroundAnimation.to(wrapper, {
                height: oh,
                minHeight: oh,
                ease: "power",
            })
        }
        if(!mobile){
            gsap.set(".backgrounds", {
                flexDirection: "row",
            })
        }

        background.backgroundAnimation = backgroundAnimation
    })
})
</script>
<template>
    <div class="sec sec3">
        <h1>Expériences</h1>
        <div class="content backgrounds" :class="[mobile ? 'portrait' : 'landscape']" v-if="backgrounds">
            <div class="background" v-for="(background, index) in backgrounds" :class='index === 0 ? "expanded" : ""' @click="expand" @mouseover="hover" @mouseleave="leave">
                <div class="aside">
                    <div class="label" :class=background.job>
                        <h2>{{ background.company }}</h2>
                        <div class="date">{{ background.date }}</div>
                    </div>
                </div>
                <div class="wrapper">
                    <div class="content">
                        <div>
                            <div class="job">{{ background.job }}</div>
                            <span class="description" v-html=(background.description)></span>
                        </div>
                        <div class="tags" v-if="background.environment">
                            <span class="tag" v-for="tag in background.environment">{{ tag }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.backgrounds{
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    justify-content:left;
    align-items: stretch;
    align-content: stretch;
    padding-top: 20px;
}
.background{
    flex: 2 1 auto;
    cursor: pointer;
    display: flex;
    padding: 10px;
    max-width: 50%;
    min-height: 400px;
}
.background.expanded{
    cursor: default;
}
.background > * {
    pointer-events: none;
}
.background .aside{
    background: white;
    width: 2rem;
    border-radius: 10px 10px 10px 10px;
    -webkit-box-shadow: 5px 0px 10px 0px rgba(0,0,0,0.3); 
    box-shadow: 5px 0px 10px 0px rgba(0,0,0,0.3);
}
.background.expanded .aside{
    background: var(--color3);
    color: white;
}
.background .aside h2{
    color: black;
}
.background .aside .date{
    min-width: 150px;
}
.background.expanded .aside h2{
    color: white;
}
.background .label{
    display: flex;
    gap: 1rem;
    transform: rotate(-90deg) translateX(-450px);
    min-width: 450px;
    transform-origin: top left;
    justify-content: end;
    padding-top: 5px;
    padding-right: 10px;
    font-weight: bold;
}
.background h2{
    font-size: 1rem;
    padding: 0;
}
.background .wrapper{
    overflow-y: hidden;
    z-index: -1;
    width: 100%;
    display: flex;
}
.background .content{
    margin-top: 1rem;
    margin-bottom: 1rem;
    margin-right: 1rem;
    border-radius: 0px 10px 10px 0px;
    -webkit-box-shadow: 5px 0px 10px 0px rgba(0,0,0,0.3); 
    box-shadow: 5px 0px 10px 0px rgba(0,0,0,0.3);
    background-color: #f1f1f1;
    z-index: -1;
    padding: 5px 10px;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-x: hidden;
    background: rgb(6,35,92);
    background: -moz-linear-gradient(104deg, rgba(6,35,92,0.47102591036414565) 0%, rgba(255,255,255,1) 35%, rgba(255,255,255,1) 100%);
    background: -webkit-linear-gradient(104deg, rgba(6,35,92,0.47102591036414565) 0%, rgba(255,255,255,1) 35%, rgba(255,255,255,1) 100%);
    background: linear-gradient(104deg, rgba(6,35,92,0.47102591036414565) 0%, rgba(255,255,255,1) 35%, rgba(255,255,255,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#06235c",endColorstr="#ffffff",GradientType=1);
}
.backgrounds .tags{
    border-top: 1px solid gray;
    color: gray;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
}
.backgrounds .tag:not(:first-child)::before{
    content: "#";
    margin: 0 0.5rem;
}

.background .content .description >>> h3{
    color: var(--color2);
    font-weight: bold;
    font-size: 1.2rem;
    margin: 1rem 0;
}
.background .description >>> ul, .background .description >>> li {
    list-style: square inside;
}
.background .content .job{
    color: var(--color4);
    font-weight: bold;
    font-size: 1.4rem;
    margin-bottom: 0.5rem;
}
@media only screen and (orientation: portrait){
    .backgrounds{
        flex-direction: column;
    }
    .background{
        height: 100%;
        display: block;
        max-width: 100%;
        min-height: 0;
        padding: 0 2%;
    }
    .background .aside{
        width: 100%;
    }
    .background .aside .date{
        min-width: 100%;
    }
    .background .label{
        transform: none;
        justify-content: center;
        padding: 0.5rem 0;
        display: block;
        text-align: center;
        min-width: 100%;
    }

    .background .wrapper{
        width: 96%;
        margin-left: 2%;
    }
    .background .content{
        margin: 0;
        border-radius: 0px 0px 10px 10px;
        box-shadow: 5px 0px 10px 0px rgba(0,0,0,0.3);
        -webkit-box-shadow: 5px 0px 10px 0px rgba(0,0,0,0.3);
    }
}
</style>