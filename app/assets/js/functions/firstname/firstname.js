const controlFirstName = function(champ){
    const firstnameRegex = new RegExp('^[a-zA-Z0-9- \'éèçàùôï]{2,30}$');
    return champ.value.match(firstnameRegex) ? true : false;
}

export {controlFirstName};
