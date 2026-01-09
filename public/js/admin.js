document.addEventListener("DOMContentLoaded", function () { iniciarAdmin(); });


function ProcesaFrmBorrarMultimedia(id_multimedia) {
  let datos = new FormData();
  datos.append("funcion", "ProcesaFrmBorrarMultimedia");
  datos.append("id_multimedia", id_multimedia);
  fetch("/api/admin", { method: "POST", body: datos })
    .then((response) => response.json())
    .then((contenido) => {
      if (contenido["respuesta"] == "ok") {
        msModal("ok", "Recurso Borrado");
        BorrarIdFadeOut('tr_' + id_multimedia);
      } else {
        msModal("alert", "Ocurrio un error Inesperado");
      }
    })
.catch(error => console.error(error));
}


function FrmBorrarMultimedia(id_multimedia)
{
  msModal("sino", "Borrar Multimedia");
  let descripcion = document.getElementById("id_descripcion_" + id_multimedia).innerText;
  let render = `<form accept-charset="UTF-8">
        <h3>Desea elimiar el recurso Multimedia: ${descripcion}?</h3>
        <div class="botonera">
            <input type="reset" id="btnCancelar" name="btnCancelar" class="boton" value="Cancelar" onclick="msCerrar()"/>
            <input name="btnBuscar" id="ProcesaFrmBorrarMultimedia" id_multimedia="${id_multimedia}"  type="submit" class="boton" value="Borrar">
        </div>
    </form>`;
  document.querySelector("#msModalHTML").innerHTML = render;
}

function frmSetear(id_multimedia)
{
    let datos = new FormData();
    datos.append("funcion","frmSetear");
    datos.append("id_multimedia", id_multimedia);
    fetch('/api/admin', {method: 'POST', body : datos})
    .then(res => res.text())
    .then(data => 
    {
        if(data == '0')
        {
            msModal('ok','Se desactiv&oacute; la publicaci&oacute;n');
            ListaMultimedia();
        }      
        else
        if(data == '1')
        {
            msModal('ok','Se activ&oacute; la publicaci&oacute;n');
            ListaMultimedia();
        }    

        if((data != '0') && (data != '1'))
        {
            msModal('alert','Ocurrio un error Inesperado');
        }
    });     
}

function ProcesaFrmAbmMultimedia()
{
    DisabledById('btnCancelar');
    DisabledById('btnEnviar');
    BorraMsgFrm();
    let form =  document.querySelector('#FrmAbmMultimedia');
    let datos = new FormData(form);
    datos.append("funcion","ProcesaFrmAbmMultimedia");
    fetch('/api/admin', {method: 'POST', body : datos})
    .then( response => response.text() )
    .then( data => manejardatos(data) );
    const manejaError = (error) => 
    {
        //console.log(error);
        msModal('alert','Ocurrio un error Inesperado');
    };
    
    const manejardatos = (data) => 
    {
        let contenido=JSON.parse(data);
        //console.log(contenido[0]);
        //console.log(contenido[1]);
        if (contenido[0] == 'error')
        {
            MsgFormError('FrmAbmMultimedia','Existen campos no validos');
            for (i = 1; i < contenido.length; i++) 
            {
                MsgError(contenido[i],'');
            } 
            ReiniciarMsgFrm();
        }
        if (contenido[0]=='agregado')
        {
            msModal('ok','Registro Creado');
            ListaMultimedia();
        }
        if (contenido[0]=='modificado')
        {
            msModal('ok','Registro Modificado');
            ListaMultimedia();
        }
    };
    EnabledById('btnCancelar');
    EnabledById('btnEnviar');
}

function FrmAbmMultimedia(id_multimedia)
{
    let titulo = 'Agregar';
    if (id_multimedia >= 1){
        titulo = 'Modificar';
    }
    
    let datos = new FormData();
    datos.append("funcion","FrmAbmMultimedia");
    datos.append("id_multimedia", id_multimedia);
    fetch('/api/admin', {method: 'POST', body : datos})
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
            msModal('med', titulo);
            document.querySelector('#msModalHTML').innerHTML = data;
        }      
    });     
}

function ListaMultimedia()
{
    let datos = new FormData();
    datos.append("funcion", "ListaMultimedia");
    datos.append("otro_dto", "25663314");
    fetch("/api/admin", { method: "POST", body: datos })
    .then( response => response.json() )
    .then( contenido => 
    {
        if (contenido['respuesta'] == 'ok')
        {
            document.querySelector("#multimedia").tbody  = '';
          document.querySelector("#multimedia").innerHTML  = contenido['html'];
          //document.querySelector("#contenido").insertAdjacentHTML("beforeend", data);
        } else
        if (contenido['respuesta'] == 'sin_datos')
        {
            document.querySelector("#multimedia").tbody  = '';
            document.querySelector("#multimedia").innerHTML  = '<tr><td colspan="6">No hay recursos multimedia cargados</td></tr>';
        } else
        {
            msModal('alert','Ocurrio un error Inesperado');
        }
    })
    .catch( error => 
    {
        msModal('alert','Ocurrio un error Inesperado');
    }
    );
}

function limpia_horario()
{
    document.querySelector("#h_inicio").value = '';
    document.querySelector("#h_final").value = '';
}

function iniciarAdmin()
{
    const contenido = document.body;
    contenido.addEventListener('click', (e) => {

        //Boton Agregar multimedia
        if (e.target.id === 'btn_add_multimedia'){
            //console.log(e.target.attributes.id.value);
            FrmAbmMultimedia('0');
        } else
        //Boton procesar formulario borrar multimedia
        if (e.target.id === 'ProcesaFrmBorrarMultimedia'){
            e.preventDefault();            
            ProcesaFrmBorrarMultimedia(e.target.attributes.id_multimedia.value);
        } else
        //Botones para subir archivo multimedia
        if (e.target.classList.contains('subir_multimedia')){
            FormAgregarSustento(e.target.attributes.id_multimedia.value);
        } else
        //Botones para setear mostrar o no
        if (e.target.classList.contains('btn_set')){
            frmSetear(e.target.attributes.id_multimedia.value);
        } else
        if (e.target.classList.contains('btn_borrar')){
            FrmBorrarMultimedia(e.target.attributes.id_multimedia.value);
        } else
        if (e.target.classList.contains('btn_editar')){
            FrmAbmMultimedia(e.target.attributes.id_multimedia.value);
        }
    });

    ListaMultimedia();
}
