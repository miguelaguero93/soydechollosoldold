$(function(){
	mobileMenu = document.getElementById("mobileNav");
	menuTrigger = document.getElementById("nav-icon1");
	if (menuTrigger != null) {

		menuTrigger.addEventListener('click',toggleMobileMenu);

	}
	
	triggetWaitBtn = document.getElementsByClassName('tw');
	for (var i = triggetWaitBtn.length - 1; i >= 0; i--) {
		triggetWaitBtn[i].addEventListener('click',showB);
	}
	
	$("#blanket").hide();
	$('[data-toggle="tooltip"]').tooltip()
})
function toggleMobileMenu(){
 menuTrigger.classList.toggle("open");	
 mobileMenu.classList.toggle("globalPopupActive");
}
function showB(){
  $("#blanket").show();
}
function hideB(){
  $("#blanket").hide();
}
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}