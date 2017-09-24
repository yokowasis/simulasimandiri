<?php require_once (dirname(__FILE__).'/../bimadb.php'); ?>
<script>
var themedir = 'wp-content/themes/unbk';

(function($){

    $.fn.shuffle = function() {
 
        var allElems = this.get(),
            getRandom = function(max) {
                return Math.floor(Math.random() * max);
            },
            shuffled = $.map(allElems, function(){
                var random = getRandom(allElems.length),
                    randEl = $(allElems[random]).clone(true)[0];
                allElems.splice(random, 1);
                return randEl;
           });
 
        this.each(function(i){
            $(this).replaceWith($(shuffled[i]));
        });
 
        return $(shuffled);
 
    };

	$('#summary-button').click();
 
    WordToWordpress();

	$('#last-soal').click(function(){
		if ($('.not-done').length>0) {
			$('#ragu-modal').show();
		} else {
			if ($('.ragu-ragu').length>0) {
				$('#ragu-modal').show();
			} else {
				$('#yakin-modal').show();
			}
		}
		
	})
 
	$('#selesai').click(function(){
		if ($(this).hasClass('btn-success')){
			$.post(themedir+'/kumpul.php',{
				nama : $('#nama_siswa').text()
			},function(s){
				window.location = './';
			});
		} else {
		}
	})

	//repopulate answer
	<?php
        $mapel = $_SESSION['mapel'];

		$sql = "SELECT * FROM `hasil` WHERE `test` = '$mapel' AND `userid` = '".strtoupper($_SESSION['siswa'])."'";
		$result = $conn->query($sql);
	?>

	jQuery(document).ready(function($) {
		$('.essay').prev().remove();
		<?php
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
			    	for ($i=1;$i<=100;$i++) {
			    		$ans = $row['no'.$i];
			    		if ($ans!='') {
			    			?>
			    				<?php 
			    					if (strlen($ans)>1) {
			    						?>
						    				$('textarea[data-nomor-asli=<?php echo $i ?>]').val('<?php echo preg_replace("/\r\n|\r|\n/",'\r\n',$ans); ?>');
			    						<?php
			    					} else {
			    						?>
						    				$('.option[data-nomor-asli=<?php echo $i ?>][data-option-asli=<?php echo $ans ?>]').addClass('checked');
			    						<?php
			    					}
			    				?>
			    				$('#summary .nomor-asli-<?php echo $i ?>').removeClass('not-done');
			    				$('#summary .nomor-asli-<?php echo $i ?>').addClass('done');
			    				$('#summary .nomor-asli-<?php echo $i ?> span').text($('.option[data-nomor-asli=<?php echo $i ?>].checked').text());
			    			<?php
			    		}
			    	}
			    }
			} else {
			    //echo "0 results";
			}
		?>
	});


})(jQuery);

//CountDown
<?php

	$kodemapel = $_SESSION['mapel'];

	$sql = 'SELECT alokasi FROM options WHERE `kode`=?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $kodemapel);
	$stmt->execute();
	$meta = $stmt->result_metadata(); 
	unset ($result);
	unset ($params);
	while ($field = $meta->fetch_field()) 
	{ 
	    $params[] = &$row[$field->name]; 
	} 
	call_user_func_array(array($stmt, 'bind_result'), $params); 
	while ($stmt->fetch()) { 
	    foreach($row as $key => $val) 
	    { 
	        $c[$key] = $val; 
	    } 
	    $result[] = $c; 
	} 
	$stmt->close();

	if ($result) {
		$seconds  = $alokasi = $result[0]['alokasi']  * 60;
	}


	$sql = "SELECT `stamp` FROM hasil WHERE `test`='$kodemapel' AND `userid`='".strtoupper($_SESSION['siswa'])."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $time = $row['stamp'];
	        if ($time == '') {
	        } else {
		        $time = explode(":", $time);
		        $seconds = $time[0]*3600 + $time[1]*60 + $time[2];
	        }
	    }
	} else {
	}
?>
<?php if ($seconds <= 0) : ?>
	window.location = "./?logout=1";
<?php endif; ?>
var upgradeTime = <?php echo $seconds ?>;
var seconds = upgradeTime;
var countdownTimer = setInterval('timer()', 1000);

function timer() {
	var days        = Math.floor(seconds/24/60/60);
	var hoursLeft   = Math.floor((seconds) - (days*86400));
	var hours       = Math.floor(hoursLeft/3600);
	var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
	var minutes     = Math.floor(minutesLeft/60);
	var remainingSeconds = seconds % 60;
	if (remainingSeconds < 10) {
		remainingSeconds = remainingSeconds; 
	}
	if (hours<10) {
		hours = '0' + hours;
	}
	if (minutes<10) {
		minutes = '0' + minutes;
	}
	if (remainingSeconds<10) {
		remainingSeconds = '0' + remainingSeconds;
	}
	document.getElementById('countdown').innerHTML = hours + ":" + minutes + ":" + remainingSeconds;
	if (seconds <= 0) {
		clearInterval(countdownTimer);
		document.getElementById('countdown').innerHTML = "Completed";
		$('#selesai').removeClass('btn-default');
		$('#selesai').addClass('btn-success');
		$('#selesai').click();
	} else {
		seconds--;
	}
}

function removeOuterTag(elem){
	elem.replaceWith(elem.html());
}

function replaceTag(elem,begin,end,log) {
	s = begin;
	s += elem.html();
	s += end;
	elem.replaceWith(s);
}

function tabletodiv(table){

	table.each(function(index, el) {
		$(this).children('tbody').each(function(index, el) {
			$(this).children('tr').each(function(index, el) {
				$(this).children('td').each(function(index, el) {
					replaceTag($(this),'<div class="ex-td">','</div>');
				});
				replaceTag($(this),'<div class="ex-tr">','</div>');
			});
			replaceTag($(this),'<div class="ex-tbody">','</div>');
		});
		replaceTag($(this),'<div class="ex-table">','</div>');
	});

}

function WordToWordpress(){
	tabletodiv($('#soal-body>div>table'));
	removeOuterTag($('#soal-body>div'));
	for (i=1;i<=2;i++){
		removeOuterTag($('#soal-body>div'));
	}

	//Convert Html ke Class Object
	jumlahsoal = $('div.ex-tr').length / 6;
	console.log(jumlahsoal);
	$('#jumlah_soal').text(jumlahsoal);
	var soal = [];
	for (i=1;i<=jumlahsoal;i++) {
		console.log(i);
		soal[i] = {};
		soal[i].n = i;
		soal[i].q = $('div.ex-tr').eq((i-1)*6+0).children('.ex-td').eq(1).html();
		
		if ($('div.ex-tr').eq((i-1)*6+1).children('.ex-td').eq(2).html().indexOf('__')>=0) {
			soal[i].a = '<textarea data-nomor-asli='+i+' class="essay" style="width:100%;background:#fff" rows=5></textarea>';
		} else {
			soal[i].a = $('div.ex-tr').eq((i-1)*6+1).children('.ex-td').eq(2).html();
		}

		if ($('div.ex-tr').eq((i-1)*6+2).children('.ex-td').eq(2).html()=='&nbsp;') {
			soal[i].b = '<p class="to-be-removed">@</p>';
		} else {
			soal[i].b = $('div.ex-tr').eq((i-1)*6+2).children('.ex-td').eq(2).html();
		}

		if ($('div.ex-tr').eq((i-1)*6+3).children('.ex-td').eq(2).html()=='&nbsp;') {
			soal[i].c = '<p class="to-be-removed">@</p>';
		} else {
			soal[i].c = $('div.ex-tr').eq((i-1)*6+3).children('.ex-td').eq(2).html();	
		}
		
		if ($('div.ex-tr').eq((i-1)*6+4).children('.ex-td').eq(2).html()=='&nbsp;') {
			soal[i].d = '<p class="to-be-removed">@</p>';
		} else {
			soal[i].d = $('div.ex-tr').eq((i-1)*6+4).children('.ex-td').eq(2).html();	
		}

		if ($('div.ex-tr').eq((i-1)*6+5).children('.ex-td').eq(2).html()=='&nbsp;') {
			soal[i].e = '<p class="to-be-removed">@</p>';
		} else {
			soal[i].e = $('div.ex-tr').eq((i-1)*6+5).children('.ex-td').eq(2).html();	
		}
		
	}

	//Clear HTML
	var body = $('#soal-body');
	body.html('');

	//Masukkan Soal ke HTML
	html = '';
	for (i=1;i<=jumlahsoal;i++) {
		no = soal[i].n;
		html += '<div data-nomor-asli="'+no+'" id="nomor-asli-'+no+'" class="soal nomor-asli-'+no+'">';
		html += '<div class="nomor">'+no+'</div>';
		html += soal[i].q;
		html += '<div class="options-group">';

		for (j=1;j<=5;j++){
			html += '<div class="options">';
			html += '<span data-nomor-asli="'+no+'" data-option-asli="'+String.fromCharCode(64+j)+'" class="option">X</span>';
			html += soal[i][String.fromCharCode(96+j)];
			html += '</div>';
		}


		html += '</div>';
		html += '</div>';

	}
	body.html(html);

	//Jumlahsoal dan Yang harus Dikerjakan
	<?php 
		$sql = "SELECT shuffle2,shuffle,jumlahsoal, dikerjakan FROM `options` WHERE `kode`='".$_SESSION['mapel']."'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$stmt->bind_result($shuffle2,$shuffle, $mapel_jumlahsoal, $mapel_dikerjakan);
		while ($stmt->fetch()) {
		}
		$stmt->close();
	?>

	//Acak and Grouping
	<?php include('acak.js.php'); ?>

	//Re-Index

	$('.to-be-removed').parent().remove();

	var i=0;
	$('div.soal').each(function(index, el) {
		//Soal
		no = i+1;
		$(this).addClass('nomor-'+(no));
		$(this).find('div.nomor').text(no);

		//Pilihan
		var j = 0;
		<?php if ($shuffle2) : ?>
		$(this).children('div.options-group').find('.options').shuffle();
		<?php endif; ?>
		$(this).children('div.options-group').find('.options').each(function(index, el) {
			span = $(this).children('span');
			//span.text(String.fromCharCode(65+j));
			span.html('<span class="inneroption">'+String.fromCharCode(65+j)+'</span>');
			span.attr('data-nomor',no);
			span.attr('data-option',String.fromCharCode(65+j));
			span.addClass('option-'+String.fromCharCode(65+j))
			j++;
		});
		$(this).children('div.options-group').find('textarea').each(function(index, el) {
			span = $(this);
			span.attr('data-nomor',no);
			j++;
		});

		i++;

	});

	//summary
	var i=0;
	$('div.soal').each(function(index, el) {
		i++;
		var no_asli = $(this).attr('data-nomor-asli');
		$('#summary').append('<div id="jawaban-'+i+'" style="display:none"></div>');
		$('#summary').append('<div class="not-done no nomor-asli-'+no_asli+' no-'+i+' "><p>'+i+'</p><span></span></div>');
	});
	$('#summary .no-1').addClass('active')

		$('.no:gt(<?php echo $mapel_dikerjakan-1 ?>)').remove();
		$('.soal:gt(<?php echo $mapel_dikerjakan-1 ?>)').remove();
		$('#jumlah_soal').text('<?php echo $mapel_dikerjakan ?>');


	//Simpan Urutan Acak
	<?php if ($saveurutansoal==1) : ?>
	var s = '';
	var i = 0;
	$('div.soal').each(function(index, el) {
		i++;
		id = $(this).prop('id');
		id = id.replace("nomor-asli-","");
		s = s + i + "=" + id + "&";
	});
	$.get(themedir+'/api-18575621/saveurutan.php?'+s,{},function(s){
	});
	<?php endif; ?>

	//Aktifkan
	$('#soal-body').show();
	$('div.soal').eq(0).addClass('active');
}

</script>