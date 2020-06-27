	<?php
    echo '<select name="state" id="state" class="select2 " style="width:100%"><option value="">Select</option>';
		foreach($get_state as $k=> $v)
		{
			echo '<option value="'.$v->state_id.'" >'.$v->state_name.'</option>';
		}
		echo '</select>';
        ?>

         <script>
    function select2Focus() 
    {
      var select2 = $(this).data('select2');
      setTimeout(function() {
          if (!select2.opened()) {
              select2.open();
          }
      }, 0);  
    }
    $(document).ready(function () {
   	

    $('.select2').select2({/*width: "100%"*/}).one('select2-focus', select2Focus).on("select2-blur", function () {
      $(this).one('select2-focus', select2Focus)
  })



 });
	

   
    </script>
    