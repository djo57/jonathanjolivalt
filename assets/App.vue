<script>
import { gsap } from "gsap"
import { Flip } from "gsap/Flip"
import { ScrollTrigger } from "gsap/ScrollTrigger"
import { TextPlugin } from "gsap/TextPlugin"

import globalContext from "./js/global"
import {setStickyHeader} from "./js/sticky"

import Loader from "./components/Loader.vue"
import Intro from "./components/Intro"
import Resume from "./components/Resume"
import Skills from "./components/Skills"
import Background from "./components/Background"
import ModalForm from "./components/ModalForm.vue"

import { ref } from "vue"

export default {
  setup() {
    const intro = ref(false)
    const resumes = ref(false)
    const skills = ref(false)
    const backgrounds = ref(false)
    const modal = ref(false)
    const scroller = ref(false)
    const loader = ref(true)
    let mm = gsap.matchMedia()
    return {
        skills, modal, backgrounds, intro, resumes, scroller, loader, mm
    }
  },
  watch:{
    scroller(datas){
        if(datas){
            this.loader = false
            /* H1 SCROLL ANIMATIONS */
            gsap.to("h1", {
                "--gsap-h1": "50%",
                "--gsap-h1-margin": "50px",
                repeat: -1,
                yoyo: true,
                duration: 3,
                ease: "power1.in"
            })
            const headers = gsap.utils.toArray("h1")
            headers.forEach((h1) => {
                gsap.from(h1, {
                    xPercent: 150,
                    duration: 0.4,
                    ease: "power.in",
                    skewX: "30deg",
                    scrollTrigger:{
                        trigger: h1,
                        start: "top 80%",
                        end: "bottom 5%",
                        toggleActions: "restart reverse restart reverse"
                    }
                })
            })
        }
        ScrollTrigger.refresh()
    },
    intro(datas){
        if(datas){
            /* BARS SCROLL ANIMATION */
            const bars = gsap.utils.toArray(".bar")
            bars.forEach((bar, index) => {
                gsap.to(bar, {
                    translateY: -((index+1)*50),
                    scrollTrigger:{
                        trigger: bar,
                        scrub: 2,
                        start: "0",
                        end: "bottom"
                    }
                })
            })

            globalContext.stickyHeaderElement = document.querySelector("#hero-content-sticky")
            globalContext.scroller = ScrollTrigger.create({
                trigger: "body",
                start: "top top",
                onUpdate: (self) => {
                    setStickyHeader(self.progress)
                },
            })
            this.checkScroller()
        }
    },
    resumes(datas){
        if(datas){
            /* SQUARES SCROLL ANIMATION */
            const squares = gsap.utils.toArray(".square")
            squares.forEach((square, index) => {
                gsap.set(square, {
                    rotation: 45 * (index + 1),
                })
                gsap.to(square, {
                    rotation: 180 * (index + 1),
                    scrollTrigger:{
                        trigger: square,
                        scrub: 2,
                        start: "0",
                        end: "bottom"
                    }
                })
            })

            /* BOXES SCROLL ANIMATION */
            const boxes = gsap.utils.toArray(".boxes .box")
            boxes.forEach((box, index) => {
                gsap.from(box, {
                    opacity: 0,
                    duration: 1,
                    rotationY: 90,
                    ease: "back",
                    //stagger: 0.05,
                    delay: index * 0.1,
                    scale: 0.7,

                    scrollTrigger:{
                        trigger: box,
                        start: "top 66%",
                        end: "bottom 33%",
                        toggleActions: "restart reverse restart reverse",
                    }
                })
            })
            this.checkScroller()
        }
    },
    skills(datas){
        if(datas && Array.isArray(datas)){
            /* SKILLS SCROLL ANIMATION */

            /**
             * BACKGROUND ANIMATION
             */
            gsap.to(".skills", {
                "--gsap-skills-gradiant": "radial-gradient(circle, rgba(0,17,34,1) 0%, rgba(255,255,255,1) 20%)",
                keyframes:{
                    "0%":{"--gsap-skills-gradiant": "radial-gradient(circle, rgba(0,17,34,1) 0%, rgba(255,255,255,1) 20%)"},
                    "30%":{"--gsap-skills-gradiant": "radial-gradient(circle, rgba(0,17,34,1) 50%, rgba(255,255,255,1) 70%)"},
                    "70%":{"--gsap-skills-gradiant": "radial-gradient(circle, rgba(0,17,34,1) 50%, rgba(255,255,255,1) 70%)"},
                    "100%":{"--gsap-skills-gradiant": "radial-gradient(circle, rgba(0,17,34,1) 0%, rgba(255,255,255,1) 20%)"},
                },
                scrollTrigger:{
                    trigger: ".skills",
                    start: "top 90%",
                    end: "bottom 10%",
                    scrub: 1
                }
            })

            /**
             * STARS ANIMATION
             */
            gsap.from(".stars", {
                scale: 0.01,
                ease: "power1.inOut",
                duration: 1,
                stagger: 0.1,
                scrollTrigger: {
                    trigger: ".skills .content",
                    toggleActions: "restart reverse restart reverse",
                    start: "top 25%",
                    end: "bottom 50%"
                }
            })

            const skillsElement = document.querySelector(".skills .content")
            this.mm.add({
                isMobile: "(orientation: portrait)",
                isDesktop: "(orientation: landscape)",
            }, (context) => {
                let { isDesktop, isMobile } = context.conditions
                //if(isMobile){
                    gsap.set(skillsElement, {
                        perspectiveOrigin: "50% 100%"
                    })
                    gsap.to(skillsElement, {
                        perspectiveOrigin: "50% 0%",
                        scrollTrigger:{
                            trigger: skillsElement,
                            start: "20% 80%",
                            end: "80% -40%",
                            scrub: 2,
                        }
                    })
                /*}
                else if(isDesktop){
                    gsap.set(skillsElement, {
                        perspectiveOrigin: "50% 70%"
                    })
                    gsap.to(skillsElement, {
                        perspectiveOrigin: "50% 30%",
                        scrollTrigger:{
                            trigger: skillsElement,
                            start: "top 10%",
                            end: "90% 100%",
                            scrub: 2
                        }
                    })
                }*/
            })

            /**
             * STARS INIT
             */
            const grid = 9
            var currentWrapper = false
            var currentPos = 0
            var z = Math.ceil(datas.length / grid)

            datas.forEach((data, index) => {
                if(!currentWrapper || index % grid === 0){
                    currentPos++
                    let wrapperElement = document.createElement("div")
                    wrapperElement.classList.add("wrapper", "wrapper"+(currentPos))
                    wrapperElement.style.zIndex = z - index
                    if(currentWrapper){
                        wrapperElement.classList.add("wrapperZ")
                        gsap.set(wrapperElement, {
                            translateZ: index * -25
                        })
                    }
                    skillsElement.append(wrapperElement)
                    currentWrapper = wrapperElement
                }

                let skillElement = document.createElement("div")
                skillElement.classList.add("skill")
                skillElement.textContent = data.name
                currentWrapper.appendChild(skillElement)

                /**
                 * TEXT ANIMATION
                 */
                gsap.set(skillElement, {
                    textShadow: "0px 0px 0px #FFFFFF",
                    opacity:0
                })

                gsap.to(skillElement, {
                    duration: 1.5,
                    keyframes:{
                        "25%":{ opacity: 1},
                        "50%":{
                            textShadow: "0px 0px 100px #FFFFFF", 
                        },
                        "75%":{ opacity: 1},
                        "100%":{textShadow: "0px 0px 0px #FFFFFF", opacity: 0},
                    },
                    scrollTrigger:{
                        trigger: skillElement,
                        start: "top 90%",
                        end: "bottom 10%",
                        scrub: 1
                    }
                })
            })

            /*gsap.from(".wrapper", {
                duration: 0.5,
                scale: 0.5,
                stagger: 0.1,
                opacity: 0,
                scrollTrigger:{
                    trigger: ".skills .content",
                    start: "top 80%",
                    toggleActions: "restart reverse restart reverse"
                }
            })*/
            this.checkScroller()
        }
    },
    backgrounds(datas){
        if(datas && Array.isArray(datas)){
            /* BACKGROUND ANIMATION */
            gsap.from(".background", {
                opacity: 0,
                duration: 0.7,
                stagger: 0.2,
                x: -50,
                scrollTrigger: {
                    trigger: ".backgrounds",
                    start: "top 80%",
                    end: "+=99999"
                }
            })
            this.checkScroller()
        }
    },
    modal(val){
        console.log(val)
        if(val === false){
            gsap.to(".modal", {
                opacity: 0
            })
        }
    }
  },
  methods: {
    async updateDom (data) {
        this.skills = data
    },
    async updateBg (data) {
        this.backgrounds = data
    },
    async updateIntro (data) {
        this.intro = data
    },
    async updateResume (data) {
        this.resumes = data
    },
    showModal(state = true){
        if(state === false){
            gsap.to(".modal", {
                opacity: 0
            })
        }
        this.modal = state
    },
    checkScroller(){
        this.scroller = this.skills && this.backgrounds && this.intro && this.resumes
    }
  },
  components: {
    Intro, 
    Resume, 
    Skills, 
    Background, 
    ModalForm,
    Loader
},
  mounted() {
    gsap.registerPlugin(Flip,ScrollTrigger,TextPlugin)
  }
}
</script>

<template>
  <Loader v-model:loader="loader"/>
  <ModalForm :opened="modal" @showModal="showModal"/>
  <Intro @showModal="showModal" @updateIntro="updateIntro"/>
  <Resume @updateResume="updateResume" />
  <Skills @updateDom="updateDom"/>
  <Background @updateBg="updateBg" />
</template>