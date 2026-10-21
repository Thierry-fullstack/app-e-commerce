import {showMessage} from "./functions/message/message.js";
import {successFieldRegister} from "./functions/succesfield/succesField.js";
import {alertFieldRegister} from "./functions/alertField/alertField.js";
import {controlLastname} from "./functions/lastname/lastname.js";
import {controlFirstName} from "./functions/firstname/firstname.js";
import {controlAddress} from "./functions/address/address.js";
import {controlZipcode} from "./functions/zipcode/zipcode.js";
import {controlCity} from "./functions/city/city.js";
import {controlFieldsRegistration} from "./functions/controlFields/controlFields.js";



window.onload = () => {
    const inscription_client_form = document.querySelector('#inscription_client_form');
    if(inscription_client_form){
        const inputAll = inscription_client_form.querySelectorAll('input');
        const labelAll = inscription_client_form.querySelectorAll('label');
        let inputEffect = []; let labelEffect = [];
        for(let i = 0; i < inputAll.length; i++){
            if(inputAll[i].type !=='hidden')
            inputEffect.push(inputAll[i]);
            labelEffect.push(labelAll[i]);
        }
        /* fields error */
        const customer_lastname_error1 = document.querySelector('#customer_lastname_error1');
        const customer_firstname_error1 = document.querySelector('#customer_firstname_error1');
        const customer_address_error1 = document.querySelector('#customer_address_error1');
        const customer_zipcode_error1 = document.querySelector('#customer_zipcode_error1');
        const customer_city_error1= document.querySelector('#customer_city_error1');
        /* button submit */
        const customer_submit = document.querySelector('#customer_submit');
        customer_submit.setAttribute('disabled','disabled');
        /* general message */
        const dialogInscription = document.querySelector('#dialogInscription');

        /* allInfo */
        const tagsLi = document.querySelectorAll('ul li.contact-item');
        const infoAll =[];
        tagsLi.forEach((li) => {
           if(li.classList.contains('contact-item')){
            infoAll.push(li.firstElementChild);
           }
        });
     //   console.log(infoAll);
        /* begin */
        let message = '* Champs obligatoires';
        showMessage(dialogInscription, message);
        inputEffect[0].addEventListener('focus',function(){
            message ='Saisir nom';
            showMessage(dialogInscription, message);
        });
        inputEffect[0].addEventListener('input',function(){
            controlLastname(this)? successFieldRegister(this,infoAll[0],labelEffect[0],customer_lastname_error1) :
                alertFieldRegister(this,infoAll[0],labelEffect[0],customer_lastname_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);

        });
        inputEffect[0].addEventListener('blur',function(){
            controlLastname(this)? successFieldRegister(this,infoAll[0],labelEffect[0],customer_lastname_error1) :
                alertFieldRegister(this,infoAll[0],labelEffect[0],customer_lastname_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });

        inputEffect[1].addEventListener('focus',function(){
            message ='Saisir prénom';
            showMessage(dialogInscription, message);
        });
        inputEffect[1].addEventListener('input',function(){
            controlFirstName(this)? successFieldRegister(this,infoAll[1],labelEffect[1],customer_firstname_error1) :
                alertFieldRegister(this,infoAll[1],labelEffect[1],customer_firstname_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[1].addEventListener('blur',function(){
            controlFirstName(this)? successFieldRegister(this,infoAll[1],labelEffect[1],customer_firstname_error1) :
                alertFieldRegister(this,infoAll[1],labelEffect[1],customer_firstname_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[2].addEventListener('focus',function(){
            message ='Saisir adresse';
            showMessage(dialogInscription, message);
        });
        inputEffect[2].addEventListener('input',function(){
            controlAddress(this)? successFieldRegister(this,infoAll[2],labelEffect[2],customer_address_error1) :
                alertFieldRegister(this,infoAll[2],labelEffect[2],customer_address_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[2].addEventListener('blur',function(){
            controlAddress(this)? successFieldRegister(this,infoAll[2],labelEffect[2],customer_address_error1) :
                alertFieldRegister(this,infoAll[2],labelEffect[2],customer_address_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[3].addEventListener('focus',function(){
            message ='Saisir code postal';
            showMessage(dialogInscription, message);
        });
        inputEffect[3].addEventListener('input',function(){
            controlZipcode(this)? successFieldRegister(this,infoAll[3],labelEffect[3],customer_zipcode_error1) :
                alertFieldRegister(this,infoAll[3],labelEffect[3],customer_zipcode_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[3].addEventListener('blur',function(){
            controlZipcode(this)? successFieldRegister(this,infoAll[3],labelEffect[3],customer_zipcode_error1) :
                alertFieldRegister(this,infoAll[3],labelEffect[3],customer_zipcode_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[4].addEventListener('focus',function(){
            message ='Saisir ville';
            showMessage(dialogInscription, message);
        });
        inputEffect[4].addEventListener('input',function(){
            controlCity(this)? successFieldRegister(this,infoAll[4],labelEffect[4],customer_city_error1) :
                alertFieldRegister(this,infoAll[4],labelEffect[4],customer_city_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });
        inputEffect[4].addEventListener('blur',function(){
            controlCity(this)? successFieldRegister(this,infoAll[4],labelEffect[4],customer_city_error1) :
                alertFieldRegister(this,infoAll[4],labelEffect[4],customer_city_error1);
            controlFieldsRegistration(inputEffect,dialogInscription,customer_submit);
        });

        customer_submit .addEventListener('click',function(e){
            let counter = 0;
            for(let i =0; i < inputEffect.length; i++){
                if(inputEffect[i].classList.contains('is-valid')){
                    counter++;
                }
            }
            if(counter !== inputEffect.length) {
                message = 'Veuillez remplir correctement tous les champs';
                showMessage(dialogInscription, message);
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        })
    }
}
