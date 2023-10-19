// import Validator from './js-form-validator.js';
// const Validator = require('./js-form-validator.min.js');
import { Validator } from './js-form-validator.min.js';


// Get form handle

if (document.querySelector('.form-user-edit')) {
    let formHandle = document.querySelector('.form-user-edit');

    new Validator(formHandle, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
// Got to validation
}

if (document.querySelector('.form-user-login')) {
    let formHandleLogin = document.querySelector('.form-user-login');

    new Validator(formHandleLogin, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
// Got to validation
}
if (document.querySelector('.form-user-register')) {
    let formHandleRegister = document.querySelector('.form-user-register');

    new Validator(formHandleRegister, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
}

if (document.querySelector('.admin_form')) {
    let formHandleRegister = document.querySelector('.admin_form');

    new Validator(formHandleRegister, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
}

