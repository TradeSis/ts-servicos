 // Cards com Botões acionamento individual
 $('.ts-cardColor1').click(function() {
    $('.ts-cardColor1').addClass('ts-cardColor-active');
    $('.ts-cardColor1').removeClass('ts-shadowOff');
    $('.ts-cardColor2').removeClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-cardColor-active');
  });
  $('.ts-cardColor2').click(function() {
    $('.ts-cardColor2').addClass('ts-cardColor-active');
    $('.ts-cardColor2').removeClass('ts-shadowOff');
    $('.ts-cardColor1').removeClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-cardColor-active');
  });
  $('.ts-cardColor3').click(function() {
    $('.ts-cardColor3').addClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-shadowOff');
    $('.ts-cardColor1').removeClass('ts-cardColor-active');
    $('.ts-cardColor2').removeClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-cardColor-active');
  });
  $('.ts-cardColor4').click(function() {
    $('.ts-cardColor4').addClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-shadowOff');
    $('.ts-cardColor1').removeClass('ts-cardColor-active');
    $('.ts-cardColor2').removeClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-cardColor-active');
  });
  $('.ts-cardColor5').click(function() {
    $('.ts-cardColor5').addClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-shadowOff');
    $('.ts-cardColor1').removeClass('ts-cardColor-active');
    $('.ts-cardColor2').removeClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-cardColor-active');
  });
  $('.ts-cardColor6').click(function() {
    $('.ts-cardColor6').addClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-shadowOff');
    $('.ts-cardColor1').removeClass('ts-cardColor-active');
    $('.ts-cardColor2').removeClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-cardColor-active');
  });
  
  // Cards com Botões acionamento ligado ao Select de StatusDemanda
  let btn = document.querySelectorAll('button');
  /*   let select = document.querySelector('select'); */
  let select = document.getElementById('FiltroStatusContrato')
  
  function troca(e) {
    select.value = e.currentTarget.id;
  }
  
  btn.forEach((el) => {
    el.addEventListener('click', troca);
  })
  
  function mudarSelect(valor) {
    $('.ts-cardColor1').removeClass('ts-cardColor-active');
    $('.ts-cardColor2').removeClass('ts-cardColor-active');
    $('.ts-cardColor3').removeClass('ts-cardColor-active');
    $('.ts-cardColor4').removeClass('ts-cardColor-active');
    $('.ts-cardColor5').removeClass('ts-cardColor-active');
    $('.ts-cardColor6').removeClass('ts-cardColor-active');
    $('.ts-cardColor' + valor).addClass('ts-cardColor-active');
    $('.ts-cardColor' + valor).removeClass('ts-shadowOff');
  
  }