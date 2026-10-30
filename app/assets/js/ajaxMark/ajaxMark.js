
let newMarkForm = document.body.querySelector('#newMarkForm');
if(newMarkForm){
    const mark_form_name = newMarkForm.querySelector('#mark_form_name');

    mark_form_name.addEventListener('focus',function(){
       removeError(this);
    });

    newMarkForm.addEventListener('submit',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        fetch(this.action,{
            method: 'POST',
            body: new FormData(e.target),
        })
            .then(response=>response.json())
            .then( json => {
              handleResponse(json)
            })
    });

    const handleResponse = function(response){
        removeAllErrors();
        switch (response.code){
            case 'MARK_ADD_SUCCESSFULLY':
                recordMarkDone();
                break;
            case 'MARK_BAD_RESPONSE':
                handleErrors(reponse.errors);
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
    isInvalidElements.forEach(isInvalidElements => isInvalidElements.classList.remove('is-invalid'));
}

const recordMarkDone = function(){
    newMarkForm.reset();
    window.location.href = '/admin/mark/list';
}

const handleErrors = function(errors){
    if(errors.length ===0)return;
    for(const key in errors){
        let element = document.querySelector(`#mark_form_${key}`);
        element.classList.add('is-invalid');
        let div = document.createElement('div');
        div.classList.add('invalid-feedback','d-block');
        div.innerText = errors[key];
        element.after(div);
    }
}
