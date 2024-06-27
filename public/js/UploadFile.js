 

/*----------------Valida archivo a cargar con Indicador XMLHttpRequest ----------------*/
function FormAgregarSustento(id_multimedia)
{
    let datos = new FormData();
    datos.append("funcion","FormAgregarSustento");
    datos.append("id_multimedia",id_multimedia);
    fetch('/api/upload', {method: 'POST', body : datos})
    .then(res => res.text())
    .then(data => 
    {
        if(data == 'error')
        {
            msModal('alert','Ocurrio un error Inesperado');
        }
        else
        if(data != 'error')
        {
            msModal('min', 'Agregar Archivo');
            document.querySelector('#msModalHTML').innerHTML = data;
        }      
        DisabledById('btnEnviar');
    });     
}


/*----------------Barra de progreso del Upload con Indicador XMLHttpRequest ----------------*/
function BarraProgresoUpload(total, cargado, estatus)
{
    document.getElementById('salidamensaje').innerHTML = 
    `<div class="indicador-carga">
        <div class=\"barra cargando\">
            <span class=\"estado\">0%</span>
        </div>
    </div>`;

    var barra = document.querySelector('.barra');
    var estado = document.querySelector('.estado');
    let porcentaje = Math.round((cargado / total) * 100);
    barra.style.width = porcentaje+'%';
    estado.innerHTML=porcentaje + '%';
    
    if (porcentaje>99)
    {
        estado.innerHTML='Procesando...<span class="ico-loading"></span>';
    }
    
    if (estatus=='error_size')
    {
        barra.classList.add('error');
        estado.innerHTML = "El archivo es muy grande";
    }
    else
    if (estatus=='error_mime')
    {
        barra.classList.add('error');
        estado.innerHTML = "El tipo de archivo no es valido";
    }
    else
    if (estatus=='error')
    {
        barra.classList.add('error');
        estado.innerHTML = "Ocurrio un error Inesperado";
    }
    else
    if (estatus=='ok')
    {
        barra.classList.add('exito');
        estado.innerHTML = "Archivo Cargado Exitosamente";
        document.getElementById("archivo").value = '';
    }
}
/*----------------Barra de progreso del Upload con Indicador XMLHttpRequest ----------------*/


function ValidaArchivoSubir(max_filesize_MB)
{
    EnabledById('btnEnviar');
    
    let formulario = document.getElementById('form_subir');
    formulario.addEventListener("submit",function(event){event.preventDefault();});

    let archivo = document.getElementById("archivo");
    var files = archivo.files;

    console.log(files[0].name);

    document.getElementById("descripcion").value = eliminarExtension(files[0].name);
    


    if (!(document.getElementById('salidamensaje')))
    {
        archivo.insertAdjacentHTML("afterend", `<div id="salidamensaje"></div>`);
    }

    let salidamensaje = document.getElementById('salidamensaje');
    salidamensaje.innerHTML = '';

    let max_filesize = (max_filesize_MB * 1048576);

    if (files.length == 1)
    {
        //console.log(files[0].size);
        if ((files[0].size) > (max_filesize)) 
        {
            let nombrecantidad = formatSizeUnits(max_filesize);
            salidamensaje.innerHTML = `<span class="error_max_file">El archivo no puede ser mayor a ` + nombrecantidad + `</span>`;
            if (document.getElementById('btnEnviar'))
            {
                DisabledById('btnEnviar');
            }
        }
        else
        if ((files[0].size) <= (max_filesize))
        {
            if (!(document.getElementById('btnEnviar')))
            {
                if (e.target.classList.contains('agrega_sustento')){
                    //alert(e.target.attributes.id_multimedia.value);
                    ProcesaFormAgregarSustento(e.target.attributes.id_multimedia.value);
                }
            }
        }
    }
}

function ProcesaFormAgregarSustento(id_multimedia)
{
    DisabledById('btnCancelar');
    DisabledById('btnEnviar');
    BorraMsgFrm();

    let formulario = document.getElementById('form_subir');
    formulario.addEventListener("submit",function(event){event.preventDefault();});

    var files = document.getElementById('archivo').files;

    // Si hay un archivo seleccionado
    if (files.length == 1)
    {
        let peticion = new XMLHttpRequest();

        //Progreso
        peticion.upload.addEventListener("progress",function(e) 
        {
            BarraProgresoUpload(e.total, e.loaded, msg = '')
        });

        let datos = new FormData(formulario);
        datos.append("funcion","ProcesaFormAgregarSustento");
        datos.append("id_multimedia", id_multimedia);
        peticion.open('post', '/api/upload');
        peticion.responseType = 'text';
        peticion.send(datos);
        peticion.onreadystatechange = function()
        {
            if ((peticion.readyState == 4) && (peticion.status == 200))
            {
                //console.log(peticion.response);
                let resultado=JSON.parse(peticion.response );

                if (resultado[0] == 'descripcion')
                {
                    EnabledById('btnCancelar');
                    EnabledById('btnEnviar');
                    document.querySelector('#salidamensaje').innerHTML = "";
                    MsgFormError('form_subir','La descripci&oacute;n no es valida');
                    MsgError('descripcion','');
                    ReiniciarMsgFrm();
                }
                else
                if (resultado[0] !== 'ok')
                {
                    BarraProgresoUpload(100, 100, resultado[0]);
                    //console.log('array 1: ' + resultado[1]);
                    EnabledById('btnCancelar');
                }
                else
                if (resultado[0] == 'ok')
                {
                    msModal('ok', 'Archivo Cargado');
                    ListaMultimedia();
                }
            }
        };
    }
}