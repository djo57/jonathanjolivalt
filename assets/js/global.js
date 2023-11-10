const globalContext = {
    "firstLoad": true,
    "stickyHeader": false,
    "moveImgFrom": "#hero-content .me img",
    "moveImgTo": "#hero-content-sticky .me",
    "moveHeaderVisibility": "visible",
    "moveContactFrom ": "#hero-content .contact",
    "moveContactTo": "#hero-content-sticky",
    "moveImgFromContent": "#hero-content .me",
    "stickyHeaderElement": false
}

window.addEventListener("load", () => {
    if(globalContext.firstLoad && window.scrollY === 0){
        globalContext.firstLoad = false
    }
})

export default globalContext;