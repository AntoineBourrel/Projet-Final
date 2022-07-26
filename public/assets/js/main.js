const icon = document.querySelector('.icons');
const menu = document.querySelector('.menu-mobile');

icon.addEventListener('click', function(){
    menu.classList.toggle('d-b');
});