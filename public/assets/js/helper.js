function localeNumberToNumber(str) {
    let num = str.replace(/\./g, '');
    return parseInt(num);
}

function ToNumber(str) {
    let num = str.replace(/\./g, '');
    return parseInt(num);
}

function toLocaleNumber(num, digit) {
    if (typeof num === 'string')
        num = Number(num);
    return num.toLocaleString('id-ID', {
        minimumFractionDigits: digit,
        maximumFractionDigits: digit
    });
}