document.addEventListener("DOMContentLoaded", function () {
  inicia_instantaneas();
});

let OrigenURL = window.location.origin;
let contenido;
let total_diapositivas = 0;
let diapositiva_actual = 0;
let timer;
let duracion = 15;
let reloj_descendente = duracion;
let inactivity_time = 5000; // Tiempo de inactividad en milisegundos (por ejemplo, 5 segundos)
let inactivityTimeout;

function imagenes() {
  let imagen = contenido[diapositiva_actual][2];
  fetch(`${OrigenURL}/media/${imagen}`) // Verificar si existe la imagen
    .then((response) => {
      if (response.ok) {
          // la imagen si existe
          document.body.style.backgroundImage = "url(media/" + imagen + ")";
          let render = `<div id="temporizador"></div>`;
          document.querySelector("#contenedor").insertAdjacentHTML("beforeend", render);        
        } else {
            // imagen  no existe
            myHandler();
        }
    })
    .catch((error) => {
      console.error(`Error fetching imagen: ${error}`);
    });
}

function videos() {
  //modal.style.margin = "auto";
  document.body.style.backgroundImage = "url(" + "" + ")";
  clearTimeout(timer);
  let video = contenido[diapositiva_actual][2];

  fetch(`${OrigenURL}/media/${video}`) // Verificar si el video existe
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
}

function myHandler() {
    reloj_descendente = duracion;
    diapositiva_actual += 1;
    VerDiapositiva();
}

// Función para ocultar boton
function hideButton() {
  document.getElementById("btn_switch").style.visibility = "hidden";
  document.getElementById("btn_switch").style.visibility = "hidden";
}

// Función para mostrar boton
function showButton() {
  document.getElementById("btn_switch").style.visibility = "visible";
}

// Función para manejar la inactividad
function handleInactivity() {
  clearTimeout(inactivityTimeout);
  inactivityTimeout = setTimeout(hideButton, inactivity_time);
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

function CargarDiapositiva() {
  document.querySelector("#contenedor").innerHTML = "";
  que_ver = contenido[diapositiva_actual][0];
  console.log('diapositiva_actual: ' + contenido[diapositiva_actual]);
  duracion = contenido[diapositiva_actual][1];
  reloj_descendente = duracion;

  if (que_ver == "0") {
    ImgLogo();
  } else if (que_ver == "1") {
    imagenes();
  } else if (que_ver == "2") {
    videos();
  } 
}

function temporizador() {
  clearTimeout(timer);
  if (reloj_descendente >= 1) {
    if (document.querySelector("#temporizador")) {
      document.querySelector("#temporizador").innerHTML = 
        '<div id="contiene_contador"><span>' + reloj_descendente + "</span></div>";
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
  fetch('/api/instantaneas', {method: 'POST', body : datos})
  .then( response => response.json() )
  .then( data => 
  {
      // store fetched data in the global variable
      contenido = data;
      total_diapositivas = contenido.length;
      function shuffleArray(inputArray) {
        inputArray.sort(() => Math.random() - 0.5);
      }
      shuffleArray(contenido);
      console.log('array desordenado: ' + contenido);
      document.querySelector("#contenido").innerHTML = "";
      let render = `<div id="btn_switch" class="arrows-maximize" onclick="statusFullScreen()"></div><div id="contenedor">contenedor</div>`;
      document.querySelector("#contenido").insertAdjacentHTML("beforeend", render);
      VerDiapositiva();
  })
  .catch( error => {console.error(error)})

  handleInactivity();
}