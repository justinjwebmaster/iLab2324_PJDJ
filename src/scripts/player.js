// CODE de la page projet

const playBtn = document.querySelector(".btn__play");
const videoSection = document.querySelector(".projectVideo");
const projectVideo__container = document.querySelector(".projectVideo__container");

playBtn.addEventListener("click", function() {
  videoSection.classList.remove("projectVideo--hidden");
});

videoSection.addEventListener("click", function() {
  videoSection.classList.add("projectVideo--hidden");
});





