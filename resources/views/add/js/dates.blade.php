<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<script type="text/javascript">
	var lang ={
		    previousMonth : 'Anterior Mes',
		    nextMonth     : 'Siguiente Mes',
		    months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    weekdays      : ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sab']
	}
	var start_picker = new Pikaday({ 
		field: document.getElementById('datepickerstart'),
		format: 'D/M/YYYY',
		toString(date, format) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    	},
    	minDate: new Date(),
		i18n: lang,
		firstDay: 1,
		onSelect: function() {
			app.start = this.getMoment().format('Y-M-DD')
        	validateDates()
        } 
	})

	var end_picker = new Pikaday({ 
		field: document.getElementById('datepickerend'),
		format: 'D/M/YYYY',
		toString(date, format) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    	},
    	minDate: new Date(),
		i18n: lang,
		firstDay: 1,
		onSelect: function() {
			app.end = this.getMoment().format('Y-M-DD')
        	validateDates()
        } 
	})

	function validateDates(){
		if (app.start.length && app.end.length) {
			start_string = app.start+' '+app.start_time
			end_string = app.end+' '+app.end_time
			start = moment(start_string)
			end = moment(end_string)
			if (start >= end) {
				app.end = ''
				app.end_time = '23:00'
				end_picker.setDate(null)
				snackError('La fecha de finalización no puede ser antes del inicio.')
			}
		}
	}

</script>