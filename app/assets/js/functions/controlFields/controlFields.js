

const controlFieldsRegistration = function(champs,dial,submit)
{
    let counter = 0;
    for(let i = 0; i < champs.length; i++)
    {
        if(champs[i].value !== '' && champs[i].classList.contains('is-valid') || champs[i].checked && champs[i].classList.contains('is-valid'))
        {
            counter++;
        }
    }
    if(counter === champs.length)
    {
        submit.removeAttribute('disabled');
        submit.classList.remove('btn-outline-secondary');
        submit.classList.add('btn-secondary');
        dial.textContent="Cliquez sur le bouton soumettre";
    }else if (counter !== champs.length)
    {
        submit.setAttribute('disabled','disabled');
        submit.classList.remove('btn-secondary');
        submit.classList.add('btn-outline-secondary');
        dial.textContent="Saisir vos coordonnées";
    }
}

export {controlFieldsRegistration}
