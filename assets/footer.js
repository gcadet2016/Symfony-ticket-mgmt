import './styles/footer.css';

//const cta = document.getElementsByClassName('cta_header');

export function toggleFooter() {
    const footer = document.getElementById('toaster');
    const expandIcon = document.getElementById('expand_icon');
    footer.classList.toggle('is_open');
    expandIcon.classList.toggle('is_reversed');
}

// gcadet: to be deleted
//document.addEventListener('click', toggleFooter);
console.log('This log comes from assets/footer.js');