'use strict';

const paginas = document.getElementById('paginas');

function comprobarPaginas() {
    const texto = paginas.value.trim();
    const n = Number(texto);
    const entero = /^[+-]?(0|[1-9][0-9]*)$/.test(texto);
    const valido = entero && Number.isInteger(n)
        && n >= Number(paginas.dataset.min) && n <= Number(paginas.dataset.max);

    paginas.setCustomValidity(valido ? '' : 'Escribe un entero entre 1 y 3000.');
}

paginas.addEventListener('input', comprobarPaginas);
comprobarPaginas();

document.getElementById('rellenar').addEventListener('click', () => {
    const ejemplo = {
        titulo: '  Dune  ', autor: 'Frank Herbert', paginas: '412',
        genero: 'ciencia', fechaAlta: '',
    };
    for (const [campo, valor] of Object.entries(ejemplo)) {
        document.getElementById(campo).value = valor;
    }
    comprobarPaginas();
});

// La validación del navegador ayuda al usuario. El botón con formnovalidate
// permite omitirla para observar que PHP sigue comprobando el mismo envío.
