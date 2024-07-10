/**
* CalendarioManager
* Representa el manejador de UI de la rendicion de viaticos
* @author     Elvio, Alan, Leandro
* @copyright  Jefatura de Asesores del Gobernador - JAG
* @license    NONE
* @version    Release
* @link       https://viaticos.jag.gob.ar
*/

/**
 * Convierte el string receptor  YYYY-M-D al formato
 * ISO8601 YYYY-MM-DD.
 * @access private
 * @returns {string}
 */
String.prototype.asDateISO8601 = function () {    
    let [anio, mes, dia] = this.split('-');
    mes = parseInt(mes);
    dia = parseInt(dia);
    mes = (mes < 10) ? '0' + mes: mes;
    dia = (dia < 10) ? '0' + dia: dia;
    if(anio && mes && dia) return anio + '-' + mes + '-' + dia;
    throw Error("Imposible convertir fecha a formato ISO8601");
};

/**
 * Convierte el date receptor al formato
 * ISO8601 YYYY-MM-DD.
 * @access private
 * @returns {string}
 */
Date.prototype.toStringISO8601 = function () {    
    let month = ((this.getMonth() + 1) < 10) ? "0" + (this.getMonth() + 1): this.getMonth()+1;
    let date = (this.getDate() < 10) ? "0" + this.getDate(): this.getDate();
    let year = this.getFullYear();
    return year + "-" + month + "-" + date;
};

/**
 * Convierte el date receptor al formato
 * Latino dd-mm-yyyy.
 * @access private
 * @returns {string}
 */
Date.prototype.toStringLATAM = function () {    
    
    let month = ((this.getMonth() + 1) < 10) ? "0" + (this.getMonth() + 1): this.getMonth()+1;
    let day = (this.getDate() < 10) ? "0" + this.getDate(): this.getDate();
    let year = this.getFullYear();
    return day + "/" + month + "/" + year;
};

/**
 * Parsea el date string en formato ATOM para convertirlo en Date
 * @see https://www.php.net/manual/en/class.datetimeinterface.php
 * @access private
 * @returns {string}
 */
Date.parseFromATOM = function(strDate) {
    let [fecha, hora] = strDate.split('T');
    let [anio, mes, dia] = fecha.split('-');
    return new Date(anio, mes-1, dia);
}

/**
 * Convierte el date receptor al formato
 * ISO8601 YYYY-MM-DD.
 * @access private
 * @returns {string}
 */
Date.prototype.toStringISO8601 = function () {    
    let month = ((this.getMonth() + 1) < 10) ? "0" + (this.getMonth() + 1): this.getMonth()+1;
    let date = (this.getDate() < 10) ? "0" + this.getDate(): this.getDate();
    let year = this.getFullYear();
    return year + "-" + month + "-" + date;
};


class CalendarioManager {

    static BGC_FERIADO = "hsla(112, 0%, 66%, 1)";
    static C_FERIADO = "#00000";
    static BGC_VIATICOM = "hsla(112, 0%, 86%, 1)"; // background color viatico mes
    static BC_VIATICOM = "hsla(112, 0%, 66%, 1)";  // border color viatico mes
    static BGC_VIATICOP = "hsla(112, 23%, 66%, 1)";
    static BC_VIATICOP = "hsla(112, 23%, 36%, 1)";
    
    static C_VIATICO = "#009aae";

    constructor(mes=1, anio=2024) {

        this.mes = mes;
        this.anio = anio;
        this.fullCalendar = null;
        this.parentContainer = null;
        this.feriados = [];
        this.viaticosMes = [];
        this.rendiciones = [];
        this.viaticoSolicitado = null;
        this.submitBlock = null;

        this.decorarFeriadoBlock = null;
        this.mouseOverFeriadoBlock = null;
        this.mouseOutFeriadoBlock = null;

        this.decorarRendicionBlock = null;
        this.mouseOverRendicionBlock = null;
        this.mouseOutRendicionBlock = null;
        
        this.decorarViaticoBlock = null;
        this.mouseOverViaticoBlock = null;
        this.mouseOutViaticoBlock = null;
        this.inicializar();

    }

    

    inicializar() {
        let self = this;
        let porcentaje, modulo, tipoRendicion, destino = null;
        

        // accion que se realiza al abrir el modal
        $('#rendicionModal').on('shown.bs.modal', () => {

            porcentaje = document.getElementById('porcentaje');
            modulo = document.getElementById('modulo');
            tipoRendicion = document.getElementById('tipoRendicion');
            destino = document.getElementById('destino');

            let cambioPorcentaje = (event) => {
                self.updateAceptarRendicionButton(
                    porcentaje.value, 
                    modulo.value, 
                    tipoRendicion.value, 
                    destino.value);                
            }
            porcentaje.removeEventListener("change", cambioPorcentaje);
            porcentaje.value = "0";
            porcentaje.addEventListener("change", cambioPorcentaje);

            let cambioModulo = (event) => {
                self.updateAceptarRendicionButton(
                    porcentaje.value, 
                    modulo.value, 
                    tipoRendicion.value, 
                    destino.value);
            }
            modulo.removeEventListener("change", cambioModulo);
            modulo.value = "0";
            modulo.addEventListener("change", cambioModulo);
            
            let cambioTipoRendicion = (event) => {
                self.updateAceptarRendicionButton(
                    porcentaje.value, 
                    modulo.value, 
                    tipoRendicion.value, 
                    destino.value);
            }
            tipoRendicion.removeEventListener("change", cambioModulo);
            tipoRendicion.value = "";
            tipoRendicion.addEventListener("change", cambioModulo);

            
            let cambioDestino = (event) => {
                self.updateAceptarRendicionButton(
                    porcentaje.value, 
                    modulo.value, 
                    tipoRendicion.value, 
                    destino.value);
            }
            destino.removeEventListener("change", cambioDestino);
            destino.value = "";
            destino.addEventListener("change", cambioDestino);            

            $('#porcentaje').trigger('focus');
        });

        // accion al aceptar la rendicion del modal
        $('.btn-aceptar').on('click', function() {        
            let porcentaje = parseFloat(document.getElementById('porcentaje').value);
            let modulo = parseFloat(document.getElementById('modulo').value);
            let tipoRendicion = document.getElementById('tipoRendicion').value;
            let destino = document.getElementById('destino').value;
            let fecha = document.getElementById('fechaRendicion').value;
            let valorViatico = (tipoRendicion == "viatico") ? (modulo * (porcentaje/100)): 0;
            let valorMovilidad = (tipoRendicion == "movilidad") ? (modulo * (porcentaje/100)): 0;
            self.agregarRendicion(self.viaticoSolicitado.id, fecha, porcentaje, destino, modulo, tipoRendicion);
            self.refrescarFullCalendar();
            /*self.focusSiguienteDiaUsable(fecha);  */    
            $('#rendicionModal').modal('hide');
            document.querySelector(".btn-aceptar").disabled = true;        
        });

        // accion al guardar la rendicion
        document.querySelector(".btn-guardar-rendicion").addEventListener("click", (event) =>{
            self.guardarRendicion(event);
        });
    }



    /**
     * Algo cambio en el modal de rendicion.
     * Se actualiza el button Aceptar dependiendo del estado
     * del modal.
     * @access private
     * @param {string} porcentaje 
     * @param {string} modulo 
     * @param {string} tipoRendicion 
     * @param {string} destino 
     */
    updateAceptarRendicionButton(porcentaje, modulo, tipoRendicion, destino) {

        let puedeAceptar = 
            (porcentaje != "0") &&
            (modulo != "0") &&
            (tipoRendicion != "") &&
            (destino != "");
        if(puedeAceptar)
            document.querySelector(".btn-aceptar").disabled = false;
        else document.querySelector(".btn-aceptar").disabled = true;
        
    }

    /**
     * Setea el focus al boton "rendir" con fecha siguiente 
     * a la pasada por parametro
     * @access private
     * @param {strDate} fecha 
     */
    focusSiguienteDiaUsable(fecha) {        
        let date = new Date(fecha+"T00:00:00");
        date.setDate(date.getDate()+1);
        let siguiente = date.toStringISO8601();        
        document.querySelector(`[data-usabledate="${siguiente}"]`).focus({focusVisible: true });
    }

    /**
     * Fuerza el refresco del fullCalendar
     * @access private
     */
    refrescarFullCalendar() {

        let fullCalendar = this.crearFullCalendar(this.parentContainer);
        fullCalendar.render();
        this.postRender();
    }

    /**
     * Acciones a realizar posterior al renderizado
     * @access private
     */
    postRender() {
        let importe = this.importeTotalRendicion();        
        document.getElementById("importe").innerHTML = importe;
        document.getElementById("importeSolicitado").innerHTML = this.viaticoSolicitado.importe;
        document.getElementById("moduloSolicitado").innerHTML = this.viaticoSolicitado.modulo;
        document.getElementById("dias150").innerHTML = this.viaticoSolicitado.dias150;
        document.getElementById("dias100").innerHTML = this.viaticoSolicitado.dias100;
        document.getElementById("dias70").innerHTML = this.viaticoSolicitado.dias70;
        document.getElementById("dias50").innerHTML = this.viaticoSolicitado.dias50;
        document.getElementById("dias40").innerHTML = this.viaticoSolicitado.dias40;
        document.getElementById("dias20").innerHTML = this.viaticoSolicitado.dias20;
        document.getElementById("diasMov").innerHTML = this.viaticoSolicitado.diasmov;
        if(importe == this.viaticoSolicitado.importe) {
            document.querySelector(".btn-guardar-rendicion").disabled = false;
            document.querySelector(".btn-guardar-rendicion").classList.remove("btn-outline-success");
            document.querySelector(".btn-guardar-rendicion").classList.add("btn-success");
            document.getElementById("importe").parentElement.style.backgroundColor = "hsla(112, 23%, 86%, 1)";
            document.getElementById("importe").style.fontWeight = "bolder";
            document.querySelector(".btn-guardar-rendicion").parentElement.style.backgroundColor = "hsla(112, 23%, 86%, 1)";
        } else {
            document.querySelector(".btn-guardar-rendicion").disabled = true;
            document.querySelector(".btn-guardar-rendicion").classList.remove("btn-success");
            document.querySelector(".btn-guardar-rendicion").classList.add("btn-outline-success");
            document.getElementById("importe").style.fontWeight = "normal";
            document.getElementById("importe").parentElement.style.backgroundColor = "white";
            document.querySelector(".btn-guardar-rendicion").parentElement.style.backgroundColor = "white";
        }
    }

    /**
     * Setea la accion que se hará al guardar la rendicion
     * @access public
     * @param {CallableFunction} callback 
     */
    onGuardarRendicion(callback){
        this.submitBlock = callback;
    }



    /**
     * Setea la accion que se hará al mostrar un dia con rendicion
     * (viatico que actualmente se esta rindiendo)
     * @access public
     * @param {CallableFunction} callback 
     */
    onDecorarRendicion(callback){
        this.decorarRendicionBlock = callback;
    }

    /**
     * Setea la accion que se hará al mouseover de un dia rendicion
     * @access public
     * @param {CallableFunction} callback 
     */
    onMouseOverRendicion(callback){
        this.mouseOverRendicionBlock = callback;
    }

    /**
     * Setea la accion que se hará al mouseout de un dia rendicion
     * @access public
     * @param {CallableFunction} callback 
     */
    onMouseOutRendicion(callback){
        this.mouseOutRendicionBlock = callback;
    }

    /**
     * Setea la accion que se hará al mostrar un dia con un viatico
     * @access public
     * @param {CallableFunction} callback 
     */
    onDecorarViatico(callback){
        this.decorarViaticoBlock = callback;
    }

    /**
     * Setea la accion que se hará al mouseover de un dia con un viatico
     * @access public
     * @param {CallableFunction} callback 
     */
    onMouseOverViatico(callback){
        this.mouseOverViaticoBlock = callback;
    }

    /**
     * Setea la accion que se hará al mouseout de un dia con un viatico
     * @access public
     * @param {CallableFunction} callback 
     */
    onMouseOutViatico(callback){
        this.mouseOutViaticoBlock = callback;
    }

    /**
     * Setea la accion que se hará al mostrar un dia con un feriado
     * @access public
     * @param {CallableFunction} callback 
     */
    onDecorarFeriado(callback){
        this.decorarFeriadoBlock = callback;
    }

    /**
     * Setea la accion que se hará al mouseover de un dia con un feriado
     * @access public
     * @param {CallableFunction} callback 
     */
    onMouseOverFeriado(callback){
        this.mouseOverFeriadoBlock = callback;
    }

    /**
     * Setea la accion que se hará al mouseout de un dia con un feriado
     * @access public
     * @param {CallableFunction} callback 
     */
    onMouseOutFeriado(callback){
        this.mouseOutFeriadoBlock = callback;
    }


    

    /**
    * Crea el contenedor del fullCalendar
    * @access private
    * @param {string} id 
    * @param {HTMLElement} parentElement
    * @returns {HTMLElement}
    */
    crearContenedor(id, parentElement) {
        this.parentContainer = parentElement;
        let contenedor = document.getElementById(id);
        if(contenedor) {
            contenedor.parentElement.removeChild(contenedor);
        }
        contenedor = document.createElement("DIV");
        contenedor.id = id;
        parentElement.appendChild(contenedor);
        return contenedor;
    }

    /**
     * Crea el componente fullcalendar
     * @param {HTMLElement} parentElement 
     * @returns {FullCalendar.Calendar}
     */
    crearFullCalendar(parentElement) {
        let self = this;
        let calendarEl = this.crearContenedor("calendar", parentElement);        
        self.fullCalendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ["interaction", "dayGrid", "timeGrid" /*, "resourceTimeline"*/],        
            locale: 'es',
            defaultDate: (this.anio + '-' + this.mes + '-01').asDateISO8601(),
            navLinks: false, 
            businessHours: false, 
            editable: true,
            dayRender: (info) => {    
    
                self.decorarDia(info);
            }
        });
        return self.fullCalendar;
    }

    /**
     * Retorna un feriado si date
     * es un feriado en este calendario.
     * Null en caso contrario
     * @param {Date} date 
     * @returns {object}
     */
    esFeriado(date) {
        let dia = null;
        let result = null;
        if(this.feriados.length == 0) return false;
        this.feriados.forEach( elem => {
            var diaStr = elem.anio+"-";
            if (elem.mes < 10){
                    diaStr+="0"+elem.mes;
            }else{
                diaStr+=elem.mes;
            }
            diaStr+="-"+elem.dia+" 00:00:00";
            dia = new Date(diaStr);
            if(dia.getDate() == date.getDate() &&
                dia.getMonth() == date.getMonth() &&
                dia.getFullYear() == date.getFullYear() ) result = elem;
        });
        return result;
    }

    /**
     * Retorna un viatico mes si date
     * es un viatico en este calendario.
     * Null en caso contrario
     * @param {Date} date 
     * @returns {object}
     */
    esViaticoMes(date) {
        let dia = null;
        let result = null;
        if(this.viaticosMes.length == 0) return false;
        var diaStr = this.viaticosMes.forEach( elem => {
                elem.rendiciones.forEach( rend =>{
                    var fechaRend= rend.fecha.split('+');
                    var rendFecha= new Date(fechaRend[0]);
                    
                    /*console.log("rendFecha>>"+rendFecha.toStringISO8601(), "date>>"+date.toStringISO8601());*/
                    if(rendFecha.toStringISO8601() == date.toStringISO8601()){
                        result = rend;
                    }
                });
              
        });
        return result;
    }

    /**
     * Retorna un viatico si date
     * es un viatico pedido en este calendario.
     * Null en caso contrario
     * @param {Date} date 
     * @returns {object}
     */
    esRendicion(date) {
        let dia = null;
        let result = null;
        if(this.rendiciones.length == 0) return false;
        this.rendiciones.forEach( elem => {     
            dia = new Date(elem.fecha+"T00:00:00");
            if(dia.toStringISO8601() == date.toStringISO8601()){
                result = elem;
            }
        });
        return result;
    }

    /**     
     * Retorna true si date corresponde al mes y año
     * del receptor
     * @param {Date} date 
     * @returns {boolean}
     */
    esEsteMes(date) {        
        return (parseInt(this.anio) == date.getFullYear() && parseInt(this.mes-1) == date.getMonth());
    }

    /**
     * Decora el dia segun a qué conjunto (feriado, viatico, rendicion)
     * pertenece el dia actual que se esta decorando.
     * @param {object} info 
     */
    decorarDia(info) {
        let self = this;
        let date = info.date;
        let feriado, viat, rend = null;
        if(feriado = this.esFeriado(date)) {
            this.decorarFeriado(info, feriado);            
        } else if(viat = this.esViaticoMes(date)) {
            this.decorarViatico(info, viat);
        } else if(rend = this.esRendicion(date)) {
            this.decorarRendicion(info, rend);
        } else if(this.esEsteMes(date)) {
            this.decorarDiaRendible(info);                
        }
    }

    /**
     * Decora el info (fullCalendar info)
     * como feriado. Invoca el callback, si lo hubiese
     * @access private
     * @param {object} info 
     * @param {object} feriado 
     */
    decorarFeriado(info, feriado) {
        info.el.addEventListener("mouseover", (event) =>{
            if(this.mouseOverFeriadoBlock)
                this.mouseOverFeriadoBlock(info, feriado);
        });
        info.el.addEventListener("mouseout", (event) =>{
            if(this.mouseOutFeriadoBlock)
                this.mouseOutFeriadoBlock(info, feriado);
        });

        if(this.decorarFeriadoBlock)
            this.decorarFeriadoBlock(info, feriado);
    }

    /**
     * Decora el info (fullCalendar info)
     * como viatico. Este viatico corresponde al mismo agente pero seria otra rendicion.
     * @access private
     * @param {object} info 
     * @param {object} viat 
     */
    decorarViatico(info, viat) {
        info.el.addEventListener("mouseover", (event) =>{
            if(this.mouseOverViaticoBlock)
                this.mouseOverViaticoBlock(info, viat);
        });
        info.el.addEventListener("mouseout", (event) =>{
            if(this.mouseOutViaticoBlock)
                this.mouseOutViaticoBlock(info, viat);
        });
        if(this.decorarViaticoBlock) 
            this.decorarViaticoBlock(info, viat);
    }

    /**
     * Decora el info (fullCalendar info)
     * como rendicion
     * @access private
     * @param {object} info 
     * @param {object} viat 
     */
    decorarRendicion(info, rend) {

        info.el.addEventListener("mouseover", (event) =>{
            if(this.mouseOverRendicionBlock)
                this.mouseOverRendicionBlock(info, rend);
        });
        info.el.addEventListener("mouseout", (event) =>{
            if(this.mouseOutRendicionBlock)
                this.mouseOutRendicionBlock(info, rend);
        });
        if(this.decorarRendicionBlock) 
            this.decorarRendicionBlock(info, rend);

        let self = this;        
        let strDate = info.date.toStringISO8601();
        let btnRendicion = document.createElement("button");
        btnRendicion.classList.add('dayButton');
        btnRendicion.classList.add('btn');
        btnRendicion.classList.add('btn-inverse');
        btnRendicion.classList.add('btn-outline-inverse');
        btnRendicion.setAttribute("data-rendiciondate", strDate);
        info.el.style.position="relative";
        btnRendicion.style.position ="absolute";
        btnRendicion.style.bottom="0.3em";
        btnRendicion.style.left="0.3em";
        btnRendicion.style.height="1.5em";
        btnRendicion.style.width="7em";
        btnRendicion.innerHTML = "<i class='fa fa-trash'></i></i>&nbsp;Remover";
        info.el.appendChild(btnRendicion);
        /**
         * agrega el boton al elemento html del dia y le hookea 
         * el evento click redireccionandolo al mismo receptor
         */
        info.el.appendChild(btnRendicion);
        btnRendicion.addEventListener("click", (event) => {                    
            self.removerRendicion(strDate);
            self.refrescarFullCalendar();  
        });
    }




    /**
     * Decora el info (fullCalendar info)
     * como dia rendible
     * @access private
     * @param {object} info 
     */
    decorarDiaRendible(info) {
        let self = this;
        let strDate = info.date.toStringISO8601();       

        let btnRendicion = document.createElement("button");
        btnRendicion.classList.add('dayButton');
        btnRendicion.classList.add('btn');
        btnRendicion.classList.add('btn-inverse');
        btnRendicion.classList.add('btn-outline-inverse');
        btnRendicion.setAttribute("data-usabledate", strDate);
        info.el.style.position="relative";
        btnRendicion.style.position ="absolute";
        btnRendicion.style.bottom="0.3em";
        btnRendicion.style.left="0.3em";
        btnRendicion.style.height="1.2em";
        btnRendicion.style.width="7em";
        btnRendicion.style.marginBottom="5px";
        btnRendicion.innerHTML = "<i class='fa fa fa-edit'></i></i>&nbsp;Rendir";
        info.el.appendChild(btnRendicion);
        

        /**
         * agrega el boton al elemento html del dia y le hookea 
         * el evento click redireccionandolo al mismo receptor
         */

        btnRendicion.addEventListener("click", (event) => {                    
            self.abrirModalRendicion(event);
        });
        btnRendicion.style.position ="abolute";
        btnRendicion.style.bottom = "0";
        //info.el.innerHTML += "<button class='dayButton btn-mini btn-outline' data-date='" + strDate + "'><i class='fa-regular fa-calendar-plus'></i>&nbsp;Rendir</button>";                
        info.el.style.padding = "20px 0 0 5px";
        info.el.style.valign="bottom";
    }

    /**
     * Abre la ventana modal para definir la 
     * rendicion de una fecha.
     * @param {Event} event 
     */
    abrirModalRendicion(event) {        
        let fecha = event.srcElement.getAttribute("data-usabledate");
        document.getElementById("fechaRendicion").value = fecha;
        $('#rendicionModal').modal('show');
    }

    /**
     * Agrega una rendicion al receptor
     * 
     * @param {string} viatico_id 
     * @param {string} fecha 
     * @param {float} porcentaje 
     * @param {string} destino 
     * @param {float} modulo 
     * @param {string} tipo 
     * @param {float} valorViatico 
     * @param {float} valorMovilidad 
     */
    agregarRendicion(viatico_id, fecha, porcentaje, destino, modulo, tipo, valorViatico=0, valorMovilidad=0) {
        let rendicion = {
            id: viatico_id, 
            fecha: fecha, 
            porcentaje: porcentaje, 
            destino: destino, 
            modulo: modulo, 
            tipo: tipo
        }
        this.rendiciones.push(rendicion);
    }

    /**
     * Elimina la rendicion del receptor
     * @access private
     * @param {string} fecha 
     */
    removerRendicion(fecha) {
        this.rendiciones = this.rendiciones.filter((r) => r.fecha != fecha);
    }

    /**
     * Se guardo la rendicion. Se invoca al callback si lo hubiese.
     * @access private
     * @param {*} event 
     */
    guardarRendicion(event) {
        if(this.submitBlock)
            this.submitBlock(event,this.viaticoSolicitado,this.rendiciones);
    }

    /**
     * Retorna el valor total de la rendicion
     * @returns {float}
     */
    importeTotalRendicion() {
        let total = 0;
        this.rendiciones.forEach( r => {
            total += (parseFloat(r.porcentaje) * parseFloat(r.modulo));
        });
        return total;
    }
    setFeriados(feriados){
        this.feriados = feriados;
    }
    setViaticos(viaticos){
        this.viaticosMes= viaticos;
    }

    setSolicitud(solicitud){
        this.viaticoSolicitado = solicitud;
    }


    
}