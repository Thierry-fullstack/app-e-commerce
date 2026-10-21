import {showMessage} from "./functions/message/message.js";
import {controlEmail} from "./functions/email/email.js";
import {successLoginField} from "./functions/succesfield/succesField.js";
import {alertLoginField} from "./functions/alertField/alertField.js";
import {controlPassword} from "./functions/password/password.js";
import {controlRemember} from "./functions/checkbox/checkbox.js";
import {controlFieldsRegistration} from "./functions/controlFields/controlFields.js";


window.onload = ()=>{
    const loginForm = document.querySelector('#loginForm');
    if(loginForm){
        const inputAll = loginForm.querySelectorAll('input');
        const selectInputs =[];
        const selectLabels =[];
        for(let i =0; i < inputAll.length; i++){
            if(inputAll[i].type !== 'hidden'){
                selectInputs.push(inputAll[i]);
                selectLabels.push(inputAll[i].nextElementSibling);
            }
        }
        /* button submit */
        const buttonSubmit = loginForm.querySelector('button[type="submit"]');
        buttonSubmit.setAttribute('disabled','disabled');
        const dialogLogin = document.querySelector('#dialogLogin');  //dialogLogin
        /* begin */
        let message = '';
        if(selectInputs[0].value.length === 0){
            message = 'Saisir votre email';
            showMessage(dialogLogin,message);
        }
        /* email */
        selectInputs[0].addEventListener('focus',function(){
            message = 'Saisir votre email';
            showMessage(dialogLogin,message);
        });
        selectInputs[0].addEventListener('input',function(){
            controlEmail(this,selectLabels[0])? successLoginField(this,selectLabels[0]):
        alertLoginField(this,selectLabels[0]);
            controlFieldsRegistration(selectInputs,dialogLogin,buttonSubmit);
        });
        selectInputs[0].addEventListener('blur',function(){
            controlEmail(this,selectLabels[0])? successLoginField(this,selectLabels[0]):
                alertLoginField(this,selectLabels[0]);
            controlFieldsRegistration(selectInputs,dialogLogin,buttonSubmit);
        });
        /* password */
        selectInputs[1].addEventListener('focus',function(){
            message = 'Saisir votre mot de passe';
            showMessage(dialogLogin,message);
        });
        selectInputs[1].addEventListener('input',function(){
            controlPassword(this,selectLabels[1])? successLoginField(this,selectLabels[1]):
                alertLoginField(this,selectLabels[1]);
            controlFieldsRegistration(selectInputs,dialogLogin,buttonSubmit);
        });
        selectInputs[1].addEventListener('blur',function(){
            controlPassword(this,selectLabels[1])? successLoginField(this,selectLabels[1]):
                alertLoginField(this,selectLabels[1]);
            controlFieldsRegistration(selectInputs,dialogLogin,buttonSubmit);
        });

        /* checkbox */
        selectInputs[2].addEventListener('focus',function(){
            message = 'Cochez la case pour vous connecter';
            showMessage(dialogLogin,message);
        });
        selectInputs[2].addEventListener('input',function(){
            controlRemember(this,selectLabels[2])? successLoginField(this,selectLabels[2]):
                alertLoginField(this,selectLabels[2]);
            controlFieldsRegistration(selectInputs,dialogLogin,buttonSubmit);
        });
        selectInputs[2].addEventListener('blur',function(){
            controlRemember(this,selectLabels[2])? successLoginField(this,selectLabels[2]):
                alertLoginField(this,selectLabels[2]);
            controlFieldsRegistration(selectInputs,dialogLogin,buttonSubmit);
        });

        /* submit */
        buttonSubmit.addEventListener('click',function(e){
          let counter = 0;
          for(let i =0; i < selectInputs.length; i++){
              if(selectInputs[i].classList.contains('is-valid')){
                  counter++;
              }
          }
            if(counter !== selectInputs.length){
          e.preventDefault();
          e.stopPropagation();
          return false;
        }
});
    }
}
