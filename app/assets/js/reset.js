import {confirmNegatif, confirmPositif, showMessage} from "./functions/message/message.js";
import {controlPasswordOne, controlPasswordTwo} from "./functions/password/password.js";
import {successLoginField} from "./functions/succesfield/succesField.js";
import {alertLoginField} from "./functions/alertField/alertField.js";


window.onload = () =>{
    const form = document.body.querySelector('#resetPasswordForm');
    if(form){
        let mdp = "";
        const passwordsInput = form.querySelectorAll("input[type='password']");
        const labelsField = form.querySelectorAll('label');
        /* info dialog */
        const dialogNewMdp = document.body.querySelector('#dialogNewMdp');
        const all_password_criteria = document.body.querySelectorAll("li[data-password-criteria]");
        const all_confirm_criteria = document.body.querySelectorAll("li[data-password-criteria-2]");

        const password_criteria = document.body.querySelector("#password_criteria");
        const confirm_criteria = document.body.querySelector("#confirm_criteria");
        /* button submit */
        const submitButton = form.querySelector("button[type='submit']");
        /* begin */
        let message = 'Saisir le nouveau mot de passe';
        showMessage(dialogNewMdp,message);

        /* eventlistener on input password one */
        passwordsInput[0].addEventListener('focus',function({currentTarget}){
                password_criteria.style.display = "block";
                message = " Dix caractères ";
                showMessage(dialogNewMdp,message);
                confirm_criteria.style.display = "none";
                let password = currentTarget.value;
                if(password.length === 0){
                    all_password_criteria.forEach((li)=>li.className=" ");
                    password_length_criteria.textContent =" 10 caractèress";
                }
        });
        passwordsInput[0].addEventListener('input',function({currentTarget}){
                confirm_criteria.style.display = "none";
                let password = currentTarget.value;
                controlPasswordOne(this,password)? successLoginField(this,labelsField[0]):alertLoginField(this,labelsField[0]);
        });
        passwordsInput[0].addEventListener('blur',function({currentTarget}){
            confirm_criteria.style.display = "none";
            let password = currentTarget.value;
            controlPasswordOne(this,password)? successLoginField(this,labelsField[0]):alertLoginField(this,labelsField[0]);
        });

        passwordsInput[1].addEventListener('focus',function({currentTarget}){
            password_criteria.style.display = "none";
            confirm_criteria.style.display="block";
            message = "Confirmez le mot de passe";
            showMessage(dialogNewMdp,message);
            let password = currentTarget.value;
            if(password.length === 0){
                all_confirm_criteria.forEach((li)=>li.className=" ");
                confirm_length_criteria.textContent ="10 caratères";
            }
        });
        passwordsInput[1].addEventListener('input',function({currentTarget}){
            password_criteria.style.display = "none";
            let password = currentTarget.value;
            if(controlPasswordTwo(this,password)? successLoginField(this,labelsField[1]):alertLoginField(this,labelsField[1]))
            {
                mdp =(this.value === passwordsInput[0].value)? successLoginField(this,labelsField[1]):alertLoginField(this,labelsField[1]);
                mdp ? confirmPositif(dialogNewMdp):confirmNegatif(dialogNewMdp);
            }
        });
        passwordsInput[1].addEventListener('blur',function({currentTarget}){
            password_criteria.style.display = "none";
            let password = currentTarget.value;
            if(controlPasswordTwo(this,password)? successLoginField(this,labelsField[1]):alertLoginField(this,labelsField[1]))
            {
                mdp =(this.value === passwordsInput[0].value)? successLoginField(this,labelsField[1]):alertLoginField(this,labelsField[1]);
                mdp ? confirmPositif(dialogNewMdp):confirmNegatif(dialogNewMdp);
            }
        });

        /* eventlistener on button submit */
        submitButton.addEventListener('click',function(e){
            let counter = 0;
            for(let i =0; i < passwordsInput.length; i++)
            {
                if(passwordsInput[i].value ==='' || passwordsInput[i].classList.contains('is-invalid'))
                {
                    alertLoginField(passwordsInput[i],labelsField[i]);
                    counter++;
                }
            }
            if(counter !==0 || counter === passwordsInput.length)
            {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });


    }
}
