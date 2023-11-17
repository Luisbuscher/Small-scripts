const area = window.document.getElementById('area');
const caracteres = window.document.getElementById('caracteres')
const palavras = window.document.getElementById('palavras')
var textoLength = 0
var texto = ''

area.addEventListener('keypress',() =>{
    textoLength = area.value.length
    texto = area.value
    texto = texto.trim()
    let palavras_ = 0
    let validar = false
    
    for(let i = 0; i < textoLength; i++){
        if(texto[i] == ' ' && validar == false){
            palavras_ += 1
            validar = true
        }
        if(texto[i] != ' '){
            validar = false
        }
    }

    caracteres.innerHTML = `O texto inserido tem ${textoLength + 1} caracteres!`
    palavras.innerHTML = `O texto inserido tem ${palavras_ + 1} palavras!`
})

function apagar(){
    area.value = ''
    caracteres.innerHTML = `O texto inserido tem 0 caracteres!`
    palavras.innerHTML = `O texto inserido tem 0 palavras!`
    textoLength = 0
    texto = ''
}