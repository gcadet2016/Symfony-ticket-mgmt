/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/global.css'; //global.css importé dans base.html.twig
//import './styles/rightToolbar.css'; // rightToolbar.css importé dans base.html.twig


document.addEventListener("DOMContentLoaded", function () {
    const rightToolbarToggle = document.getElementById("right-toolbar-tog");
    rightToolbarToggle.addEventListener("click", () => {
        const rightToolbar = document.getElementById("right-toolbar");
        rightToolbar.classList.toggle("right-toolbar-open");
    });

    const toggleButton = document.getElementById("darkLight-toggle");
    const currentTheme = localStorage.getItem("theme") || "light-mode";
    document.body.classList.add(currentTheme);

    const headerMenu = document.getElementById("dropdown-menu");
    headerMenu.classList.add(currentTheme);

    toggleButton.addEventListener("click", () => {
        const newTheme = document.body.classList.contains("light-mode") ? "dark-mode" : "light-mode";
        document.body.classList.remove("light-mode", "dark-mode");
        headerMenu.classList.remove("light-mode", "dark-mode");

        document.body.classList.add(newTheme);
        headerMenu.classList.add(newTheme);
        
        localStorage.setItem("theme", newTheme);
    });


});
