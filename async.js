function kirimdata(){

	var dataaction = $(this).find("button[type=submit]:focus").val();
	var strformencoded = $(this).serialize();
	var arrayform = $(this).serializeArray(); 
	
	filegbr = $("#gambar1").prop('files')[0];
	filefoto = $("#gambar2").prop('files')[0];
	console.log(filefoto);
	df = new FormData();
	df.append("gambar2", filefoto);	
	df.append("dataku", strformencoded);
	df.append("action", dataaction);
	$.ajax({
		url: "prak6.php",
		type: "POST",
		data: df,
		processData: false,
		contentType: false,
		success: function (result) {
			 $("#result").html(result);
		}
	});
	
	return false;
}

