 // Cards com Botões acionamento individual
 $('.ts-cardColor').click(function() {
  $('.ts-cardColor').addClass('ts-cardColor-active');
  $('.ts-cardColor').removeClass('ts-shadowOff');
  $('.ts-cardColor1').removeClass('ts-cardColor-active');
  $('.ts-cardColor2').removeClass('ts-cardColor-active');
  $('.ts-cardColor3').removeClass('ts-cardColor-active');
  $('.ts-cardColor0').removeClass('ts-cardColor-active');
});
$('.ts-cardColor1').click(function() {
  $('.ts-cardColor1').addClass('ts-cardColor-active');
  $('.ts-cardColor1').removeClass('ts-shadowOff');
  $('.ts-cardColor').removeClass('ts-cardColor-active');
  $('.ts-cardColor2').removeClass('ts-cardColor-active');
  $('.ts-cardColor3').removeClass('ts-cardColor-active');
  $('.ts-cardColor0').removeClass('ts-cardColor-active');
});
$('.ts-cardColor2').click(function() {
  $('.ts-cardColor2').addClass('ts-cardColor-active');
  $('.ts-cardColor2').removeClass('ts-shadowOff');
  $('.ts-cardColor').removeClass('ts-cardColor-active');
  $('.ts-cardColor1').removeClass('ts-cardColor-active');
  $('.ts-cardColor3').removeClass('ts-cardColor-active');
  $('.ts-cardColor0').removeClass('ts-cardColor-active');
});
$('.ts-cardColor3').click(function() {
  $('.ts-cardColor3').addClass('ts-cardColor-active');
  $('.ts-cardColor3').removeClass('ts-shadowOff');
  $('.ts-cardColor').removeClass('ts-cardColor-active');
  $('.ts-cardColor1').removeClass('ts-cardColor-active');
  $('.ts-cardColor2').removeClass('ts-cardColor-active');
  $('.ts-cardColor0').removeClass('ts-cardColor-active');
});
$('.ts-cardColor0').click(function() {
  $('.ts-cardColor0').addClass('ts-cardColor-active');
  $('.ts-cardColor0').removeClass('ts-shadowOff');
  $('.ts-cardColor').removeClass('ts-cardColor-active');
  $('.ts-cardColor1').removeClass('ts-cardColor-active');
  $('.ts-cardColor2').removeClass('ts-cardColor-active');
  $('.ts-cardColor3').removeClass('ts-cardColor-active');
});

// Cards com Botões acionamento ligado ao Select de StatusDemanda
let btn = document.querySelectorAll('button');
/*   let select = document.querySelector('select'); */
let select = document.getElementById('FiltroStatusDemanda')

function troca(e) {
  select.value = e.currentTarget.id;
}

btn.forEach((el) => {
  el.addEventListener('click', troca);
})

function mudarSelect(valor) {
  $('.ts-cardColor').removeClass('ts-cardColor-active');
  $('.ts-cardColor1').removeClass('ts-cardColor-active');
  $('.ts-cardColor2').removeClass('ts-cardColor-active');
  $('.ts-cardColor3').removeClass('ts-cardColor-active');
  $('.ts-cardColor0').removeClass('ts-cardColor-active');
  $('.ts-cardColor' + valor).addClass('ts-cardColor-active');
  $('.ts-cardColor' + valor).removeClass('ts-shadowOff');

}