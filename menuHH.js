$('.nordeste').click(function(){ /* .nordeste, estamos usando a classe $('ACESSAR ALGO')*/
    $('.menuLateral ul .itensNordeste').toggleClass('mostra');
   /*  $('.menuLateral ul .seta1').toggleClass('gira'); */ /* alternativa giro da seta */
});

$('.sudeste').click(function(){
    $('.menuLateral ul .itensSudeste').toggleClass('mostra');
});

$('.btnAbre').click(function(){
   
    $('.menuLateral').toggleClass('mostra');
    $('.diviFrame').toggleClass('mostra');

});

$('.btnFecha').click(function(){
    $('.menuLateral').toggleClass('mostra');
});

$('.nordeste').mouseover(function(){      /* gira ponteira ao passar o mouse */
    $('.menuLateral ul .seta1').toggleClass('gira');
});

$('.nordeste').mouseout(function(){       /* gira ponteira ao sair o mouse */
    $('.menuLateral ul .seta1').toggleClass('gira');
})

$('.sudeste').mouseover(function(){      /* gira ponteira ao passar o mouse */
    $('.menuLateral ul .seta2').toggleClass('gira');
});

$('.sudeste').mouseout(function(){       /* gira ponteira ao sair o mouse */
    $('.menuLateral ul .seta2').toggleClass('gira');
})
/*
const $menuLateral = $('.menuLateral');
$(document).mouseup(e => {
    if(!$menuLateral.is(e.target)
        && $menuLateral.has(e.target).length === 0)
     {
        $menuLateral.removeClass('mostra');
    }
});
*/