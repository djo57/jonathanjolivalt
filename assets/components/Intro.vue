<script setup>
import {ref, onMounted} from "vue"
import { gsap } from "gsap"
import hero from '../images/jonathanjolivalt.jpg'
import contact from '../images/contact.svg'

const intro = ref(false)
const emit = defineEmits(['showModal', 'updateIntro'])

const getIntro = async() => {
    try{
        await fetch("/api/intro/get", {"method": "GET"})
        .then(response => response.json())
        .then(result => {
            intro.value = result
        })
    } catch(error){
        console.log(error)
    }
}

onMounted(async () => {
    await getIntro()
    emit('updateIntro', intro.value)
    gsap.registerEffect({
        name: "clip",
        defaults:{
            duration: 0.5,
            ease: "power1.in",
            direction: "full"
        },
        extendTimeline: true,
        effect: (targets, config) => {
            let direction = {
                top: "polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)",
                bottom: "polygon(0% 100%, 100% 100%, 100% 100%, 0% 100%)",
                full: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)",
                left: "polygon(0% 0%, 0% 0%, 0% 100%, 0% 100%)",
                right: "polygon(100% 0%, 100% 0%, 100% 100%, 100% 100%)",
                halfY: "polygon(0% 0%, 100% 0%, 100% 50%, 0% 50%)"
            }
            /* left-top / right-top / right-bottom / left-bottom */

            const cp = window.getComputedStyle(targets[0]).clipPath

            if(cp == "none"){
                gsap.set(targets, { clipPath:direction["full"] })
            }

            let animation = gsap.timeline()
            .to(targets, {
                clipPath: direction[config.direction],
                duration: config.duration,
                ease: config.ease
            })

            return animation
        }
    })

    gsap.from(".bar", {
        height: 0,
        width:0,
        duration:2,
        stagger: 0.5
    })

    const hoverTimeline = gsap.timeline({
        paused: true
    })
    const hoverTimeline2 = gsap.timeline({
        paused: true
    })

    hoverTimeline.from(".contact .bg", {
        scale:0, 
        duration:0.3, 
        ease:"circ",
    }).to(".hero .contact img", {
        scale: 0.8
    }, 0)

    const hoverButton = document.querySelector(".contact")
    hoverButton.addEventListener('mouseenter', (event) => {
        hoverTimeline.restart()
        hoverTimeline2.restart()
    })
    hoverButton.addEventListener('mouseleave', (event) => {
        hoverTimeline.reverse()
        hoverTimeline2.restart()
    })

    const name = document.querySelector(".hero .fg")
    const nameTimeline = gsap.timeline({
        paused: true
    })
    nameTimeline.clip(name, {direction: "halfY"}).from(".hero .intro", {
        "--gsap-gradiant": "linear-gradient(180deg, rgba(97,110,116,1) 0%, rgba(255,255,255,1) 0%)",
        opacity: 1,
        duration: 2
    })
    
    const heroTimeline = gsap.timeline({ 
        defaults: { duration: 0.2, opacity: 0, ease: "back(2)"},
        onComplete: () => { 
            nameTimeline.play() 
        }
    })

    heroTimeline.from(".hero .me img", {   
        scale:2,
        ease: "power"
    }).from(".hero .mask li", {
        xPercent: -50,
    }).from(".hero h2", {
        xPercent: 50
    }).from(".hero h3", {
        yPercent: 50
    }).to(".hero .mask li, .hero h2", {
        letterSpacing: "-8px",
        opacity: 1
    }).from(".hero .introduction", {
        scale: 0.5
    }).from(".social li, .hero .degree li, .hero .lang li", {   
        stagger:0.1,
        translateX: -50
    }).from(".hero .contact", {
        opacity: 0,
        scale:0.1
    }).to(".degree li, .lang li", {
        paddingBottom: 10,
        opacity: 1
    }).to(".hero .introduction",{
        text: intro.value.description, 
        duration: 1,
        opacity: 1,
        ease: "power.in"
    }).set(".hero .mask .fg", {
        backgroundColor: "white",
        color: "#012",
        opacity: 1,
        duration: 0.1
    }).set(".hero .mask .bg", {
        backgroundColor: "#012",
        color: "white",
        opacity: 1,
        duration: 0.1
    })
})
</script>
<template>
    <div id="hero-content-sticky">
        <div class="me"></div>
    </div>
    <header class="sec hero" id="hero-wrapper">
        <div id="hero-content">
            <div class="bar bar1"></div>
            <div class="bar bar2"></div>
            <div class="bar bar3"></div>
            <div class="me">
                <img :src=hero alt="Photo of me">
            </div>
            <div class="intro" v-if="intro">
                <ul class="mask">
                    <li class="bg">{{ intro.name }}</li>
                    <li class="fg">{{ intro.name }}</li>
                </ul>
                <h2>{{ intro.job }}</h2>
                <h3>{{ intro.title }}</h3>
                <p class="introduction">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce rutrum hendrerit sem, vitae sodales risus venenatis sit amet. Vestibulum nec urna purus. In nec leo et orci tincidunt pretium eu id est.
                </p>
                <div class="degree" v-if="intro.degrees">
                    <ul>
                        <li v-for="degree in intro.degrees">{{ degree }}</li>
                    </ul>
                </div>
                <div class="lang">
                    <ul>
                        <li><span class="fi fi-fr"></span>Français, courant</li>
                        <li><span class="fi fi-gb"></span>Anglais, B1</li>
                    </ul>
                </div>
                <div class="social">
                    <ul>
                        <li v-for="link in intro.social"><a :href="link.link" class="link.class" target="_blank">{{ link.html }}</a></li>
                    </ul>
                </div>
                <div class="para contact" @click="$emit('showModal')">
                    <div class="bg circle"></div>
                    <span class="hover"><img :src=contact alt="email"> Contactez moi</span>
                </div>
            </div>
        </div>
    </header>
</template>

<style scoped>
/*.loader-enter-active, 
.loader-leave-active {
  transition: opacity 1s ease-out;
}

.loader-enter-from,
.loader-leave-to {
    opacity: 0;
}*/

#hero-content-sticky{
    width: 400px;
    height: 100px;
    position: sticky;
    top: 10px;
    left: 10px;
    z-index: 999999;
    display: flex;
    /*display: none;*/
    flex-direction: row;
    flex-wrap: wrap;
    justify-content:stretch;
    align-items: stretch;
}
#hero-content-sticky .me{
    width: 100px;
    z-index: 2;
}
#hero-content-sticky .me img{
    border-radius: 50%;
    width: 100px;
    height: 100px;
    border: 5px solid var(--color3);
    object-fit:cover;
}
#hero-content-sticky .para{
    font-size: 1.2rem;
    color: var(--color4);
    line-height: 50px;
    background: white;
    border-radius: 10px;
    height: 70px;
    margin-left: -30px;
    margin-top: 15px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    opacity: 0.7;
}
#hero-content-sticky .contact{
    cursor: pointer;
    width: fit-content;
}
#hero-content-sticky .contact img{
    width: 64px;
    height: 64px;
    vertical-align: middle;
}
#hero-content-sticky .contact .circle {
	/*width:5vmin;
	height:5vmin;*/
	border-radius:50%;
    height: 256px;
    width: 256px;
}
#hero-content-sticky .contact span {
    z-index: 1000;
}
#hero-content-sticky .contact .bg {
	position:absolute;
	background:white;
	opacity:0.5;
	will-change:transform;
    margin-top: -100px;
    margin-left: -32px;
    z-index: 0;
}

@media only screen and (orientation: portrait){
    #hero-content-sticky{
        height: 200px;
        width: 650px;
    }
    #hero-content-sticky .me{
        width: 200px;
    }
    #hero-content-sticky .me img{
        width: 200px;
        height: 200px;
    }
    #hero-content-sticky .para{
        height: 150px;
        margin-left: -40px;
        margin-top: 25px;
        line-height: 130px;
        font-size: 2.4rem!important;
    }
    #hero-content-sticky .contact img{
        width: 128px;
        height: 128px;
    }
    .degree ul{
        list-style-type: square;
        list-style-position: inside;
        margin-bottom: 2rem;
    }
}

#hero-wrapper{
    margin-top: -100px;
}
#hero-content{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content:stretch;
    /*font-family: 'Noto Sans Rejang', sans-serif;*/
    align-items: stretch;
    position: relative;
    /*padding: 100px;*/
    min-height: 100vh;
    width: 100%;
    overflow-x: hidden;
}
#hero-content div{
    flex: auto;
}
#hero-content .me{
    overflow: hidden;
    width: 30%;
    z-index: 2;
}
#hero-content .me::before{
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30%;
    height: 200px;
    background: linear-gradient(to top, white, transparent);
    z-index: 100;
}

#hero-content .intro{
    width: 70%;
    /*background: var(--color8);*/
    background: var(--gsap-gradiant);
}

.hero .me img{
    width: 100%;
    height: 100%;
    object-fit:cover;
}
#hero-content h1, h2, h3, p, ul, .para{
    padding: 1% 10%;
}
#hero-content .mask{
    font-size: 6rem;
    color: var(--color1);
    letter-spacing:0px;
}
#hero-content h2{
    font-size: 4rem;
    color: var(--color2);
}
#hero-content h3{
    font-size: 2rem;
    color: var(--color3);
}
.hero p, .hero .para{
    font-size: 1.2rem;
    color: var(--color4);
}

#hero-content ul{
    font-size: 1.1rem;
    color: var(--color5);
}

#hero-content .mask{
    display: grid;
    padding: 0;
}

#hero-content .mask li{
    font-weight: bold;
    font-size: 6rem;
    width: auto;
    /*padding: 0 20px;*/
    padding-left: 10%;
}
#hero-content .lang span{
    margin-right: 10px;
    padding-right: 10px;
}
#hero-content .social a{
    text-decoration: none;
    color: var(--color5);
    background: url("../images/linkedin.svg") no-repeat;
    padding-left: 34px;
}
.hero .contact, #hero-content-sticky .contact{
    cursor: pointer;
    width: fit-content;
}
.hero .contact img, #hero-content-sticky .contact img{
    width: 64px;
    height: 64px;
    vertical-align: middle;
}
@media only screen and (orientation: portrait){
    #hero-wrapper{
        margin-top: -200px;
    }
    #hero-content p, ul{
        padding: 2rem 0.5rem;
    }
    #hero-content h1, h2, h3{
        padding: 0.5rem;
    }
    #hero-content .para{
        padding: 2rem 0.5rem;
    }
    #hero-content .mask{
        font-size: 9rem;
    }
    #hero-content .mask li{
        font-size: 7rem;
        padding-left: 1rem;
    }
    #hero-content ul{
        padding: 0.5rem;
    }
    #hero-content h2{
        font-size: 5rem;
    }
    #hero-content h3{
        font-size: 3rem;
    }
    .hero .para{
        font-size: 2.4rem;
        min-width: 100%;
    }
    /*.hero .contact img{
        width: 128px;
        height: 128px;
    }*/
    #hero-content .lang li{
        vertical-align: middle;
    }
    #hero-content .lang span{
        font-size: 3rem;
        margin-right: 2rem;
    }
    #hero-content .social{
        margin-top: 2rem;
    }
    #hero-content .social a{
        padding-left: 5.8rem;
    }
}
.hero .contact .circle, #hero-content-sticky .circle {
	/*width:5vmin;
	height:5vmin;*/
	border-radius:50%;
    height: 256px;
    width: 256px;
}
.hero .contact span, #hero-content-sticky span {
    z-index: 1000;
}
.hero .contact .bg, #hero-content-sticky .bg {
	position:absolute;
	background:white;
	opacity:0.5;
	will-change:transform;
    margin-top: -100px;
    margin-left: -32px;
    z-index: 0;
}
/*.circle span {
	color:white;
	font-weight:800;
	font-family: sans-serif;
	font-size:13vmin;
}*/

/*.hero .mask .bg{
    background-color: pink;
    color: #0D0B30;
}
.hero .mask .fg{
    background-color: #0D0B30;
    color: pink;
}*/

#hero-content .mask  > *{
    grid-area: 1/1;
}

/*@media only screen and (min-width: 900px) and (max-width: 1000px) {
    body{
        background-color: red;
    }
    #hero-content h1, h2, h3, p, ul, .para{
        padding: 1rem;
    }
    #hero-content .mask{
        font-size: 5.5rem;
    }
    #hero-content .mask li{
        font-size: 5.5rem;
    }
    #hero-content h2{
        font-size: 4.5rem;
    }
    #hero-content h3{
        font-size: 1.5rem;
    }
    .hero p, .hero .para, #hero-content-sticky .para{
        font-size: 1rem;
    }
    #hero-content ul{
        font-size: 0.8rem;
    }
}*/
@media only screen and (orientation: portrait){
    #hero-content{
        flex-direction: column;
    }
    #hero-content .hero {
      flex-direction: column;
    }
    #hero-content .me{
        width: 100%;
        z-index: 2;
        height: 300px;
        max-height: 300px;
        min-height: 300px;
        overflow: hidden;
    }
    #hero-content .me::before{
        display: none;
    }
    .hero #hero-content .me img{
        object-position: 50% 20%;
    }
    #hero-content .intro {
        width: 100%;
        padding-bottom: 5rem;
    }
}
</style>