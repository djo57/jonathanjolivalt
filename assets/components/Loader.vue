<script setup>
import gsap from 'gsap'
import { onMounted } from 'vue';

const props = defineProps(['loader'])

const onLeave = (el, done) => {
    gsap.to(el, {
        opacity: 0,
        scaleX: 0,
        duration: 0.5,
        onComplete: () => {
            el.remove()
        }
    })
}

onMounted(async () => {
    gsap.to(".loader", {
        duration: 0.3,
        repeat: -1,
        yoyo: true,
        fontSize: "2rem",
        color: "black",
        ease: "expo.in"
    })
})
</script>

<template>
    <Transition 
    name="loader" 
    :css="false"
    @leave="onLeave">
        <div class="loader" v-if="loader">
            ...chargement...
        </div>
    </Transition>
</template>

<style scoped>
.loader{
    color: var(--color3);
    width: 100vw;
    height: 100vh;
    text-align: center;
    vertical-align: middle;
    background-color: var(--color8);
    align-items:center;
    justify-content:center;
    display: flex;
    position: fixed;
    z-index: 9999;
    font-size: 1.5rem;
    font-weight: bold;
}
</style>