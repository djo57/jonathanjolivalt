import { gsap } from "gsap"
import { Flip } from "gsap/Flip";

import globalContext from "./global"
import { faTemperatureUp } from "@fortawesome/free-solid-svg-icons";

const flipStickyHeader = (show, flipAnimation = true) => {
    if(show){
        globalContext.moveImgFrom = "#hero-content .me img"
        globalContext.moveImgTo = "#hero-content-sticky .me"
        globalContext.stickyHeaderVisibility = "visible"
        globalContext.moveContactFrom = "#hero-content .contact"
        globalContext.moveContactTo = "#hero-content-sticky"
        globalContext.moveImgFromContent = "#hero-content .me"
    }
    else{
        globalContext.moveImgFrom = "#hero-content-sticky .me img"
        globalContext.moveImgTo = "#hero-content .me"
        globalContext.stickyHeaderVisibility = "hidden"
        globalContext.moveContactFrom = "#hero-content-sticky .contact"
        globalContext.moveContactTo = "#hero-content .intro"
        globalContext.moveImgFromContent = "#hero-content .me"
    }

    const heroImg = document.querySelector(globalContext.moveImgFrom)
    if(null === heroImg) return;
    const stickyImg = document.querySelector(globalContext.moveImgTo)
    const stickyHeader = document.querySelector("#hero-content-sticky")
    stickyHeader.style.opacity = flipAnimation ? 1 : 0

    const state = flipAnimation ? Flip.getState(heroImg) : false

    stickyHeader.style.visibility = globalContext.moveHeaderVisibility
    stickyHeader.style.display = "flex"
    stickyImg.appendChild(heroImg)

    const stickyTimeline = gsap.timeline({ 
        defaults: { duration: 1 }
    })

    if(state && heroImg.style.opacity != 0){
        Flip.from(state, {
            duration: 0.5,
            ease: "power1",
            absolute: true
        })
    }
    else{
        stickyTimeline.to(stickyHeader, {
            opacity: 1,
            delay: 2,
        })
    }

    const heroContact = document.querySelector(globalContext.moveContactFrom)
    const stickyContact = document.querySelector(globalContext.moveContactTo)

    const state2 = flipAnimation ? Flip.getState(heroContact) : false

    stickyContact.appendChild(heroContact)

    if(state2){
        Flip.from(state2, {
            duration: 0.5,
            ease: "power1",
            absolute: true,
            scale: true
        })
    }
    globalContext.firstLoad = false
}

const buildStickyHeader = (show, flipAnimation = true) => {
    const stickyDuration = 0.2

    if(show){
        flipStickyHeader(true, flipAnimation)

        gsap.to(globalContext.moveImgFromContent, {
            marginLeft: "-70%",
            duration: stickyDuration * 3
        })
    }
    else{
        gsap.to(globalContext.moveImgFromContent, {
            marginLeft: "0%",
            duration: stickyDuration,
            onComplete: () => {
                flipStickyHeader(false, flipAnimation)
                globalContext.stickyHeaderElement.style.visibility = "hidden"
            }
        })
    }
}

const setStickyHeader = (progress) => {
    if(globalContext.firstLoad && progress > 0){
        globalContext.stickyHeader = true
        buildStickyHeader(globalContext.stickyHeader, false)
    }
    else if(!globalContext.stickyHeader && progress > 0){
        globalContext.stickyHeader = true
        buildStickyHeader(globalContext.stickyHeader)
    }
    else if(globalContext.stickyHeader && progress === 0){
        globalContext.stickyHeader = false
        buildStickyHeader(globalContext.stickyHeader)
    }
}

export {setStickyHeader}