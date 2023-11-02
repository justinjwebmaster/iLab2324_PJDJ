console.info('Hello world');

// Sélectionnez toutes les checkboxes du formulaire
const checkboxes = document.querySelectorAll('#formFilter input[type="checkbox"]');

// Ajoutez un écouteur d'événement à chaque checkbox
checkboxes.forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    // Soumettez le formulaire chaque fois qu'une checkbox est cochée ou décochée
    document.getElementById('formFilter').submit();
  });
});
