<script type="text/javascript">
  
// $(document).ready(function(){

function scrollPreview() {
  
    left_offset = $('#left').offset().top
    sidebar = $('#sidebar')
    window_height = $(window).height()
    bottom_offset =  $('footer').height()
    
    $(window).scroll(function () {
      document_height = $(document).height()
      scroll = $(this).scrollTop()
      if (scroll < left_offset) {
        sidebar.css({
          'position': 'absolute',
          'bottom':'auto',
          'top':0
        });

      }else{
        left_to_scrol  = document_height - (window_height + scroll)  
        if (left_to_scrol > bottom_offset) {
          sidebar.css({
            'position': 'fixed'
          });
        }else{
          sidebar.css({
            'top': 'auto',
            'bottom': 0,
            'position': 'absolute'            
          });
        }  
      } 
    })

} 
// })

</script>