const dots = document.querySelectorAll(".tab-menu a");

const removeActiveClass = () => {
    dots.forEach(dot => {
        dot.classList.remove("active");
    })
};

const addActiveClass = (entries, observer) => {
    entries.forEach((entry) => {
        if(entry.isIntersecting) {
            let currentDot = document.querySelector(
                `.tab-menu a[href='#${entry.target.id}']`
            );
            removeActiveClass();
            currentDot.classList.add("active");
        }
    });
};

const options = {
    threshold: 0.1,
};

const observer = new IntersectionObserver(addActiveClass, options);
const sections = document.querySelectorAll("section");

sections.forEach((section) => {
    observer.observe(section);
});
