const modal =  document.getElementById("modal");
const addpopup =  document.getElementById("add-acc");

document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) && !addpopup.contains(e.target)) {
        CloseAddAccount()
    }
})

function OpenAddAccount() {
    modal.classList.add("open-modal");
    addpopup.classList.add("open-modal-content");
    document.querySelector(".hidden-log-out").classList.add("active");

    const inputDivs = addpopup.querySelectorAll("div.input input");
    inputDivs.forEach(input => {
        input.value = ""; 
    });
}   

function CloseAddAccount() {
    modal.classList.remove("open-modal");
    addpopup.classList.remove("open-modal-content");
    document.querySelector(".hidden-log-out").classList.remove("active");

    const inputDivs = addpopup.querySelectorAll("div.input input");
    inputDivs.forEach(input => {
        input.value = ""; 
    });
}