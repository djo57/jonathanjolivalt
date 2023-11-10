<script setup>
import {onMounted} from "vue"
import { gsap } from "gsap"

onMounted(() => {
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
        //repeat: -1,
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
    <div class="sec">
        <h1>Résumé</h1>
        <div class="boxes content">
            <div class="square square1"></div>
            <div class="square square2"></div>
            <div class="square square3"></div>
            <div class="box">
                <h4>Compétences</h4>
                <ul>
                    <li>Développement backend avec PHP 8, Symfony 6, MySQL</li>
                    <li>Développement frontend avec ReactJS, VueJS, JavaScript, Jquery, Ajax, CSS, HTML</li>
                    <li>API Restful avec API Platform</li>
                    <li>Webservice SOAP</li>
                    <li>Intégration continue CI/CD</li>
                    <li>Documentation</li>
                    <li>Identification des besoins</li>
                    <li>Formation des utilisateurs</li>
                </ul>
            </div>
            <div class="box">
                <h4>Projets</h4>
                <ul>
                    <li>Création d'une page de gestion des tournées des chauffeurs Chronopost</li>
                    <li>Création d’une tâche qui calcul les tournées des chauffeurs via un webservice SOAP</li>
                    <li>Maintenance d’un système d’import/export de données</li>
                    <li>Maintenance d’un système de contrôle de température et de suivi des colis</li>
                    <li>Création et refonte de sites web</li>
                    <li>Installation, configuration et gestion de CMS / boutiques en ligne</li>
                </ul>
            </div>
            <div class="box">
                <h4>Sociétés</h4>
                <ul>
                    <li>CTIE - Centre des technologies de l'information de l'état (Consultant 5 ans)</li>
                    <li>Chronopost / Biologistic (Consultant 4 ans)</li>
                    <li>Netline (CDI 3 ans)</li>
                    <li>Markeasy (CDI 2 ans)</li>
                    <li>Nvision (CDD 6 mois)</li>
                    <li>WORT (Consultant 3 mois)</li>
                    <li>Inverto (CDD 1 mois)</li>
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
    /*padding: 0 50px;*/
    /*transform-style:preserve-3d;*/
    perspective:2400px;
    /*background-color: white;*/
    clear: both;
}
/*.boxes.test {
    flex-direction: column;
    padding: 0 10px;
  }*/
.boxes .box {
    flex: 1;
    /*padding: 16px;*/
    /*background-color: red;*/
    margin: 50px;
    border-radius: 15px;
    border: var(--box-border);
    /*transform-style:preserve-3d;*/
    /*perspective:600px;*/
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
    /*background-image: url("https://cdn.futura-sciences.com/cdn-cgi/image/width=1920,quality=60,format=auto/sources/images/Code-informatique.jpeg");*/
    /*background-size: cover;*/
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
    /*background: white;*/
}
.boxes .box li{
    padding: 0;
    padding-top: 0.5rem;
    text-align: center;
}
.boxes .box li:not(:last-child):after{
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

@media only screen and (max-width: 1200px) {
    .boxes .box {
        margin-left: 10px;
        margin-right: 10px;
    }
}
@media only screen and (max-width: 800px) {
    .boxes {
      flex-direction: column;
      padding: 0 10px;
    }
    .boxes .box {
        margin: 10px;
        margin-bottom: 50px;
    }
}
</style>