<script setup>
import { ref, useAttrs, reactive, watch, onMounted } from 'vue'
import { useReCaptcha } from 'vue-recaptcha-v3'
import { useVuelidate } from '@vuelidate/core'
import { required, email, alphaNum, helpers } from '@vuelidate/validators'
import { gsap } from "gsap"
import { library, dom } from '@fortawesome/fontawesome-svg-core'

const sent = ref(0)
const emit = defineEmits(['showModal'])

defineOptions({
  inheritAttrs: false
})

const attrs = useAttrs()

const state = reactive({
    name: '',
    email: '',
    phone: '',
    description: ''
})

const rules = {
    name: { 
        required:helpers.withMessage('Veuillez entrer un nom', required)
    },
    email: { 
        required:helpers.withMessage('Veuillez entrer un email', required), 
        email:helpers.withMessage("Cet email n'est pas valide, ex: toto@tata.com", email) 
    },
    phone: { 
        alphaNum:helpers.withMessage("Cet numéro n'est pas valide, ex: +33611223344 sans espaces", alphaNum)  
    },
    description: {
        required:helpers.withMessage('Veuillez laisser un message', required)
    }
}

const v$ = useVuelidate(rules, state)
const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()

const sendMail = async() => {
    const [validation, recaptcha] = await Promise.all([
        v$.value.$validate(),
        recaptchaLoaded()
    ])

    const token = await executeRecaptcha('login')

    if(token && validation && (!sent.value || sent.value === 3)){
        sent.value = 1
        const requestOptions = {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(state)
        }
        try{
            await fetch("/mail/send", requestOptions)
            .then(response => response.json())
            .then(data => sent.value = data === true ? 2 : 3)
        } catch(error){
            sent.value = 3
        }
    }
}

watch(sent, () => {
    if(sent.value === 2){
        gsap.to(".modal .content", {
            delay: 1,
            rotation: 720,
            scale: 0.1,
            opacity: 0,
            onComplete: () => {
                emit('showModal', false)
            }
        })
    }
})

let hoverButton= gsap.timeline({
    paused: true
})

const hover = (event) => {
    const button = event.target

    hoverButton.to(button, {
        background: "#06C",
        color: "white",
        duration: 0.3,
        letterSpacing: "-1px",
    })
    button.hoverButton = hoverButton
    button.hoverButton.play()
}

const leave = (event) => {
    const button = event.target
    
    hoverButton.to(button, {
        background: "#06C",
        color: "white",
        duration: 0.3,
        letterSpacing: "-1px",
    })
    button.hoverButton = hoverButton
    button.hoverButton.reverse()
}

</script>

<template>
    <teleport to="body">
        <div v-if="attrs['opened']" class="modal">
            <div class="content">
                <div @click="$emit('showModal', false)" class="close"><font-awesome-icon icon="circle-xmark" shake size="2xs" style="color: #ad0000;" /></div>
                <form @submit.prevent="sendMail">
                    <div class="input">
                        <input type="text" placeholder="Nom prénom*" v-model="state.name" id="name" autocomplete="name" />
                        <div class="input-errors" v-for="error of v$.name.$errors" :key="error.$uid">
                            <div class="error">{{ error.$message }}</div>
                        </div>
                    </div>
                    <div class="input">
                        <input type="text" placeholder="Email*" v-model="state.email" id="email" autocomplete="email" />
                        <div class="input-errors" v-for="error of v$.email.$errors" :key="error.$uid">
                            <div class="error">{{ error.$message }}</div>
                        </div>
                    </div>
                    <div class="input">
                        <input type="text" placeholder="Téléphone" v-model="state.phone" id="phone" autocomplete="phone" />
                        <div class="input-errors" v-for="error of v$.phone.$errors" :key="error.$uid">
                            <div class="error">{{ error.$message }}</div>
                        </div>
                    </div>
                    <div class="input">
                        <textarea placeholder="Message*" v-model="state.description" id="description" autocomplete="description"></textarea>
                        <div class="input-errors" v-for="error of v$.description.$errors" :key="error.$uid">
                            <div class="error">{{ error.$message }}</div>
                        </div>
                    </div>
                    <div>
                        <Transition name="sendmail">
                            <input v-if="sent === 0 || sent === 3" type="submit" value="Envoyer le message" class="button" @mouseover="hover" @mouseleave="leave" />
                            <div v-else-if="sent === 1" class="pending">Envoi en cours...</div>
                            <div v-else-if="sent === 2" class="ok">Message envoyé</div>
                        </Transition>
                        <div v-if="sent === 3" class="error">Erreur d'envoi</div>
                    </div>
                </form>
            </div>
        </div>
    </teleport>
</template>

<style scoped>
.modal{
    width: 100%;
    height: 100vh;
    position: fixed;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10;
    display: block;
    top: 0;
    left: 0;
}
.content{
    display: block;
    position: relative;
    width: 400px;
    min-height: 50%;
    background: rgba(256, 256, 256, 1);
    border-radius: 2rem;
    box-shadow: 1px 1px 18px 8px rgba(0,0,0,1);
    -webkit-box-shadow: 1px 1px 18px 8px rgba(0,0,0,1);
    -moz-box-shadow: 1px 1px 18px 8px rgba(0,0,0,1);
    padding: 2rem;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.close{
    position: absolute;
    right: 2rem;
    font-size: 4rem;
    padding: 0!important;
    margin-top: -2rem;
    cursor: pointer;
}
input, textarea{
    border-radius: 0.5rem;
    border: 1px solid var(--color8);
    padding: 0.5rem;
    width: 100%;
    font-size: 1rem;
    color: var(--color6)
}
textarea{
    min-height: 200px;
}
.input{
    margin-bottom: 1rem;
}

.sendmail-enter-active {
    transition: all 0.3s ease-out;
}

.sendmail-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.sendmail-enter-from,
.sendmail-leave-to {
    opacity: 0;
    height: 0;
    line-height: 0;
}

@media only screen and (orientation: portrait){
    .content{
        width: 95%;
    }
    /*input, textarea, .error, .ok, .pending{
        font-size: 2.5rem;
    }
    textarea{
        min-height: 500px;
    }
    .close{
        font-size: 12rem;
        margin-top: -5rem;
    }*/
}
</style>