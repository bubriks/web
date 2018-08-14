function start(id){
	var acc = document.getElementsByClassName("accordion");

	for (var i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
				panel.style.display = "none";
			} else {
				panel.style.display = "block";
			}
		})
	};
	
	acc[acc.length -1].nextElementSibling.style.display = "block";
	acc[acc.length -1].classList.toggle("active");
	
	if(id>0){
		documentClick();
		dateClick();
		companyClick("divSender");
		companyClick("divReceiver");
		
		var $tblrows = $("#productTable tbody tr");
		
		$tblrows.each(function (index) {
			var $tblrow = $(this);
			
			changeRowValue($tblrow);
			
			calulateRowValue($tblrow);
		});
		$("#productTable tfoot tr").find("[name=added]").val($("#productTable tfoot tr").find("[name=transport]").val());
		calculateTotal();
	}
	
	addRow();
	transportChanged();	
}

function transportChanged(){
	var $tblrows = $("#productTable tfoot tr");

	$tblrows.each(function (index) {
		var $tblrow = $(this);
		
		$tblrow.find('.transport').on('change', function () {
			if(isNaN(parseFloat($tblrow.find("[name=transport]").val()))){
				$tblrow.find("[name=transport]").val(0);
			}
			
			var transport = parseFloat($tblrow.find("[name=transport]").val());
			var total = parseFloat($tblrow.find("[name=total]").val()) - parseFloat($tblrow.find("[name=added]").val());
			var dif = (total + transport) / total;
			
			var $rows = $("#productTable tbody tr");
			
			var total = 0;
			$rows.each(function (index) {
				var $row = $(this);
				
				calulateRowValue($row);
				
				val = parseFloat($row.find('.subTotal').val() * dif);
				$row.find('.subTotal').val(val);
				total += val;
			});
			$tblrow.find("[name=added]").val(transport);
			$tblrow.find("[name=total]").val(total.toFixed(2));
		}); 
	});
}

function documentClick(){
	document.getElementById("docNumber").innerText = "Dokuments: " + document.getElementById("number").value;
}

function dateClick(){
	document.getElementById("date").innerText = "Datums: " + 
		document.getElementById("preDate").value + " " + 
		document.getElementById("recDate").value + " " + 
		document.getElementById("paymentDate").value;
}

function companyClick(panelId){
	var text = document.querySelector("#"+panelId).querySelector("#title").value + " " + 
		document.querySelector("#"+panelId).querySelector("#reNumber").value + " " + 
		document.querySelector("#"+panelId).querySelector("#location").value + " " + 
		document.querySelector("#"+panelId).querySelector("#address").value + " " + 
		document.querySelector("#"+panelId).querySelector("#bank").value + " " + 
		document.querySelector("#"+panelId).querySelector("#representative").value;
		
	if(panelId == "divReceiver"){
		document.getElementById("receiver").innerText = "Preču saņēmējs: " + text;
	}
	else{
		document.getElementById("sender").innerText = "Preču piegādātājs: " + text;
	}
}

function addRow() {
	var table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
	var row = table.insertRow(-1);
	var cell = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	var cell5 = row.insertCell(5);
	var cell6 = row.insertCell(6);
	var cell7 = row.insertCell(7);
	var cell8 = row.insertCell(8);
	cell.innerHTML = "<input type='hidden' name='id' class='id' value='0'/><button class='buttonDelete' style='border-radius: 4px;' onclick='deleteRow(this);'>Dzēst</button>";
	cell1.innerHTML = "<input type='text' class='name' name='name' placeholder='Nosaukums' />";
	cell2.innerHTML = "<input type='text' class='barcode' name='barcode' placeholder='Svītrkods' />";
	cell3.innerHTML = "<input type='text' class='serNumber' name='serNumber' placeholder='Seriāla numurs' />";
	cell4.innerHTML = "<input type='text' class='itemGroup' name='itemGroup' placeholder='Preču grupa' list='prodGroups' />";
	cell5.innerHTML = "<input type='text' class='amount' name='amount' placeholder='Daudzums' onkeypress='return isNumberKey(event,this.value)' />";
	cell6.innerHTML = "<input type='text' class='priceIn' name='priceIn' placeholder='Ienākoša cena' onkeypress='return isNumberKey(event,this.value)' />";
	cell7.innerHTML = "<input type='text' class='tax' name='tax' placeholder='PVN' onkeypress='return isNumberKey(event,this.value)' />";
	cell8.innerHTML = "<input type='text' class='subTotal' name='subTotal' placeholder='Summa' value='0' readonly/>";
		
	changeRowValue($('#productTable tr:last'));
}

function changeRowValue(row){
	row.find('.divSender').on('change', function () {
		divReceiver
		var val = row.find("[name=itemGroup]").val();
		var tax = $('#prodGroups option').filter(function() {
			return this.value == val;
		}).data('tax');
		row.find("[name=tax]").val(tax);
		calulateRowValue(row);
		calculateTotal();
	}); 
	
	row.find('.amount, .priceIn, .tax').on('change', function () {
		calulateRowValue(row);
		calculateTotal();
	});
}

function calulateRowValue(row){
	var amount = row.find("[name=amount]").val();
	var priceIn = row.find("[name=priceIn]").val();
	var tax = row.find("[name=tax]").val();
	var subTotal = parseFloat(amount) * (parseFloat(priceIn)*((parseFloat(tax)/100)+1));
	
	if (!isNaN(subTotal)) {

		row.find('.subTotal').val(subTotal.toFixed(2));
	}
	else{
		row.find('.subTotal').val(0);
	}
}

function calculateTotal(){
	var total = 0;
	if(parseFloat($('.added').val()) != 0){		
		$("#productTable tbody tr").each(function (index) {
			var $row = $(this);
			
			calulateRowValue($row);
			val = parseFloat($row.find('.subTotal').val());
			total += val;
		});
	
		var dif = (total + parseFloat($('.added').val())) / total;
				
		var total = 0;
		$("#productTable tbody tr").each(function (index) {
			var $row = $(this);
			
			val = parseFloat($row.find('.subTotal').val() * dif);
			$row.find('.subTotal').val(val);
			total += val;
		});
	}
	else{
		$(".subTotal").each(function () {
			var stval = parseFloat($(this).val());
			total += isNaN(stval) ? 0 : stval;
		});
	}
	$('.total').val(total.toFixed(2));
	document.getElementById("product").innerText = "Prece: " + total.toFixed(2);
}

function isNumberKey(evt, value){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode == 46 && value.indexOf(".") == -1 || charCode > 47 && charCode < 58){
		return true;
	}
	return false;
}

function deleteRow(btndel){
	if (typeof(btndel) == "object") {
		$(btndel).closest("tr").remove();
		
		calculateTotal();
	} else {
		return false;
	}
}

function saveInfo(id){
	var TableData = new Array();
    
	$('#productTable tbody tr').each(function(row, tr){
		TableData[row]={
			"id" :$(tr).find('[name=id]').val(),
			"name" :$(tr).find('[name=name]').val(),
			"barcode" : $(tr).find('[name=barcode]').val(),
			"serNumber" : $(tr).find('[name=serNumber]').val(),
			"itemGroup" : $(tr).find('[name=itemGroup]').val(),
			"amount" : $(tr).find('[name=amount]').val(),
			"priceIn" : $(tr).find('[name=priceIn]').val(),
			"tax" : $(tr).find('[name=tax]').val()
		}
	});
	
	
	dates = [document.getElementById("preDate").value,document.getElementById("recDate").value,document.getElementById("paymentDate").value];
	sender = [document.querySelector("#divSender").querySelector("#title").value, document.querySelector("#divSender").querySelector("#reNumber").value, 
		document.querySelector("#divSender").querySelector("#location").value, document.querySelector("#divSender").querySelector("#address").value, 
		document.querySelector("#divSender").querySelector("#bank").value, document.querySelector("#divSender").querySelector("#representative").value];
	receiver = [document.querySelector("#divReceiver").querySelector("#title").value, document.querySelector("#divReceiver").querySelector("#reNumber").value, 
		document.querySelector("#divReceiver").querySelector("#location").value, document.querySelector("#divReceiver").querySelector("#address").value, 
		document.querySelector("#divReceiver").querySelector("#bank").value, document.querySelector("#divReceiver").querySelector("#representative").value];
	transport = $("#productTable tfoot tr").find("[name=transport]").val();	
	data = [id,document.getElementById("number").value, dates, sender, receiver, transport,TableData];
	data = $.toJSON(data);
	
	$.ajax({
		type: "POST",
		url: "php/saveInvoice.php",
		data: "data=" + data,
		success: function(msg){
			alert(msg);
		}
	});
}