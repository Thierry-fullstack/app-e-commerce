
import {showMessage} from "../message/message.js";

const clearField = function(champ,label)
{
    champ.classList.remove('is-valid');
    if(label.classList.contains('text-success')) {
        label.classList.remove('text-success');
    }
    label.classList.add('text-info-emphasis');
    return false;
}

/* input email on reset-password */
const checkFielResetPassword = function(champ,dial,submit)
{
    if(champ.value !=='' && champ.classList.contains('is-valid'))
    {
        let message="Soumettre votre saisie";
        submit.removeAttribute('disabled');
        submit.classList.remove('btn-outline-secondary');
        submit.classList.add('btn-secondary');
        showMessage(dial,message);
    }
    else if(champ.classList.contains('is-invalid'))
    {
        let message = 'Adresse email invalide';
        submit.setAttribute('disabled','disabled');
        submit.classList.remove('btn-secondary');
        submit.classList.add('btn-outline-secondary');
        showMessage(dial,message);
    }
}

export { clearField,checkFielResetPassword }
