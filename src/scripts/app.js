// recuper button + filters__form
const button = document.querySelector('.filters__btn');
const formFilters = document.querySelector('.filters__form');
// au clic > afficher filters__form et cache filters__btn
button.addEventListener('click', function() {
  formFilters.classList.remove('filters__form--hidden');
  button.classList.add('filters__btn--hidden');
});


// fermer filters__form et afficher filters__btn
const buttonClose = document.querySelector('.filters__btn--close');
buttonClose.addEventListener('click', function() {
  formFilters.classList.add('filters__form--hidden');
  button.classList.remove('filters__btn--hidden');
});


