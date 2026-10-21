import {showMessage} from "./functions/message/message.js";
import {checkFielResetPassword} from "./functions/clearField/clearField.js";
import {controlEmail} from "./functions/email/email.js";
import {successLoginField} from "./functions/succesfield/succesField.js";
import {alertLoginField} from "./functions/alertField/alertField.js";


window.onload = ()=>{
    const resetPasswordForm = document.querySelector('#resetPasswordForm');
    if(resetPasswordForm){
            const inputEmail = resetPasswordForm.querySelector('[type=email]' );
            const inputSubmit = resetPasswordForm.querySelector('[type=submit]');
            inputSubmit.setAttribute('disabled','disabled');
            const dialogResetPassword = document.body.querySelector('#dialog-reset-password');
            const labelEmail = resetPasswordForm.querySelector('#email-label');

            /* begin */
        let message = 'Indiquez votre adresse email';
        showMessage(dialogResetPassword,message);

        inputEmail.addEventListener('focus',function(){
            checkFielResetPassword(this,dialogResetPassword,inputSubmit);
        });
        inputEmail.addEventListener('input',function(){
            controlEmail(this)? successLoginField(this,labelEmail):alertLoginField(this,labelEmail);
            checkFielResetPassword(this,dialogResetPassword,inputSubmit);
        });
        inputEmail.addEventListener('blur',function(){
            controlEmail(this)? successLoginField(this,labelEmail):alertLoginField(this,labelEmail);
            checkFielResetPassword(this,dialogResetPassword,inputSubmit);
        });

        /* eventlistener on button submit */
        inputSubmit.addEventListener('click',function(event){
           if(inputEmail.value ==='' || inputEmail.classList.contains('is-invalid')){
               alertLoginField(inputEmail,labelEmail);
               event.preventDefault();
               event.stopImmediatePropagation();
               return false;
           }
        });

    }
}
