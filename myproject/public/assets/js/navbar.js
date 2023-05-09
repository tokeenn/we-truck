const principalHam = document.querySelector('.principal-ham');
const innerHam = document.querySelector('.inner-ham');
const navs = document.querySelector('.navs');

principalHam.addEventListener('click', () => {
    navs.classList.toggle('show');
    navs.classList.remove('hide');
})

innerHam.addEventListener('click', () => {
    navs.classList.toggle('hide');
    navs.classList.remove('show');
})