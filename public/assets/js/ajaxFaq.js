function setStatus(val,id) {
    $.ajax({
        url: './status/'+id,
        type: "POST",
        data: {
            "utile": val
        },
        async: true,
        success: function (data) {
            console.log(data)
        }
    });
}
function addQuestion() {
    $.ajax({
        url: './ajout',
        type: "POST",
        data: {
            "question": $('#votre_question').val()
        },
        async: true,
        success: function (data) {
            console.log(data)
        }
    });
}
function goAjax(val) {
    $.ajax({
        url: './search',
        type: "POST",
        data: {
            "search": val
        },
        async: true,
        success: function (data) {
            console.log(data.message.length);
            $('div#ajax-results').html('');
            if (data.message.length > 0) {
                for (i = 0; i < data.message.length; i++) {
                    var idQ = data.message[i].id;
                    var question = data.message[i].question;

                    var reponse = data.message[i].reponse;
                    $('div#ajax-results').append('<div class="bg-light p-3 rounder mb-3 border rounded"><div class="col-md-12 mb-5"><h3 class="font-weight-bold orange">' +
                        question + '</h3><hr class="mb-5">' +
                        reponse.replace(/\n/g, '<br>') + '</div>' +
                        '<hr>' +
                        '<div class="col-md-12 text-center mt-2 p-3 bg-dark-light border rounded">' +
                        '<small>Afin d\'améliorer nos services, merci de répondre à cette question !</small>' +
                        '<h4>Cette réponse vous à-t-elle été utile ?</h4>' +
                        '<button onclick="setStatus(\'OUI\','+idQ+')" class="btn-rounded border-0">OUI</button> ' +
                        '<button onclick="setStatus(\'NON\','+idQ+')" class="btn-rounded border-0">NON</button>' +
                        '</div></div>');
                }
            } else {
                $('div#ajax-results').html("<div class='border w-100 text-center rounded bg-light p-5'>" +
                    "<h1>:( Aucun résultats...</h1>" +
                    "<h3>Posez-nous votre question nous y répondrons au plus vite !</h3>"+
                    "<div class='mt-3 mb-3 rounded p-2 bg-white border'><input type='text' class='input col-md-10' id='votre_question' name='votre_question' value="+ val +" placeholder='Entrez votre question ici !'>" +
                    "<button onclick='addQuestion()' class='btn-rounded border-0 col-md-2'>Envoyer</button></div>"+
                    "Une question urgente ? Un problème non résolu ? <br><br><a href='mailto:ww@mgel.fr'>" +
                    "<i class='fa fas fa-at'></i> Contactez-nous par mail</a><br><a href='tel:0383300300'>" +
                    "<i class='fa fas fa-mobile'></i> Contactez-nous par téléphone</a></div>");
            }
            $('body').scroll('#ajax-results');
            console.log(data.message)
        }
    });
}

$('#search').on('keyup', function () {
    goAjax($('#search').val());
});
$('.q-hover').on('click', function () {
    that = $(this);
    $('#search').val(that.text());
    goAjax(that.text());
})