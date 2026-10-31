/**
 *
 * @typedef {Object} FormResponse
 * @property {string} code
 * @property {Object} errors
 * @property {string} html
 */
let newMarkForm = document.body.querySelector('#newMarkForm');
if(newMarkForm){
    const mark_form_name = newMarkForm.querySelector('#mark_form_name');
    const marks_list = document.body.querySelector('#marks_list');

    mark_form_name.addEventListener('focus',function(){
       removeError(this);
    });

    newMarkForm.addEventListener('submit',function(e){
        e.preventDefault();
        fetch(this.action,{
            method: 'POST',
            body: new FormData(e.target),
        })
            .then(response=>response.json())
            .then( json => {
              handleResponse(json)
            })
    });

    /**
     *
     * @param {FormResponse} response
     */
    const handleResponse = function(response){
        removeAllErrors();
        switch (response.code){
            case 'MARK_ADD_SUCCESSFULLY':
                newMarkForm.reset();
                marks_list.innerHTML += response.html;

                break;
            case 'MARK_BAD_RESPONSE':
                handleErrors(response.errors);
                break;
        }
    }
}


const removeError = function(field){
    field.classList.remove('is-invalid');
    field.nextSibling.remove();
}

const removeAllErrors = function(){
    const invalidFeedbackElements = document.querySelectorAll('.invalid-feedback');
    const isInvalidElements = document.querySelectorAll('.is-invalid');

    invalidFeedbackElements.forEach(errorElement => errorElement.remove());
    isInvalidElements.forEach(isInvalidElement => isInvalidElement.classList.remove('is-invalid'));
}



/**
 *
 * @param {Object} errors
 */
const handleErrors = function(errors){
    if(errors.length === 0) return;

    for(const key in errors) {
        let element = document.querySelector(`#mark_form_${key}`);
        element.classList.add('is-invalid');

        let div = document.createElement('div');
        div.classList.add('invalid-feedback', 'd-block');
        div.innerText = errors[key];
        element.after(div);
    }
}
