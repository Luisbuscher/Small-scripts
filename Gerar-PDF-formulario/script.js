window.addEventListener('load', function() {
    document.getElementById('gerarPDFButton').addEventListener('click', gerarPDF);
});

function gerarPDF() {
    const jsPDF = window.jspdf.jsPDF;
    let doc = new jsPDF();
    
    // Selecionando os elementos da página
    let formato = document.getElementById("formato").value;
    let tom_de_voz = document.getElementById("tom_de_voz").value;
    let figurino = document.getElementById("figurino").value;
    let cenario = document.getElementById("cenario").value;
    let persona = document.getElementById("persona").value;
    let nivel_do_papo = document.getElementById("nivel_do_papo").value;
    let ambiente = document.getElementById("ambiente").value;
    let tempo = document.getElementById("tempo").value;
    let local = document.getElementById("local").value;
    let duracao = document.getElementById("duracao").value;
    let edicao = document.getElementById("edicao").value;
    let tema = document.getElementById("tema").value;
    let copy = document.getElementById("copy").value;
    let hashtags = document.getElementById("hashtags").value;
    let observacoes = document.getElementById("observacoes").value;

    // Adicionando ao documento PDF
    let formatoLines = doc.splitTextToSize('Formato: ' + formato, 180);
    doc.text(formatoLines, 10, 10);

    let tom_de_vozLines = doc.splitTextToSize('Tom de voz: ' + tom_de_voz, 180);
    let tom_de_vozY = 10 + (formatoLines.length * 10);
    doc.text(tom_de_vozLines, 10, tom_de_vozY);

    let figurinoLines = doc.splitTextToSize('Figurino: ' + figurino, 180);
    let figurinoY = tom_de_vozY + (tom_de_vozLines.length * 10);
    doc.text(figurinoLines, 10, figurinoY);

    let cenarioLines = doc.splitTextToSize('Cenário: ' + cenario, 180);
    let cenarioY = figurinoY + (figurinoLines.length * 10);
    doc.text(cenarioLines, 10, cenarioY);

    let personaLines = doc.splitTextToSize('Persona: ' + persona, 180);
    let personaY = cenarioY + (cenarioLines.length * 10);
    doc.text(personaLines, 10, personaY);

    let nivel_do_papoLines = doc.splitTextToSize('Nível do Papo: ' + nivel_do_papo, 180);
    let nivel_do_papoY = personaY + (personaLines.length * 10);
    doc.text(nivel_do_papoLines, 10, nivel_do_papoY);

    let ambienteLines = doc.splitTextToSize('Ambiente: ' + ambiente, 180);
    let ambienteY = nivel_do_papoY + (nivel_do_papoLines.length * 10);
    doc.text(ambienteLines, 10, ambienteY);

    let tempoLines = doc.splitTextToSize('Tempo: ' + tempo, 180);
    let tempoY = ambienteY + (ambienteLines.length * 10);
    doc.text(tempoLines, 10, tempoY);

    let localLines = doc.splitTextToSize('Local: ' + local, 180);
    let localY = tempoY + (tempoLines.length * 10);
    doc.text(localLines, 10, localY);

    let duracaoLines = doc.splitTextToSize('Duração: ' + duracao, 180);
    let duracaoY = localY + (localLines.length * 10);
    doc.text(duracaoLines, 10, duracaoY);

    let edicaoLines = doc.splitTextToSize('Edição: ' + edicao, 180);
    let edicaoY = duracaoY + (duracaoLines.length * 10);
    doc.text(edicaoLines, 10, edicaoY);

    let temaLines = doc.splitTextToSize('Tema: ' + tema, 180);
    let temaY = edicaoY + (edicaoLines.length * 10);
    doc.text(temaLines, 10, temaY);

    let copyLines = doc.splitTextToSize('Copy: ' + copy, 180);
    let copyY = temaY + (temaLines.length * 10);
    doc.text(copyLines, 10, copyY);

    let hashtagsLines = doc.splitTextToSize('Hashtags: ' + hashtags, 180);
    let hashtagsY = copyY + (copyLines.length * 10);
    doc.text(hashtagsLines, 10, hashtagsY);

    let observacoesLines = doc.splitTextToSize('Observações: ' + observacoes, 180);
    let observacoesY = hashtagsY + (hashtagsLines.length * 10);
    doc.text(observacoesLines, 10, observacoesY);

    // Salvando o PDF
    doc.save('Roteiro_Audiovisual.pdf');
}
