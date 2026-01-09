

let IntervaloReloj;
/*------- Reloj en tiempo real para incrustar en paginas html-------*/
function muestraReloj(elemento) {
  IntervaloReloj = setInterval(reloj_html, 1000);
  function reloj_html() {
    if (document.querySelector("#" + elemento)) {
      var fechaHora = new Date();
      var horas = fechaHora.getHours();
      var minutos = fechaHora.getMinutes();
      var segundos = fechaHora.getSeconds();
      var fecha = new Date();
      var diames = fecha.getDate();
      var diasemana = fecha.getDay();
      var mes = fecha.getMonth() + 1;
      var ano = fecha.getFullYear();
      var textosemana = new Array(7);
      textosemana[0] = "Domingo";
      textosemana[1] = "Lunes";
      textosemana[2] = "Martes";
      textosemana[3] = "Mi&eacute;rcoles";
      textosemana[4] = "Jueves";
      textosemana[5] = "Viernes";
      textosemana[6] = "S&aacute;bado";
      var textomes = new Array(12);
      textomes[1] = "Enero";
      textomes[2] = "Febrero";
      textomes[3] = "Marzo";
      textomes[4] = "Abril";
      textomes[5] = "Mayo";
      textomes[6] = "Junio";
      textomes[7] = "Julio";
      textomes[8] = "Agosto";
      textomes[9] = "Septiembre";
      textomes[10] = "Octubre";
      textomes[11] = "Noviembre";
      textomes[12] = "Diciembre";
      if (horas < 10) {
        horas = "0" + horas;
      }
      if (minutos < 10) {
        minutos = "0" + minutos;
      }
      if (segundos < 10) {
        segundos = "0" + segundos;
      }
      document.querySelector("#" + elemento).innerHTML =
      "<div class=\"horas\">" +
      horas +
      ":" +
      minutos +
      ":" +
      segundos + 
    "</div>" +


        textosemana[diasemana] +
        ", " +
        diames +
        " de " +
        textomes[mes] +
        " de " +
        ano 
        
        ;
    } else {
      clearTimeout(IntervaloReloj);
    }
  }
}

function ImgLogo() {
  document.body.style.backgroundImage = "url(" + "" + ")";
  let render = `<div id="verReloj">
        <div id="logo_img"><img src="/media/img_logo.svg" alt="logo">
            <div id="reloj"></div>
        </div>
    </div>`;
  document.querySelector("#contenedor").insertAdjacentHTML("beforeend", render);
  muestraReloj("reloj");
}