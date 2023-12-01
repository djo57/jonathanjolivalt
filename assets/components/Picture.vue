<script setup>
import { ref } from 'vue';

const props = defineProps(['pictures'])
let srcset = ref("")
let minSize = ref(0)
let maxSize = ref(0)

const responsivePicture = (pictures) => {
    for(let size in pictures){
        if(Number.isInteger(Number(size))){
            if(Number(size) > maxSize.value) maxSize.value = Number(size)
            if(minSize.value === 0) minSize.value = Number(size)
            else if(Number(size) < minSize.value) minSize.value  = Number(size)

            srcset.value += getImageUrl(pictures[size]) + " " + Number(size) + "w,"
        }
    }
}

const getSizes = () => {
    return "(max-width: "+maxSize.value+"px) 100vw, "+maxSize.value+"px"
}

const getImageUrl = (url) => {
    return url ? require("/assets/images/" + url) : "";
}

responsivePicture(props.pictures)
</script>

<template>
    <img
        :sizes="getSizes()"
        :srcset=srcset
        :src="props.pictures[minSize]"
        alt="">
</template>