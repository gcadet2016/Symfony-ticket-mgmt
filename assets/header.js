import './styles/header.css';

export function toggleMenu() {
    const menu = document.getElementById('dropdown-menu');
    menu.classList.toggle('hiddensm');
}

// ----- User Menu -----
export function showMenuUser() {
    const menu2 = document.getElementById('userMenuId');
    menu2.classList.remove('hidden');
}
export function hideMenuUser() {
    const menu2 = document.getElementById('userMenuId');
    menu2.classList.add('hidden');
}

