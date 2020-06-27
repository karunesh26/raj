<?php
	echo '<option value="">Select</option>';
	foreach($get_state as $k=> $v)
	{
		echo '<option value="'.$v->state_id.'" >'.$v->state_name.'</option>';
	}
?>


    