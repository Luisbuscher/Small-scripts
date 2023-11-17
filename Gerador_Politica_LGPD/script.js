const gerar = document.getElementById('botaoGerarPolitica');
var texto = document.getElementById('texto');
var data = new Date();
var dia, mes, ano, spanNomeDoSite, spanLinkDoSite, spanEmail, spanDia, spanMes, spanAno;
var inputs = document.getElementsByClassName('uk-input')
var nomeDoSite = window.document.getElementById('nome_site')
var linkDoSite = window.document.getElementById('link_site')
var email = window.document.getElementById('email')

inputs[0,1,2].addEventListener('input', () => {
    for(let i = 0; i < inputs.length; i++){
        if(inputs[i].value.length > 2 && i == inputs.length - 1){
            document.getElementById('botaoGerarPolitica').disabled = false;
            gerar.style.background = 'rgba(33, 33, 255, 0.795)'
            return;
        }else if(inputs[i].value.length < 2 && i == inputs.length - 1){
            document.getElementById('botaoGerarPolitica').disabled = true;
            gerar.style.background = 'rgba(68, 68, 80, 0.795)'
            return;
        }
    }

})

function gerar_politica(){
    
    definirData();

    acoplarVariaveis();

    document.getElementById('politica').removeAttribute('hidden');
}

function validarInput(){
    for (let i = 0; i < inputs.length; i++){
        if(inputs[i].value == ''){
            window.alert('Preencha todos os campos!')
            return
        }
    }
    gerar.removeAttribute('disabled')
}

function copiarTextoHTML(){

    navigator.clipboard.writeText(texto.innerHTML)

    window.alert('Texto HTML copiado')

}

function acoplarVariaveis(){

    spanNomeDoSite = document.getElementsByClassName('nomeDoSite')
    spanLinkDoSite = document.getElementsByClassName('linkDoSite')
    spanEmail = document.getElementsByClassName('email')
    spanDia = document.getElementsByClassName('dia')
    spanMes = document.getElementsByClassName('mes')
    spanAno = document.getElementsByClassName('ano')

    for (i = 0; i < spanNomeDoSite.length; i++){
        spanNomeDoSite[i].innerHTML = nomeDoSite.value;
    }

    for (i = 0; i < spanLinkDoSite.length; i++){
        spanLinkDoSite[i].innerHTML = linkDoSite.value;
    }

    for (i = 0; i < spanEmail.length; i++){
        spanEmail[i].innerHTML = email.value;
    }

    for (i = 0; i < spanDia.length; i++){
        spanDia[i].innerHTML = dia;
    }

    for (i = 0; i < spanMes.length; i++){
        spanMes[i].innerHTML = mes;
    }

    for (i = 0; i < spanAno.length; i++){
        spanAno[i].innerHTML = ano;
    }

}

function definirData(){
    mes = data.getMonth()
    dia = data.getDate()
    ano = data.getFullYear()

    let dataPorExtenso = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto','setembro', 'outubro','novembro', 'dezembro']

    mes = dataPorExtenso[mes]
}