
/* check phone number */
const controlPhone = function (field){
    const regexPhone = /^[0-9]{10}$/;
    const phoneRegex = new RegExp(regexPhone);
    return field.value.match(phoneRegex);
}

export {controlPhone}
