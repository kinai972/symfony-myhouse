/* --------------------------- */
/*        DRAGGING DROP        */
/* --------------------------- */

let newPositions = [];
const divDragginDrop = document.getElementById('dragging-drop');
const steps = document.querySelectorAll('.img-slider');
const errorMessage = document.querySelector('.alert');
console.log(errorMessage);

// À l'arrivée sur la page (avant l'événement du glisser-déposer)
    for (let i = 0; i < steps.length; i++) {
      steps[i].dataset.place = i + 1;
    }

    steps.forEach(step => {
      newPositions.push({
        id: step.dataset.id,
        place: step.dataset.place
      })
    })

// Après les événements de glisser-déposer)
Sortable.create(divDragginDrop, {
  onEnd: function(event) {
    newPositions = [];
    stepsEvent = event.target.children;
    for (let i = 0; i < stepsEvent.length; i++) {
      stepsEvent[i].dataset.place = i + 1;
    }

    steps.forEach(step => {
      newPositions.push({
        id: step.dataset.id,
        place: step.dataset.place
      })
    })

    console.log(newPositions);
    
  }
});



formSteps.addEventListener('submit', (e) => {
  e.preventDefault();

  // newPositions = [{"id":"24","place":"1"},{"id":"51","place":"2"},{"id":"4","place":"3"},{"id":"41","place":"4"},{"id":"1","place":"5"},{"id":"32","place":"6"},{"id":"50","place":"7"}];

  axios.post(formSteps.action, JSON.stringify({ images: newPositions })).then((response) => {
    // Redirige l'utilisateur vers la liste des images du slider
    window.location.href = response.data.redirect_url;
    // console.log(response);
    console.log(response.data.redirect_url);
  }).catch((error) => {
    errorMessage.textContent = error.response.data.message;
    console.log(error);
  });



})