let exampleModal = document.getElementById('staticBackdrop');
console.log(exampleModal);
exampleModal.addEventListener('show.bs.modal', function (event) {
    console.log("Modal clicked");
    // Button that triggered the modal
    let button = event.relatedTarget
    // Extract info from data-bs-* attributes
    let recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
    let modalTitle = exampleModal.querySelector('.modal-title')
    let modalBodyInput = exampleModal.querySelector('.modal-body input')

    modalTitle.textContent = 'New message to ' + recipient
    modalBodyInput.value = recipient
})