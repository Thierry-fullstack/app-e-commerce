/* control address */
const controlAddress = function(champ){
    const addressRegex = new RegExp('^[a-zA-Z0-9- \'éèçàùôï]{5,254}$');
    return champ.value.match(addressRegex) ? true : false;
}

export {controlAddress};
