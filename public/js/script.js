$(document).ready(function() {
    $("#data_locacao, #data_entrega").datepicker();
});

jQuery(function($) {
    $.datepicker.regional['pt-BR'] = {
        currentText: 'Hoje',
        monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        dayNames: ['Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'S&aacute;bado'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        dayNamesMin: ['Do', 'Se', 'Te', 'Qa', 'Qi', 'Se', 'Sa'],
        weekHeader: 'Sm',
        firstDay: 0,
        dateFormat: 'dd/mm/yy',
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        changeYear: true};
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
});

$(document).ready(function() {
    $(".obg").blur(function() {
        if ($(this).val() == "") {
            $(this).css({"border-color": "#F00", "padding": "2px"});
        } else {
            $(this).css({"border-color": "#000", "padding": "2px"});
        }
    });
});
