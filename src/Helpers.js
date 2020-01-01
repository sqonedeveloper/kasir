export var rupiah = (str) => {
   return str.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

export var replaceDotWithEmpty = (str) => {
   return str.toString().replace('.', '')
}

export var toNumeric = (str) => {
   return parseInt(str)
}