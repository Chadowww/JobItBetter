/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import './js/search_navbar.js';
import './js/favlist.js';
import './js/alerte.js';
import './bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './js/salarySlider.js';


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

// start the Stimulus application


if (document.querySelector('.be-banner-search')) {
    const parent = document.querySelector('.be-banner-home');
    const enfant = document.querySelector('.be-banner-search');

    function ajusterHauteurEnfant()
    {
        const enfantHeight = enfant.clientHeight;
        enfant.style.transform = `translateY(${enfantHeight / 2}px) scale(0.9)`;
    }

    window.addEventListener('resize', ajusterHauteurEnfant);
    ajusterHauteurEnfant();
}


function moveElement(node)
{
    let accordion = document.querySelector('.form-accordion');
    let offcanvas = document.querySelector('.form-offcanvas');
    let screenSize = window.innerWidth;
    let parent = document.querySelector('.form-accordion-parent');
    let firstChild = parent.firstChild;
    if (screenSize < 1200) {
        offcanvas.appendChild(accordion);
        parent.classList.remove('col-3')
    }
    if (screenSize >= 1200) {
        // parent.appendChild(accordion);
        parent.insertBefore(accordion, firstChild);
       parent.classList.add('col-3')
    }
}
    addEventListener('DOMContentLoaded', moveElement)
    addEventListener('resize', moveElement);
