

const form_add_product = document.body.querySelector('#form_add_product');
if(form_add_product) {

    form_add_product.addEventListener('submit', function (e) {
        e.preventDefault();
        fetch(this.action, {
            method: 'POST',
            body: new FormData(e.target),
        })
            .then(response => response.json())
            .then(json => {

                handleResponse(json)
            })
    });
}

    /**
     *
     * @param {FormResponse} response
     */
    const handleResponse = function (response) {
        removeAllErrors();
        switch (response.code) {
            case 'PRODUCT_ADD_SUCCESSFULLY':
                form_add_product.reset();
                window.location.href = "/admin/produit/list";
                break;

            case 'PRODUCT_BAD_RESPONSE':
                handleErrors(response.errors);
                break;
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
        let element = document.querySelector(`#add_product_${key}`);
        element.classList.add('is-invalid');

        let div = document.createElement('div');
        div.classList.add('invalid-feedback', 'd-block');
        div.innerText = errors[key];
        element.after(div);
    }
}
