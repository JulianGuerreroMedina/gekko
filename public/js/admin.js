document.addEventListener("DOMContentLoaded", function () {
    iniciarAdmin();
    ListaMultimedia();
});




function ProcesaFrm_borrar_multimedia(id_multimedia)
{
    let datos = new FormData();
    datos.append("funcion","ProcesaFrm_borrar_multimedia");
    datos.append("id_multimedia", id_multimedia);
    fetch('/api/Admin', {method: 'POST', body : datos})
    .then(res => res.text())
    .then(data => 
    {
        if(data != 'ok')
        {
            msModal('alert','Ocurrio un error Inesperado');
        }
        else
        if(data == 'ok')
        {
            msModal('ok', 'Recurso Borrado');
            ListaMultimedia();
        }      
    });   
}

function Frm_borrar_multimedia(id_multimedia)
{
    let datos = new FormData();
    datos.append("funcion","Frm_borrar_multimedia");
    datos.append("id_multimedia", id_multimedia);
    fetch('/api/Admin', {method: 'POST', body : datos})
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
            msModal('sino', 'Borrar');
            document.querySelector('#msModalHTML').innerHTML = data;
        }      
    });    
}

function frmSetear(id_multimedia)
{
    let datos = new FormData();
    datos.append("funcion","frmSetear");
    datos.append("id_multimedia", id_multimedia);
    fetch('/api/Admin', {method: 'POST', body : datos})
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

function ProcesaFrm_abm_multimedia()
{
    DisabledById('btnCancelar');
    DisabledById('btnEnviar');
    BorraMsgFrm();
    let form =  document.querySelector('#frm_abm_multimedia');
    let datos = new FormData(form);
    datos.append("funcion","ProcesaFrm_abm_multimedia");
    fetch('/api/Admin', {method: 'POST', body : datos})
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
            MsgFormError('frm_abm_multimedia','Existen campos no validos');
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

function Frm_abm_multimedia(id_multimedia)
{
    let titulo = 'Agregar';
    if (id_multimedia >= 1){
        titulo = 'Modificar';
    }
    
    let datos = new FormData();
    datos.append("funcion","Frm_abm_multimedia");
    datos.append("id_multimedia", id_multimedia);
    fetch('/api/Admin', {method: 'POST', body : datos})
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
    fetch("/api/Admin", { method: "POST", body: datos })
      .then((res) => res.text())
      .then((data) => {
        if (data === "error") {
          console.log(('Ocurrio un error cargando la grafica'))
        } else if (data !== "error") {
          document.querySelector("#contenido").innerHTML = '';
          document.querySelector("#contenido").insertAdjacentHTML("beforeend", data);
        }
    });
  }

function limpia_horario()
{
    document.querySelector("#h_inicio").value = '';
    document.querySelector("#h_final").value = '';
}

function iniciarAdmin()
{
    const contenido = document.getElementById("contenido");
    contenido.addEventListener('click', (e) => {

        //Boton Agregar multimedia
        if (e.target.id === 'btn_add_multimedia'){
            //console.log(e.target.attributes.id.value);
            Frm_abm_multimedia('0');
        }
        
        //Botones para subir archivo multimedia
        if (e.target.classList.contains('subir_multimedia')){
            FormAgregarSustento(e.target.attributes.id_multimedia.value);
        }

        //Botones para setear mostrar o no
        if (e.target.classList.contains('btn_set')){
            frmSetear(e.target.attributes.id_multimedia.value);
        }

        //Botones para Borrar multimedia
        if (e.target.classList.contains('btn_borrar')){
            Frm_borrar_multimedia(e.target.attributes.id_multimedia.value);
        }

        //Botones para editar multimedia
        if (e.target.classList.contains('btn_editar')){
            Frm_abm_multimedia(e.target.attributes.id_multimedia.value);
        }
    });
}
