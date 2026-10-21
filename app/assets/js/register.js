import {showMessage} from "./functions/message/message.js";
import {controlEmail} from "./functions/email/email.js";
import {successFieldRegister} from "./functions/succesfield/succesField.js";
import {alertChamps, alertFieldRegister} from "./functions/alertField/alertField.js";
import { controlPasswordRegister} from "./functions/password/password.js";
import {controlCheckbox} from "./functions/checkbox/checkbox.js";
import {controlFieldsRegistration} from "./functions/controlFields/controlFields.js";


window.onload = ()=>{
    const formRegistration = document.getElementById('registration_form');
    if(formRegistration){
      const inputs = formRegistration.querySelectorAll('input');
      const labels = formRegistration.querySelectorAll('label');
      const inputsEffect = [];
      const labelsEffect = [];
      for(let i =0; i < inputs.length; i++)
      {
          if(inputs[i].type !=='hidden'){
              inputsEffect[i]=inputs[i];
              labelsEffect[i]=labels[i];
          }
      }
        /* div error on input fields */
        const emailError1 = formRegistration.querySelector('#registration_form_email_error1');
        const plainpasswordError1 = formRegistration.querySelector('#registration_form_plainPassword_error1');
        const agreeTermsError1 = formRegistration.querySelector('#registration_form_agreeTerms_error1');
        /* button submit */
        const buttonSubmit = formRegistration.querySelector('[type=submit]');
       // buttonSubmit.setAttribute('disabled','disabled');
        /* fiield general info */
        const messageLogin = document.body.querySelector('#message-login');

        /* all ul li of document */
        const tagsLi = document.body.querySelectorAll("ul li");
        const infosAll = [];
        tagsLi.forEach((li)=>{
            if(li.classList.contains('contact-item'))
                infosAll.push((li.firstElementChild));
        });
        /* element info special regex password */
        const all_password_criteria = document.body.querySelectorAll("li[data-password-criteria]");
        buttonSubmit.setAttribute('disabled','disabled');
        /* begin */
        let message="";
        if(inputsEffect[0].value ==='') {
             message = "Saisir adresse email";
            showMessage(messageLogin, message);
        }
        inputsEffect[0].addEventListener('focus',function(){
            message="Saisir adresse email";
            showMessage(messageLogin,message);
        });
        inputsEffect[0].addEventListener('input',function(){
            controlEmail(this,infosAll[0],labelsEffect[0])? successFieldRegister(this,infosAll[0],labelsEffect[0],emailError1):
                alertFieldRegister(this,infosAll[0],labelsEffect[0],emailError1);
            controlFieldsRegistration(inputsEffect,messageLogin,buttonSubmit);

        });
        inputsEffect[0].addEventListener('blur',function(){
            controlEmail(this,infosAll[0],labelsEffect[0])? successFieldRegister(this,infosAll[0],labelsEffect[0],emailError1):
                alertFieldRegister(this,infosAll[0],labelsEffect[0],emailError1);
            controlFieldsRegistration(inputsEffect,messageLogin,buttonSubmit);
        });

        inputsEffect[1].addEventListener('focus',function({currentTarget}){
            message="Saisir mot de passe";
            showMessage(messageLogin,message);
            let password = currentTarget.value;
            if(password.length ===0){
                all_password_criteria.forEach((li)=>li.className ="");
                password_length_criteria.textContent = "10 caractères";
            }
            password_criteria.style.display="block";

        });
        inputsEffect[1].addEventListener('input',function({currentTarget}){
        let password = currentTarget.value;
        controlPasswordRegister(this,infosAll[1],labelsEffect[1],password)? successFieldRegister(this,infosAll[1],labelsEffect[1],plainpasswordError1):
            alertFieldRegister(this,infosAll[1],labelsEffect[1],plainpasswordError1);
            controlFieldsRegistration(inputsEffect,messageLogin,buttonSubmit);
        });
        inputsEffect[1].addEventListener('blur',function({currentTarget}){
            let password = currentTarget.value;
            controlPasswordRegister(this,infosAll[1],labelsEffect[1],password)? successFieldRegister(this,infosAll[1],labelsEffect[1],plainpasswordError1):
                alertFieldRegister(this,infosAll[1],labelsEffect[1],plainpasswordError1);
            controlFieldsRegistration(inputsEffect,messageLogin,buttonSubmit);
        });

        inputsEffect[2].addEventListener('focus',function(){
            message = "Accepter la collecte des données";
            showMessage(messageLogin,message);
        });
        inputsEffect[2].addEventListener('input',function(){
            controlCheckbox(this,messageLogin)? successFieldRegister(this,infosAll[2],labelsEffect[2],agreeTermsError1):
                alertFieldRegister(this,infosAll[2],labelsEffect[2],agreeTermsError1);
            controlFieldsRegistration(inputsEffect,messageLogin,buttonSubmit);
        });
        inputsEffect[2].addEventListener('blur',function(){
            controlCheckbox(this,messageLogin)? successFieldRegister(this,infosAll[2],labelsEffect[2],agreeTermsError1):
                alertFieldRegister(this,infosAll[2],labelsEffect[2],agreeTermsError1);
                controlFieldsRegistration(inputsEffect,messageLogin,buttonSubmit);
        });

        /* eventlistener on button submit */
        buttonSubmit.addEventListener('click',function(event){
           let counter = 0;
                for(let i = 0; i < inputsEffect.length; i++)
                {
                    if(inputsEffect[i].type === 'email' || inputsEffect[i].type==='password'){
                        if(inputsEffect[i].value ==='' || inputsEffect[i].classList.contains('is-invalid'))
                        {
                            counter++;
                            alertChamps(inputsEffect[i],labelsEffect[i]);
                        }
                    }
                    if(inputsEffect[i].type==='checkbox' && !(inputsEffect[i].checked))
                    {
                        counter++;
                        alertChamps(inputsEffect[i],labelsEffect[i]);
                    }
                }
                if(counter > 0 || counter < 0) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                }

        });
    }
}
