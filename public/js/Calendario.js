class Calendario {

    static BGC_FERIADO = "#b3b3b3";
    static C_FERIADO = "#00000";
    static BGC_VIATICOM = "#cce5ff";
    static BC_VIATICOM = "#99caff";
    static C_VIATICO = "#000000";

    constructor() {
        this.feriados = [];
        this.viaticosMes = [];
        this.viaticosPedidos = [];
    }

    /**
     * Retorna los feriados
     * 
     * @param {integer} mes 
     * @param {integet} anio 
     * @returns {array}
     */
    getFeriados(mes = 4, anio = 2024) {
        this.feriados = [
            {
                fecha: '2024-04-01',
                descrip: 'Ferriado puente'
            },
            {
                fecha: '2024-04-02',
                descrip: 'Día del Veterano y de los Caídos en la Guerra de Malvinas'
            }
        ];
        return this.feriados;
    }

    /**
     * Obtiene los viaticos para un agente 
     * en un mes determinado.
     * @param {integer} cuil 
     * @param {integer} mes 
     * @param {integer} anio 
     * @returns 
     */
    getViaticos(cuil = 20240404614, mes = 4, anio = 2024) {
        this.viaticosMes = [
           
            
            {
                fecha: '2024-04-09',
                tipo: 'VIATICO',
                porcentaje: 100,
                destino: 'Ranchos',
                id: 23334
            },
            {
                fecha: '2024-04-10',
                tipo: 'VIATICO',
                porcentaje: 100,
                destino: 'Ranchos',
                id: 23334
            },
            {
                fecha: '2024-04-11',
                tipo: 'VIATICO',
                porcentaje: 100,
                destino: 'Ranchos',
                id: 23311
            },
            {
                fecha: '2024-04-12',
                tipo: 'VIATICO',
                porcentaje: 100,
                destino: 'Ranchos',
                id: 23311
            },
            {
                fecha: '2024-04-22',
                tipo: 'VIATICO',
                porcentaje: 100,
                destino: 'Ranchos',
                id: 23314
            },
            {
                fecha: '2024-04-23',
                tipo: 'VIATICO',
                porcentaje: 100,
                destino: 'Ranchos',
                id: 23314
            },
        ];
        return this.viaticosMes;
    }

    /**
     * Retorna un feriado si date
     * es un feriado en este cale8ndario.
     * Null en caso contrario
     * @param {Date} date 
     * @returns {object}
     */
    esFeriado(date) {
        let dia = null;
        let result = null;
        if(this.feriados.length == 0) return false;
        this.feriados.forEach( elem => {
            dia = new Date(elem.fecha+"T00:00:00");
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
        this.viaticosMes.forEach( elem => {
            dia = new Date(elem.fecha+"T00:00:00");
            if(dia.getDate() == date.getDate() &&
                dia.getMonth() == date.getMonth() &&
                dia.getFullYear() == date.getFullYear() ) result = elem;
        });
        return result;
    }


    decorarDia(info) {
        let date = info.date;
        let feriado, viat = null;
        if(feriado = this.esFeriado(date)) {
            info.el.style.backgroundColor = Calendario.BGC_FERIADO;
            info.el.style.color = Calendario.C_FERIADO;            
            info.el.style.fontSize = "smaller";
            info.el.innerHTML = feriado.descrip;
            info.el.style.padding = "20px 0 0 5px";
        } else if(viat = this.esViaticoMes(date)) {
            info.el.style.backgroundColor = Calendario.BGC_VIATICOM;
            info.el.style.color = Calendario.C_VIATICOM;
            info.el.style.borderColor = Calendario.BC_VIATICOM;
            info.el.style.fontSize = "smaller";
            info.el.innerHTML = '[' + viat.id + '] ' + viat.tipo + ' ' + viat.porcentaje + '% ' + viat.destino;
            info.el.style.padding = "20px 0 0 5px";
        } else {
            let strDate = info.date.getFullYear() + "-" + info.date.getMonth() + "-" + info.date.getDate();
            info.el.innerHTML += "<button class='dayButton' data-date='" + strDate + "'>Click me</button>";
            info.el.style.padding = "20px 0 0 5px";
        }
        
    }

}