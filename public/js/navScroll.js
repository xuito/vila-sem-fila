const btnLeft = document.querySelector(".left-btn");
const btnRight = document.querySelector(".right-btn");
const tabMenu = document.querySelector(".tab-menu");

const IconVisibility = () => {
    let scrollLeftValue = Math.ceil(tabMenu.scrollLeft);
    let scrollableWidth = tabMenu.scrollWidth - tabMenu.clientWidth;

    btnLeft.style.display = scrollLeftValue > 0 ? "block" : "none";
    btnRight.style.display = scrollableWidth > scrollLeftValue ? "block" : "none";
}

btnRight.addEventListener("click", () => {
    tabMenu.scrollLeft += 120;
    setTimeout(() => IconVisibility(), 50);

});

btnLeft.addEventListener("click", () => {
    tabMenu.scrollLeft -= 120;
    setTimeout(() => IconVisibility(), 50);
});

window.onload = function(){
    btnRight.style.display = tabMenu.scrollWidth > tabMenu.clientWidth || tabMenu.scrollWidth >= window.innerWidth ? "block" : "none";
    btnLeft.style.display = tabMenu.scrollWidth >= window.innerWidth ? "" : "none";
}

window.onresize = function(){
    btnRight.style.display = tabMenu.scrollWidth > tabMenu.clientWidth || tabMenu.scrollWidth >= window.innerWidth ? "block" : "none";
    btnLeft.style.display = tabMenu.scrollWidth >= window.innerWidth ? "" : "none";

    let scrollLeftValue = Math.round(tabMenu.scrollLeft);
    btnLeft.style.display = scrollLeftValue > 0 ? "block" : "none";
}

// SCRIPT PARA MOVER O MENU COM O MOVIMENTO DO MOUSE

let activeDrag = false;

tabMenu.addEventListener("mousemove", (drag) => {
    if(!activeDrag) return;
    tabMenu.scrollLeft -= drag.movementX;
    IconVisibility();
    tabMenu.classList.add("dragging");
});

document.addEventListener("mouseup", () => {
    activeDrag = false;
    tabMenu.classList.remove("dragging");
});

tabMenu.addEventListener("mousedown", () => {
    activeDrag = true;
});

// SCRIPT PARA MUDAR O STATUS DE ATIVO NOS BOTOES E NAS SEÇÕES
const tabs = document.querySelectorAll(".categorie");
const tabBtns = document.querySelectorAll(".tab-btn");

const tabNav = function(tabBtnClick) {

    tabBtns.forEach((tabBtn) => {
        tabBtn.classList.remove("active");
    });

    tabBtns.forEach((tab) => {
        tab.classList.remove("active");
    });

    tabBtns[tabBtnClick].classList.add("active");
    tabs[tabBtnClick].classList.add("active");
}

tabBtns.forEach((tabBtn, i) => {
    tabBtn.addEventListener("click", () => {
        tabNav(i);
    });
});
