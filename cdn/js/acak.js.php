<?php 
	//Cek Apakah Sudah ada History Atau Belum
	$sql = "SELECT * FROM `hasil` WHERE `userid`='".$_SESSION['siswa']."' AND test='".$_SESSION['mapel']."' ";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        if ( $row['1'] == 0 ) {
//	        if ( true ) {

	        	$saveurutansoal = 1;

	        	?>
	        	//Kalao Tidak Ada History Kerjakan ACak dan Groouping

	        	//Acak
	        	<?php if ($shuffle>0) : ?>
	        		$('textarea.essay').each(function(){
						$(this).closest('div.soal').addClass('soalessay');	        		
	        		})
	        		$('div.soal').shuffle();

					$('.soalessay').each(function(){
						$(this).appendTo($('#soal-body'));
					})

	        	<?php endif; ?>


	        	//Grouping
	        	<?php
	        		$sql = "SELECT * FROM `group` WHERE `mapel`='".$_SESSION['mapel']."'";
	        		$result = $conn->query($sql);
	        		
	        		if ($result->num_rows > 0) {
	        		    // output data of each row
	        		    while($row = $result->fetch_assoc()) {
	        		    	for ($i = 1; $i <= $mapel_jumlahsoal; $i++) {
	        		    		if ($row[(string)$i]>0) {
	        		    			?>
	        		    				$('#nomor-asli-<?php echo $row[(string)$i] ?>').insertAfter($('#nomor-asli-<?php echo $i ?>'));
        		    					console.log('<?php echo $i ?>><?php echo $row[(string)$i] ?>')

	        		    			<?php
	        		    		}
	        		    	}
	        		    }
	        		} else {
	        		    //echo "0 results";
	        		}
	        	?>
	        	<?php
	        } else {
				//Kalao Ada History Ambil History
		       	$saveurutansoal = 0;
		       	?>
		       	<?php
		       	for ( $i=$mapel_dikerjakan ; $i>=1 ; $i-- ){
		       		?>
		    			$('#nomor-asli-<?php echo $row[(string)$i] ?>').prependTo('#soal-body');
		       		<?php
		       	}

	        }
	    }
	} else {
	    //echo "0 results";
	}


