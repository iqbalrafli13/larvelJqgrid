<style type="text/css">
	input, select, textarea {
		text-transform: uppercase;
		padding: 5px;
	}
</style>

<form id="transaksiForm">
	<table width="100%" cellspacing="0" id="customerData">
		<tr>
			<td>
				<label>No Faktur</label>
			</td>
			<td>
				<input type="text" id="nofaktur" name="nofaktur" class="FormElement ui-widget-content ui-corner-all" autocomplete="off">
			</td>
			<td  class="errorForm" id="nofakturError">
				<span id="" class="">No Faktur Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Tanggal Faktur</label>
			</td>
			<td>
				<input type="text" name="tanggal" class="FormElement ui-widget-content ui-corner-all hasDatePicker" required autocomplete="off" maxlength="10">
			</td>
			<td  class="errorForm" id="tanggalError">
				<span id="" class="">Tanggal Faktur Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Name Pelanggan</label>
			</td>
			<td>
				<input type="PelangganError" name="nama" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
			</td>
			<td  class="errorForm" id="namapelangganError">
				<span id="" class="">Nama Pelanggan Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Gender</label>
			</td>
			<td>
				<select id="gender" class="FormElement ui-widget-content ui-corner-all" name="gender_id" required></select>
			</td>
			<td  class="errorForm" id="gender_idError">
				<span id="" class="">Gender Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Phone</label>
			</td>
			<td>
				<input type="text" name="phone" class="FormElement ui-widget-content ui-corner-all im-phone im-numeric" required autoco,digitalGroupSpacing:'4'mplete="off">
			</td>
			<td  class="errorForm" id="phoneError">
				<span id="" class="">Phone Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Saldo</label>
			</td>
			<td>
				<input type="text" name="saldo" class="FormElement ui-widget-content ui-corner-all im-currency" required autocomplete="off">
			</td>
			<td  class="errorForm" id="saldoError">
				<span id="" class="">Saldo Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Address</label>
			</td>
			<td>
				<textarea name="address" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off"></textarea>
			</td>
			<td  class="errorForm" id="addressError">
				<span id="" class="">Address Harus diisi</span>
			</td>
		</tr>

	</table>

	<br>

	<table width="100%" class="table ui-state-default" cellpadding="5" cellspacing="0" id="detailData">
		<thead>
			<tr>
				<th class="ui-th-div">Barang</th>
				<th class="ui-th-div">Qty</th>
				<th class="ui-th-div">Harga</th>
				<th class="ui-th-div">Total Harga</th>
				<th class="ui-th-div">Action</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td  class="errorForm" id="barangError">
					<span id="" class="">barang Harus diisi</span>
				</td>
				<td  class="errorForm" id="hargaError">
					<span id="" class="">harga Harus diisi</span>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td>
                    <input type="text"  name="barang[]" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
                </td>
				<td>
					<input type="text" id="qty0"  name="qty[]" onkeyup="cal(0)" class="qtyCal FormElement ui-widget-content ui-corner-all im-currency" required autocomplete="off">
				</td>
                <td>
                    <input type="text" id="harga0"  name="harga[]" onkeyup="cal(0)" class="hargaCal FormElement ui-widget-content ui-corner-all im-numeric im-currency" required autocomplete="off">
                </td>
                <td>
                    <input type="text" id="total_item0" readonly   name="total_item[]" class="totalItem total FormElement ui-widget-content ui-corner-all im-numeric" required autocomplete="off">
                </td>
				
				<td>
					<a href="javascript:">
						<span class="ui-icon ui-icon-trash" onclick="$(this).parent().parent().parent().remove()"></span>
					</a>
				</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td colspan="" style="text-align:right">Total</td>
				<td>
					<input type="text" id="total_faktur" readonly name="total_faktur"  class="total FormElement ui-widget-content ui-corner-all im-numeric " required autocomplete="off">
				</td>
				<td>
					<a href="javascript:" onclick="addRow(); setNumericFormat(); formBindKeys();">
						<span class="ui-icon ui-icon-plus"></span>
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</form>


<script type="text/javascript">
	$(document).ready(function() {
		// let index = 0
		setDateFormat()
		setNumericFormat()
		formBindKeys()
		totalFaktur();		
	})
	var indexRows = 1;
	function addRow() {
		indexRows++;

		$('#detailData tbody tr').last().before(`
			<tr>
				<td>
					<input type="text" name="barang[]" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
				</td>
				<td>
					<input type="text" id="qty${indexRows}" name="qty[]" onkeyup="cal(${indexRows})" class="qtyCal FormElement ui-widget-content ui-corner-all im-numeric im-currency"  required autocomplete="off">
				</td>
				<td>
					<input type="text" id="harga${indexRows}" name="harga[]" onkeyup="cal(${indexRows})" class="hargaCal FormElement ui-widget-content ui-corner-all im-numeric im-currency" required autocomplete="off">
				</td>
				<td>
					<input type="text" id="total_item${indexRows}" readonly  class="total totalItem FormElement ui-widget-content ui-corner-all im-numeric im-currency" required autocomplete="off">
				</td>
				
				<td>
					<a href="javascript:">
						<span class="ui-icon ui-icon-trash" onclick="$(this).parent().parent().parent().remove()"></span>
					</a>
				</td>
			</tr>
		`)
	}
	
	function setDateFormat() {
		$('.hasDatePicker').datepicker({
			dateFormat: 'dd-mm-yyyy',
			yearRange: '2000:2099'
		}).inputmask({
			inputFormat: "dd-mm-yyyy",
			alias: "datetime",
			// minYear: '2000-01-01'
		})
		.focusout(function(e) {
			let val = $(this).val()
			if (val.match('[a-zA-Z]') == null) {
				if (val.length == 8) {
					$(this).inputmask({
						inputFormat: "dd-mm-yyyy",
					}).val([val.slice(0, 6), '20', val.slice(6)].join(''))
				}
			} else {
				$(this).focus()
			}
		})
		.focus(function() {
			let val = $(this).val()
			if (val.length == 10) {
				$(this).inputmask({
					inputFormat: 'dd-mm-yyyy',
				}).val([val.slice(0, 6), '', val.slice(8)].join(''))
			}
		})
	}
	function setNumericFormat() {
		$('.im-numeric').keypress(function(e){
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			  return false;
			}
		})
		
		$('.im-currency').inputmask('integer', {
			alias: 'numeric',
			groupSeparator: ',',
			autoGroup: true,
			digitsOptional: false,
			allowMinus: false,
			placeholder: '',
		}).css('text-align', 'right');
		
    }

    $('.im-phone').inputmask("+62 999-9999-99999");
	$('.total').css('text-align', 'right');

	$.ajax({
		url: `${baseUrl}/api/gender`,
		type: 'GET',
		dataType: 'JSON',
		success: function(res) {
			res.forEach(function(el, i) {
				$('#gender').append(`
					<option value="${el.id}">${el.nama}</option>
				`)
				$('#gender').select2()
			})
		}
	})

	function cal(id) {
		harga =$('#harga'+id).val();
		qty =$('#qty'+id).val();
		harga = Number(harga.replace(/[^0-9-]+/g,""));
		qty = Number(qty.replace(/[^0-9-]+/g,""));

		hasil = (harga) * (qty);
		hasil =BigInt(harga*qty)

		hasil = total_group(hasil)

		$('#total_item'+id).val(hasil);
		totalFaktur();
		
	}

	function totalFaktur(){
		let total_faktur =0;
		$('.totalItem').each(function(){
			var totalItem  = $(this).val()
			totalItem = Number(totalItem.replace(/[^0-9-]+/g,""))
			total_faktur +=totalItem;
		})
		total_faktur = total_group(total_faktur)
		$('#total_faktur').val(total_faktur)
	}

	function total_group (result){
		hasil=result.toString();
		mod = hasil.length % 3;
		kepala_hasil = hasil.substring(0,mod);
		ekor_hasil = hasil.substring(mod);
		ekor = ekor_hasil.match(/.{1,3}/g);
		str= kepala_hasil;
		ekor.forEach(element => {
			str += ','+element;
		});
		if(!kepala_hasil) str = str.substring(1);
		return str;
	}

	function formBindKeys() {
		let inputs = $('#customerForm [name]:not(:hidden)')
		let element
		let position

		inputs.each(function(i, el) {
			$(el).attr('data-input-index', i)
		})

		$(inputs[0]).focus()

		inputs.focus(function() {
			$(this).data('input-index')
		})

		inputs.keydown(function(e) {
			let operator
			switch(e.keyCode) {
				case 38:
					element = $(inputs[$(this).data('input-index') - 1])
					if (element.is(':not(select)') && element.attr('type') !== 'email') {
						position = element.val().length
						element[0].setSelectionRange(position, position)
					}
					element.hasClass('hasDatePicker')
						? $('.ui-datepicker').show()
						: $('.ui-datepicker').hide()
					element.focus()
					break
				case 40:
					element = $(inputs[$(this).data('input-index') + 1])
					if (element.is(':not(select)') && element.attr('type') !== 'email') {
						position = element.val().length
						element[0].setSelectionRange(position, position)
					}
					element.hasClass('hasDatePicker')
						? $('.ui-datepicker').show()
						: $('.ui-datepicker').hide()
					element.focus()
					break
			}
		})
	}
</script>
