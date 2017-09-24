var themedir = 'wp-content/themes/unbk';

function changesoal(no){
	akhir = $('#jumlah_soal').text();
	if (no==0) {
		
	} else 
	if (no==akhir) {
		$('#nomor span').html(no);
		$('.no').removeClass('active');
		$('.no.no-'+no).addClass('active');
		$('#next-soal').hide();
		$('#last-soal').show();
	} else
	{
		$('#nomor span').html(no);
		$('.no').removeClass('active');
		$('.no.no-'+no).addClass('active');
		$('#next-soal').show();
		$('#last-soal').hide();
	}
	
	$('.ragu-check').removeClass('glyphicon-check');
	$('.ragu-check').removeClass('glyphicon-unchecked');
	if ($('.no.active').hasClass('ragu-ragu')){
		$('.ragu-check').addClass('glyphicon-check');
	} else {
		$('.ragu-check').addClass('glyphicon-unchecked');
	}
}

function savewaktu(){
	$('#ajax').show();
	userid = $('#nama_siswa').text();
	sisawaktu = $('#countdown').text();
	mapel = $('#mapel').val();
	$.post(themedir+'/jawab.php',{
		userid : userid,
		sisawaktu : sisawaktu,
		mapel : mapel
	},function(s){
		if (s=='logout'){
			alert ('UserId anda telah di reset oleh Proktor, anda akan Logout');
			window.location = "./?logout=1";
		}
		$('#ajax').hide();
	})	
}

window.onbeforeunload = function(){
   // Do something
   return 'adsasd';
}

setTimeout(function() {
	window.onbeforeunload = function(evt) {
	    var message = 'Did you remember to download your form?';
	    if (typeof evt == 'undefined') {
		    savewaktu();
	    }
	    if (evt) {
		    savewaktu();
	    }


	    savewaktu();
	}
}, 2000);


$(document).ready(function(){


	$('body').keyup(function(event) {
		if (event.keyCode==65) {
			$('.soal.active .option-A').click();
		} else 
		if (event.keyCode==66) {
			$('.soal.active .option-B').click();
		} else 
		if (event.keyCode==67) {
			$('.soal.active .option-C').click();
		} else 
		if (event.keyCode==68) {
			$('.soal.active .option-D').click();
		} else 
		if (event.keyCode==69) {
			$('.soal.active .option-E').click();
		} else 
		if (event.keyCode==39 && !($('#next-soal:visible').length == 0)) {
			$('#next-soal').click();
		} else 
		if (event.keyCode==37) {
			$('#prev-soal').click();
		} else 
		{

		}
	});

	$(window).scroll(function (event) {
	    var scroll = $(window).scrollTop();
	    if (scroll>135){
	    	$('#soal-head').addClass('fixed-top');
	    	$('body.logged-in.soal-in').css('padding-top','80px');
	    } else {
	    	$('#soal-head').removeClass('fixed-top');
	    	$('body').css('padding-top','0px');
	    }
	});


	$('.container-fluid').click(function(event) {
		$('#summary-button.show').click();
	});
	$('.container').click(function(event) {
		$('#summary-button.show').click();
	});
	$('.summary-log').click(function(event) {
		$('#summary-button.show').click();
	});
	$('footer').click(function(event) {
		$('#summary-button.show').click();
	});

	
	$('.option').click(function(){
		sisawaktu = $('#countdown').text();
		$('#ajax').show();
		$(this).closest('.options-group').find('.option').each(function(){
			$(this).removeClass('checked');
		})
		act_no = $(this);
		act_no.addClass('checked');
 		nomor = $(this).attr('data-nomor');
		ragu = $('.no.no-'+nomor).hasClass('ragu-ragu');
 		nomor = $(this).attr('data-nomor-asli');
		opt=$(this).attr('data-option-asli');
		userid = $('#nama_siswa').text();
		$('#jawaban-'+nomor).html(opt);
		//alert($('#jawaban-'+nomor).html());
		if (ragu) {
			ragu = 'YA';
		} else {
			ragu - 'TIDAK';
		}

		mapel = $('#mapel').val();

		act_no.removeClass('checked');
		$.post(themedir+'/jawab.php',{
			userid : userid,
			no : nomor,
			option : opt,
			ragu : ragu,
			sisawaktu : sisawaktu,
			mapel : mapel
		},function(s){
			if (s=='logout'){
				alert ('UserId anda telah di reset oleh Proktor, anda akan Logout');
				window.location = "./?logout=1";
			}
			$('#ajax').hide();
			act_no.addClass('checked');
			$('#serial-no-'+nomor).val(opt);
			nomor = act_no.attr('data-nomor');
			$('.no.no-'+nomor+' span').html(act_no.html());
			$('.no.no-'+nomor).addClass('done');
			$('.no.no-'+nomor).removeClass('not-done');
		})
	})

	$('.essay').focusout(function(event) {
		$('#ajax').show();
		sisawaktu = $('#countdown').text();
		act_no = $(this);
		jawaban = $(this).val();
 		nomor = $(this).attr('data-nomor');
		ragu = $('.no.no-'+nomor).hasClass('ragu-ragu');
 		nomor = $(this).attr('data-nomor-asli');
		opt=jawaban;
		userid = $('#nama_siswa').text();
		$('#jawaban-'+nomor).html('X');
		//alert($('#jawaban-'+nomor).html());
		if (ragu) {
			ragu = 'YA';
		} else {
			ragu - 'TIDAK';
		}

		mapel = $('#mapel').val();

		act_no.removeClass('checked');
		$.post(themedir+'/jawab.php',{
			userid : userid,
			no : nomor,
			option : opt,
			ragu : ragu,
			sisawaktu : sisawaktu,
			mapel : mapel
		},function(s){
			//console.log(jawaban);
			if (s=='logout'){
				alert ('UserId anda telah di reset oleh Proktor, anda akan Logout');
				window.location = "./?logout=1";
			}
			$('#ajax').hide();
			act_no.addClass('checked');
			$('#serial-no-'+nomor).val(opt);
			nomor = act_no.attr('data-nomor');
			$('.no.no-'+nomor+' span').html(act_no.html());
			$('.no.no-'+nomor).addClass('done');
			$('.no.no-'+nomor).removeClass('not-done');
			//console.log(s);
		})
	});
	
	$('.no.done').removeClass('not-done');
	
	$('.ragu').click(function(){
		a = $(this).find('.ragu-check');
		sisawaktu = $('#countdown').text();
		if (a.hasClass('glyphicon-unchecked')){
			a.removeClass('glyphicon-unchecked');
			a.addClass('glyphicon-check');
			nomor = $('.soal.active').find('.nomor').text();
			$('.no.no-'+nomor).addClass('ragu-ragu');
			$.post(themedir+'/jawab.php',{
				userid : 'asdasdasd',
				mapel  : 'asdasdasd',
				no 	   : 'asdasd',
				sisawaktu : sisawaktu,
				ragu   : 'YA'
			}, function(){

			});
		} else {
			a.removeClass('glyphicon-check');
			a.addClass('glyphicon-unchecked');
			nomor = $('.soal.active').find('.nomor').text();
			$('.no.no-'+nomor).removeClass('ragu-ragu');
		}
		$('.soal.active .option.checked').click();
	})
	
	$('.no').click(function(){
		nomor = $(this).find('p').html();
		$('.soal').removeClass('active');
		$('.soal.nomor-'+nomor).addClass('active');
		changesoal(nomor);
	})
	
	$('#summary-button').click(function(){
		if ($(this).hasClass('show')){
			$('#summary').hide();
			$(this).css('right',0);
			$(this).find('button').html('<span class="glyphicon glyphicon-menu-left" aria-hidden="true" style="position:relative; top:10px"></span> Daftar <br/>Soal');
			$(this).removeClass('show');
		} else {
			$('#summary').show();
			$(this).css('right',365);
			$(this).find('button').html('<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>');
			$(this).addClass('show');
		}
	})
	
	$('#next-soal').click(function(){
		$('.soal.active').next().addClass('active');
		$('.soal.active').eq(0).removeClass('active');
 		nomor = $('.soal.active').find('.nomor').text();
		changesoal (nomor);
	})
	$('#prev-soal').click(function(){
		$('.soal.active').prev().addClass('active');
		$('.soal.active').eq(1).removeClass('active');
 		nomor = $('.soal.active').find('.nomor').text();
		changesoal (nomor);
	})
	
	
	$('.close').click(function(){
		$('.modal').hide();
	})
	$('.close-modal').click(function(){
		$('.modal').hide();
	})
	
	$('#yakin').change(function(){
		if ($(this).is(":checked")){
			$('#selesai').removeClass('btn-default');
			$('#selesai').addClass('btn-success');
		} else {
			$('#selesai').removeClass('btn-success');
			$('#selesai').addClass('btn-default');
		}
	})
	
	$('.a3').click(function(){
		fontsize = parseInt($('.soal').css("font-size"));
		font = fontsize+5+"px";
		font2 = fontsize+3+"px";
		width = $('.option').css('width');
		width = parseInt(width.replace('px',''))+4+"px";
		height = $('.option').css('height');
		height = parseInt(height.replace('px',''))+4+"px";
		$('.soal').css({'font-size':font});
		$('.option').css({'font-size':font2});
		$('.option').css({'height':height});
		$('.option').css({'width':width});
		
	})

	$('.a1').click(function(){
		fontsize = parseInt($('.soal').css("font-size"));
		font = fontsize-5+"px";
		font2 = fontsize-3+"px";
		width = $('.option').css('width');
		width = parseInt(width.replace('px',''))-4+"px";
		height = $('.option').css('height');
		height = parseInt(height.replace('px',''))-4+"px";
		$('.soal').css({'font-size':font});
		$('.option').css({'font-size':font2});
		$('.option').css({'height':height});
		$('.option').css({'width':width});
		
	})

	$('.a2').click(function(){
		fontsize = "14px";
		$('.soal').css({'font-size':fontsize});
		$('.option').css({'height':'23px'});
		$('.option').css({'width':'23px'});
		$('.option').css({'font-size':'12px'});
	})
})


