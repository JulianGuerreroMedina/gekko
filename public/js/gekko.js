document.addEventListener("DOMContentLoaded", function () {
  inicia_instantaneas();
});

let OrigenURL = window.location.origin;
console.log(OrigenURL);

let contenido;
let total_diapositivas = 0;
let diapositiva_actual = 0;
let timer;
let duracion = 15;
let reloj_descendente = duracion;


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
/*------- Reloj en tiempo real para incrustar en paginas html-------*/

function graficas() {
  cual_grafica = contenido[diapositiva_actual][2];
  //console.log(cual_grafica);
  let datos = new FormData();
  datos.append("funcion", "graficas");
  datos.append("cual_grafica", cual_grafica);
  fetch("/api/CargarContenido", { method: "POST", body: datos })
    .then((res) => res.text())
    .then((data) => {
      if (data === "error") {
        //console.log(('Ocurrio un error cargando la grafica'))
      } else if (data !== "error") {
        document.querySelector("#contenedor").innerHTML = "";
        let render = `<div id="temporizador"></div><div id="graficas">Ver aqui las graficas ${data}</div>`;
        document
          .querySelector("#contenedor")
          .insertAdjacentHTML("beforeend", render);
      }
    });
}

function CargarDiapositiva() {
  document.querySelector("#contenedor").innerHTML = "";
  que_ver = contenido[diapositiva_actual][0];
  console.log(contenido[diapositiva_actual]);
  duracion = contenido[diapositiva_actual][1];
  reloj_descendente = duracion;

  if (que_ver == "ImgLogo") {
    ImgLogo();
  } else if (que_ver == "1") {
    imagenes();
  } else if (que_ver == "2") {
    videos();
  } else if (que_ver == "3") {
    graficas();
  }
}

function temporizador() {
  clearTimeout(timer);
  if (reloj_descendente >= 1) {
    if (document.querySelector("#temporizador")) {
      document.querySelector("#temporizador").innerHTML =
        '<div id="contiene_contador"><span>' +
        reloj_descendente +
        "</span></div>";
    }
    timer = setTimeout(function () {
      temporizador();
    }, 1000);
    reloj_descendente -= 1;
  } else if (reloj_descendente == 0) {
    reloj_descendente = duracion;
    diapositiva_actual += 1;
    VerDiapositiva();
  }
}

function VerDiapositiva() {
  if (diapositiva_actual < total_diapositivas) {
    temporizador();
    CargarDiapositiva();
  } else if (diapositiva_actual >= total_diapositivas) {
    inicia_instantaneas();
  }
}

function inicia_instantaneas() {
  clearTimeout(timer);
  total_diapositivas = 0;
  diapositiva_actual = 0;
  let datos = new FormData();
  datos.append("funcion", "Instantaneas");
  fetch("/api/CargarContenido", { method: "POST", body: datos })
    .then((response) => response.text())
    .then((data) => manejardatos(data));

  const manejardatos = (data) => {
    contenido = JSON.parse(data);
    total_diapositivas = contenido.length;
    function shuffleArray(inputArray) {
      inputArray.sort(() => Math.random() - 0.5);
    }
    shuffleArray(contenido);
    console.log(contenido);
    document.querySelector("#contenido").innerHTML = "";
    let render = `<div id="btn_switch" class="arrows-maximize" onclick="statusFullScreen()"></div><div id="contenedor">contenedor</div>`;
    document
      .querySelector("#contenido")
      .insertAdjacentHTML("beforeend", render);
    VerDiapositiva();
  };

  handleInactivity();
}

function ImgLogo() {
  document.body.style.backgroundImage = "url(" + "" + ")";
  //document.querySelector("#contenido").innerHTML = '';
  let render = `<div id="verReloj">
        <div id="logo_img"><img src="/media/img_logo.svg" alt="logo">
            <div id="reloj"></div>
        </div>
    </div>`;
  document.querySelector("#contenedor").insertAdjacentHTML("beforeend", render);
  muestraReloj("reloj");
}

function imagenes() {
  let imagen = contenido[diapositiva_actual][2];
  document.body.style.backgroundImage = "url(media/" + imagen + ")";
  let render = `<div id="temporizador"></div>`;
  document.querySelector("#contenedor").insertAdjacentHTML("beforeend", render);
}

function videos() {
  //modal.style.margin = "auto";
  document.body.style.backgroundImage = "url(" + "" + ")";
  clearTimeout(timer);
  let video = contenido[diapositiva_actual][2];

  fetch(`${OrigenURL}/media/${video}`)
    .then((response) => {
      if (response.ok) {
          // Video existe
          let render = `<video src="${OrigenURL}/media/${video}" id="myVideo" autoplay="autoplay" muted="">video not supported</video>`;
          document.querySelector("#contenedor").insertAdjacentHTML("beforeend", render);
          let final_video = document.getElementById("myVideo");
          final_video.addEventListener("ended", () => {
          myHandler();
        });
      } else {
        // Video no existe
        myHandler();
      }
    })
    .catch((error) => {
      console.error(`Error fetching video: ${error}`);
    });

  function myHandler() {
    reloj_descendente = duracion;
    diapositiva_actual += 1;
    VerDiapositiva();
  }
}

// Tiempo de inactividad en milisegundos (por ejemplo, 5 segundos)
const INACTIVITY_TIME = 5000;
let inactivityTimeout;

// Función para ocultar boton
function hideButton() {
  document.getElementById("btn_switch").style.visibility = "hidden";
  document.getElementById("btn_switch").style.visibility = "hidden";
  //
}

// Función para mostrar boton
function showButton() {
  document.getElementById("btn_switch").style.visibility = "visible";
}

// Función para manejar la inactividad
function handleInactivity() {
  clearTimeout(inactivityTimeout);
  inactivityTimeout = setTimeout(hideButton, INACTIVITY_TIME);
}

// Evento para detectar movimiento del ratón
document.addEventListener("mousemove", () => {
  showButton();
  handleInactivity();
});

//funcion para detectar el estado de la pantalla
function statusFullScreen() {
  let fullscreen = 0;

  if (
    document.fullscreenElement ||
    document.webkitFullscreenElement ||
    document.mozFullScreenElement ||
    document.msFullscreenElement
  ) {
    if (document.exitFullscreen) {
      fullscreen = 1;
    } else if (document.mozCancelFullScreen) {
      // Firefox
      fullscreen = 1;
    } else if (document.webkitExitFullscreen) {
      // Chrome, Safari y Opera
      fullscreen = 1;
    } else if (document.msExitFullscreen) {
      // IE/Edge
      fullscreen = 1;
    }
  }

  console.log("fullscreen " + fullscreen);

  if (fullscreen == 0) {
    document.getElementById("btn_switch").classList.remove("arrows-maximize");
    document.getElementById("btn_switch").classList.add("arrows-minimize");
    enterFullScreen();
  } else if (fullscreen == 1) {
    document.getElementById("btn_switch").classList.remove("arrows-minimize");
    document.getElementById("btn_switch").classList.add("arrows-maximize");
    exitFullScreen();
  }
}

function enterFullScreen() {
  const doc = document.documentElement;
  if (doc.requestFullscreen) {
    doc.requestFullscreen().catch((err) => {
      console.error(
        `Error al intentar entrar en pantalla completa: ${err.message}`
      );
    });
  } else if (doc.mozRequestFullScreen) {
    // Firefox
    doc.mozRequestFullScreen().catch((err) => {
      console.error(
        `Error al intentar entrar en pantalla completa: ${err.message}`
      );
    });
  } else if (doc.webkitRequestFullscreen) {
    // Chrome, Safari y Opera
    doc.webkitRequestFullscreen().catch((err) => {
      console.error(
        `Error al intentar entrar en pantalla completa: ${err.message}`
      );
    });
  } else if (doc.msRequestFullscreen) {
    // IE/Edge
    doc.msRequestFullscreen().catch((err) => {
      console.error(
        `Error al intentar entrar en pantalla completa: ${err.message}`
      );
    });
  }
}

function exitFullScreen() {
  if (
    document.fullscreenElement ||
    document.webkitFullscreenElement ||
    document.mozFullScreenElement ||
    document.msFullscreenElement
  ) {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
      // Firefox
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      // Chrome, Safari y Opera
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      // IE/Edge
      document.msExitFullscreen();
    }
  }
}
