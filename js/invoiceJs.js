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
	
	companies();
	
	if(id>0){
		$('#doc').find("[name=number]").change();
		$('#dates').find("[name=preDate]").change();
		
		$("#productTable tbody tr").each(function (index) {
			var $tblrow = $(this);
			
			changeRowValue($tblrow);
			
			calulateRowValue($tblrow);
		});
		
		$("#productTable tfoot tr").find("[name=added]").val($("#productTable tfoot tr").find("[name=transport]").val());
		calculateTotal();
	}
	
	addRow();
}

function companies(){
	$('#doc').find('.number').on('change', function () {
		var numberVal = " "+$('#doc').find("[name=number]").val();
		numberVal = (numberVal==" ") ? "" : numberVal;
		$("[name=docNumber]").val("Dokuments"+numberVal);
	}); 
	
	$('#dates').find('.preDate, .recDate, .paymentDate').on('change', function () {
		var preDate = " "+$('#dates').find("[name=preDate]").val();
		var recDate = " "+$('#dates').find("[name=recDate]").val();
		var paymentDate = " "+$('#dates').find("[name=paymentDate]").val();
		preDate = (preDate==" ") ? "" : preDate;
		recDate = (recDate==" ") ? "" : recDate;
		paymentDate = (paymentDate==" ") ? "" : paymentDate;
		$("[name=date]").val("Datums"+preDate+recDate+paymentDate);
	}); 

	$("#productTable tfoot tr").each(function (index) {
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
	
	changeCompany("divSender");
	changeCompany("divReceiver");
}

function changeCompany(elementId){
	$('#'+elementId).find('.title').on('change', function () {
		$.ajaxSetup ({
			cache: false
		});
		
		var val = $('#'+elementId).find("[name=title]").val();
		var id = $('#Companies option').filter(function() {
			return this.value == val;
		}).data('id');
		getCompanyInfo(elementId, id, val);
	});
	$('#'+elementId).find('.reNumber, .location, .address, .bank, .representative').on('change', function () {
		var titleVal = " "+$('#'+elementId).find("[name=title]").val();
		var reNumberVal = " "+$('#'+elementId).find("[name=reNumber]").val();
		var locationVal = " "+$('#'+elementId).find("[name=location]").val();
		var addressVal = " "+$('#'+elementId).find("[name=address]").val();
		var bankVal = " "+$('#'+elementId).find("[name=bank]").val();
		var representativeVal = " "+$('#'+elementId).find("[name=representative]").val();
		titleVal = (titleVal==" ") ? "" : titleVal;
		reNumberVal = (reNumberVal==" ") ? "" : reNumberVal;
		locationVal = (locationVal==" ") ? "" : locationVal;
		addressVal = (addressVal==" ") ? "" : addressVal;
		bankVal = (bankVal==" ") ? "" : bankVal;
		representativeVal = (representativeVal==" ") ? "" : representativeVal;
		if(elementId == "divSender"){
			var sentence = "Preču piegādātājs";
		}
		else{
			var sentence = "Preču saņēmējs";
		}
		$("[name="+elementId.substring(3).toLowerCase()+"]").val(sentence+titleVal+reNumberVal+locationVal+addressVal+bankVal+representativeVal);
	}); 
	$('#'+elementId).find("[name=reNumber]").change();
}

function getCompanyInfo(elementId, id, val){
	if(isNaN(id) || id==0){
		document.getElementById(elementId).innerHTML = "<div class='container'>"+
			"<input type='text' name='title' class='title' placeholder='Kompānijas nosaukums' list='Companies' value='"+val+"'/>"+
			"<input type='text' name='reNumber' class='reNumber' placeholder='Kompānijas reģistrācijas numurs' value=''/>"+
			"<input type='text' name='location' class='location' placeholder='Kompānijas juridiskā adrese' value=''/>"+
			"<input type='text' name='address' class='address' placeholder='Kompānijas faktiskā adrese' value=''/>"+
			"<input type='text' name='bank' class='bank' placeholder='Kompānijas konta numurs' value=''/>"+
			"<input type='text' name='representative' class='representative' placeholder='Kompānijas pārstāvis' value=''/>"+
		"</div>";
		changeCompany(elementId);
	}
	else{
		var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById(elementId).innerHTML = this.responseText;
				changeCompany(elementId);
			}
		};
		xmlhttp.open("GET","php/companySelected.php?id="+id,true);
		xmlhttp.send();
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
	row.find('.itemGroup').on('change', function () {
		var val = row.find("[name=itemGroup]").val();
		var selectedOption = $('#prodGroups option').filter(function() {
			return this.value == val;
		});
		row.find("[name=tax]").val(selectedOption.data('tax'));
		row.find("[name=productGroupId]").val(selectedOption.data('id'));
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
			"tax" : $(tr).find('[name=tax]').val(),
			"productGroupId" : $(tr).find('[name=productGroupId]').val()
		}
	});
	
	
	dates = [$('#dates').find("[name=preDate]").val(),$('#dates').find("[name=recDate]").val(),$('#dates').find("[name=paymentDate]").val()];
	sender = [$('#divSender').find("[name=title]").val(), $('#divSender').find("[name=reNumber]").val(), 
		$('#divSender').find("[name=location]").val(), $('#divSender').find("[name=address]").val(), 
		$('#divSender').find("[name=bank]").val(), $('#divSender').find("[name=representative]").val()];
	receiver = [$('#divReceiver').find("[name=title]").val(), $('#divReceiver').find("[name=reNumber]").val(), 
		$('#divReceiver').find("[name=location]").val(), $('#divReceiver').find("[name=address]").val(), 
		$('#divReceiver').find("[name=bank]").val(), $('#divReceiver').find("[name=representative]").val()];
	transport = $("#productTable tfoot tr").find("[name=transport]").val();	
	data = [id,$('#doc').find("[name=number]").val(), dates, sender, receiver, transport,TableData];
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