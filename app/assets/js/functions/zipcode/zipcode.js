/* control zipcode */
const controlZipcode = function(champ){
    const zipcodeRegex = new RegExp('^[0-9]{5}$');
    return champ.value.match(zipcodeRegex) ? true : false;
}
export {controlZipcode};
