const result = window.document.getElementById('result');
var ul = document.getElementById("myUL");
var ul2 = document.getElementById('myUL2');

// Puxa os dados do arquivo onde está a lista de processadores, no formato txt;
fetch('./processadores/TodosProcessadores.txt')
    .then(response => response.text())
    .then(text => {
        var processadores = text.split('\n');
        for(let i = 0; i< processadores.length; i++){
            ul.innerHTML += `<li><a href="#">${processadores[i]}</a></li>`;
            ul2.innerHTML += `<li><a href="#">${processadores[i]}</a></li>`;
        }
});

function sugestaoProcessador1() {
    // Declarando variaveis
    var input, filter, li, a, i, txtValue;
    input = document.getElementById('processador1');
    filter = input.value.toUpperCase();
    li = ul.getElementsByTagName('li');

    // Verifica a quantidade de caracter no input e através disso oculta ou mostra a lista
    if(input.value.length <= 2){ ul.classList.add('hidden');
    } else{ ul.classList.remove('hidden');}
  
    // Percorra todos os itens da lista e oculte aqueles que não correspondem à consulta de pesquisa
    for (i = 0; i < li.length; i++) {
      a = li[i].getElementsByTagName("a")[0];
      txtValue = a.textContent || a.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) { li[i].style.display = "";
      } else { li[i].style.display = "none"; }
    }
};

ul.addEventListener('click', function(event) {
    const clickedElement = event.target;
    const clickedText = clickedElement.textContent;
    input = document.getElementById('processador1');
    input.value = clickedText;
    ul.classList.add('hidden')
});

function sugestaoProcessador2() {
    // Declarando variaveis
    var input, filter, li, a, i, txtValue;
    input = document.getElementById('processador2');
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL2");
    li = ul.getElementsByTagName('li');

    // Verifica a quantidade de caracter no input e através disso oculta ou mostra a lista
    if(input.value.length <= 2){ ul.classList.add('hidden');
    } else{ ul.classList.remove('hidden');}
  
    // Percorra todos os itens da lista e oculte aqueles que não correspondem à consulta de pesquisa
    for (i = 0; i < li.length; i++) {
      a = li[i].getElementsByTagName("a")[0];
      txtValue = a.textContent || a.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) { li[i].style.display = "";
      } else { li[i].style.display = "none"; }
    }
};

ul2.addEventListener('click', function(event) {
    const clickedElement = event.target;
    const clickedText = clickedElement.textContent;
    input = document.getElementById('processador2');
    input.value = clickedText;
    ul.classList.add('hidden')
});

function buscaSelect() {
    // Obter o valor da caixa de pesquisa
    var input, filter, select, options, i, txtValue;
    input = document.getElementById("busca");
    filter = input.value.toUpperCase();
    
    // Obter o elemento de seleção e suas opções
    select = document.getElementById("processador1");
    options = select.getElementsByTagName("option");
    
    // Iterar sobre as opções e mostrar apenas as que correspondem à pesquisa
    for (i = 0; i < options.length; i++) {
      txtValue = options[i].textContent || options[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        document.getElementById("processador1").selectedIndex = i;
        document.getElementById("processador1").style.backgroundColor = 'rgb(151, 228, 151)';
        setTimeout(function() {
            document.getElementById("processador1").style.backgroundColor = 'white';
          }, 1500);
      }
    }
  }

addEventListener('keyup', (event) => {
    if (event.keyCode === 13) {
        enviar();
    }
})

function enviar() {
    const processador1 = document.getElementById('processador1').value;
    const processador2 = document.getElementById('processador2').value;
    
    if (!processador1 || !processador2){
        alert('Preencha os dois campos');
        return;
    }

    const question = `Compare o processador ${processador1} com o processador ${processador2} e no final diga qual é o melhor.`;
    result.value = '';
    result.style.backgroundImage = 'url("https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif")';
    sendQuestion(question);
}

function sendQuestion(sQuestion) {
    const OPENAI_API_KEY = "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";

    fetch("https://api.openai.com/v1/chat/completions", {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: "Bearer " + OPENAI_API_KEY,
        },
        body: JSON.stringify({
            model: "gpt-3.5-turbo",
            messages: [
                { role: "system", content: "You are a helpful assistant." },
                { role: "user", content: sQuestion }
            ],
            max_tokens: 2048,
            temperature: 0.5,
        }),
    })
    .then((response) => response.json())
    .then((json) => {
        result.value = ""; // Limpa a textarea antes de adicionar a resposta

        if (json.error?.message) {
            result.style.backgroundImage = 'none';
            result.value += `Error: ${json.error.message}`;
        } else if (json.choices?.[0]?.message?.content) {
            var text = json.choices[0].message.content || "Sem resposta";

            result.value += "Nixem Chat: " + text;
            result.style.backgroundImage = 'none';
        }

        result.scrollTop = result.scrollHeight;
    })
    .catch((error) => {
        console.error("Error:", error);
        result.value = "Erro ao carregar resposta.";
    });

    if (result.value) result.value += "\n\n\n";
    result.scrollTop = result.scrollHeight;
}