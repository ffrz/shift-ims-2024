function localeNumberToNumber(str) {
    let num = str.replace(/\./g, '');
    return parseInt(num);
}

function toLocaleNumber(num) {
    return num.toLocaleString('id-ID');
}