$(document).ready(function() {
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData();
        formData.append('fileInput', $('#fileInput')[0].files[0]);
        formData.append('separator', $('#separator').val());

        $.ajax({
            url: 'src/server/index.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    var products = JSON.parse(response);
                    var tableBody = $('#productTable tbody');
                    tableBody.empty();

                    products.forEach(function(product) {
                        var row = $('<tr>');

                        if (product.isNegativePrice) {
                            row.css('background-color', 'red');
                        }

                        row.append('<td>' + product.name + '</td>');
                        row.append('<td>' + product.code + '</td>');
                        row.append('<td>' + (product.price ? product.price.toFixed(2) : '0.00') + '</td>');

                        var actionButton = '';
                        if (product.hasEvenNumber) {
                            actionButton = '<button onclick="copyToClipboard(atob(\'' + btoa(JSON.stringify(product)) + '\'))">Copiar</button>';
                        }
                        row.append('<td>' + actionButton + '</td>');

                        tableBody.append(row);
                    });
                } catch (error) {
                    console.error('Erro ao processar JSON:', error);
                }
            }
        });
    });
});

function copyToClipboard(data) {
    if (!navigator.clipboard) {
        fallbackCopy(data);
        return;
    }

    navigator.clipboard.writeText(data).then(() => {
        console.log('Texto copiado para a área de transferência!');
        alert('Texto copiado!');
    }).catch((err) => {
        console.error('Falha ao copiar: ', err);
        fallbackCopy(data);
    });
}

// Método de fallback para navegadores mais antigos
function fallbackCopy(data) {
    var tempInput = document.createElement('textarea'); 
    tempInput.value = data;
    document.body.appendChild(tempInput);
    tempInput.select();
    
    try {
        document.execCommand('copy');
        console.log('Texto copiado com execCommand!');
        alert('Texto copiado!');
    } catch (err) {
        console.error('Falha ao copiar usando execCommand:', err);
    }

    document.body.removeChild(tempInput);
}
