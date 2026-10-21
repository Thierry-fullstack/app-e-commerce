/* control city */
const controlCity = function(champ){
    const cityRegex = new RegExp('^[a-zA-Z- \'éèçàùôï]{2,30}$');
    return champ.value.match(cityRegex) ? true : false;
}

export {controlCity};
