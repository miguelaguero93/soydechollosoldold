function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase())
};
function errorAlert(message){
  swal({title: message, type: 'warning', showConfirmButton : true, width: 700})
}
function infoAlert(message){
  swal({title: message, type: 'info', showConfirmButton : true, width: 700})
}

function errorAlertInput(message,element){
  swal({title: message, type: 'warning', showConfirmButton : true, width: 700, onAfterClose: () => { focusElement(element) }})
}

function loadingAlert(message){
  swal({title: message, allowOutsideClick: false, allowEscapeKey: false, onBeforeOpen: () => {Swal.showLoading()}})
}

function successAlert(message){
  swal({type: 'success', html:message})
}
function focusElement(element){
	element.focus()
}
function successTimedAlert(message){
	Swal({position: 'center', type: 'success', title: message, showConfirmButton: false, timer: 1500 })
}

const toast = Swal.mixin({toast: true, position: 'center', showConfirmButton: false, timer: 2000 });

function toastSuccess(message){
    toast({type: 'success', text:message})
}
function toastError(message){
    toast({type: 'error', text:message})
}
function toastErrorTimed(message,time){
  swal({toast: true, position: 'center', showConfirmButton: true, timer: time, type: 'error', text:message })
}
function toastLog(message){
  swal({toast: true, position: 'top-left', showConfirmButton: false, timer: 2000, type: 'info', text:message })
}
function toastLoading(message){
  swal({toast: true, position: 'top-left', showConfirmButton: false, text:message,onBeforeOpen: () => {Swal.showLoading()} })
}
function toastLoadingCenter(message){
  swal({toast: true, position: 'center', showConfirmButton: false, text:message,onBeforeOpen: () => {Swal.showLoading()} })
}
function toastLoadingSuccess(message){
  swal({toast: true, position: 'top-left', showConfirmButton: false, timer: 1300, type: 'success', text:message })
}