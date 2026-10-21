const controlLastname = function(champ){
    const lastnameRegex = new RegExp('^[a-zA-Z0-9- \'éèçàùôï]{2,30}$');
    return champ.value.match(lastnameRegex) ? true : false;
}

export {controlLastname};
