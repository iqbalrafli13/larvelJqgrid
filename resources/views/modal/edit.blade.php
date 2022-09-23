<style type="text/css">
	input, select, textarea {
		text-transform: uppercase;
		padding: 5px;
	}
</style>
<?php
	$tanggal = date("d-m-Y", strtotime($transaction->tanggal));

?>
<form id="transaksiForm">
	<table width="100%" cellspacing="0" id="customerData">
		<tr>
			<td>
				<label>No Faktur</label>
			</td>
			<td>
				<input type="text" id="nofaktur" value="{{$transaction->nofaktur}}" name="nofaktur" class="FormElement ui-widget-content ui-corner-all" autocomplete="off">
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
				<input type="text" name="tanggal" value="{{$tanggal}}" class="FormElement ui-widget-content ui-corner-all hasDatePicker" required autocomplete="off" maxlength="10">
			</td>
			<td  class="errorForm" id="tanggalfakturError">
				<span id="" class="">Tanggal Faktur Harus diisi</span>
			</td>
		</tr>
		<tr>
			<td>
				<label>Name Pelanggan</label>
			</td>
			<td>
				<input type="text" name="nama" value="{{$transaction->nama}}" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
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
				<select id="gender" class="FormElement ui-widget-content ui-corner-all" name="gender_id" value="{{$transaction->gender_id}}" required></select>
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
				<input type="text" name="phone" value="{{$transaction->phone}}" class="FormElement ui-widget-content ui-corner-all im-phone im-numeric" required autoco,digitalGroupSpacing:'4'mplete="off">
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
				<input type="text" name="saldo" value="{{$transaction->saldo}}" class="FormElement ui-widget-content ui-corner-all im-currency" required autocomplete="off">
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
				<textarea name="address" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">{{$transaction->address}}</textarea>
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
				<th class="ui-th-div">Harga</th>
				<th class="ui-th-div">Qty</th>
				<th class="ui-th-div">Total Harga</th>
				<th class="ui-th-div">Action</th>
			</tr>
		</thead>
		<tbody>
            @foreach ($transaction->details as $detail)
            @php
                $total = $detail->harga * $detail->quantity;
            @endphp
			<tr>
                <td>
                    <input type="text" value="{{$detail->barang}}" name="barang[]" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
                </td>
                <td>
                    <input type="text" id="harga{{$detail->id}}" value="{{$detail->harga}}" name="harga[]" onkeyup="cal({{$detail->id}})" class="hargaCal FormElement ui-widget-content ui-corner-all im-numeric im-currency formcur" required autocomplete="off">
                </td>
                <td>
                    <input type="text" id="qty{{$detail->id}}" value="{{$detail->quantity}}" name="qty[]" onkeyup="cal({{$detail->id}})" class="qtyCal FormElement ui-widget-content ui-corner-all im-currency" required autocomplete="off">
                </td>
                <td>
                    <input type="text" id="total_item{{$detail->id}}" readonly value="{{$total}}" name="total_item[]" class="FormElement ui-widget-content ui-corner-all im-numeric im-currency formcur" required autocomplete="off">
                </td>
                
                <td>
                    <a href="javascript:">
                        <span class="ui-icon ui-icon-trash" onclick="$(this).parent().parent().parent().remove()"></span>
                    </a>
                </td>
            </tr>
            @endforeach
			<tr>
				<td colspan="4"></td>
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
		let index = 0
		setDateFormat()
		setNumericFormat()
		formBindKeys()
	})
	var indexRows = 599;
	function addRow() {
		indexRows++;

		$('#detailData tbody tr').last().before(`
			<tr>
				<td>
					<input type="text" name="barang[]" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
				</td>
				<td>
					<input type="text" id="harga${indexRows}" name="harga[]" onkeyup="cal(${indexRows})" class="hargaCal FormElement ui-widget-content ui-corner-all im-numeric im-currency" required autocomplete="off">
				</td>
				<td>
					<input type="text" id="qty${indexRows}" name="qty[]" onkeyup="cal(${indexRows})" class="qtyCal FormElement ui-widget-content ui-corner-all im-currency" required autocomplete="off">
				</td>
				<td>
					<input type="text" id="total_item${indexRows}" readonly name="total_item[]" class="FormElement ui-widget-content ui-corner-all im-numeric im-currency" required autocomplete="off">
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

	$.ajax({
		url: `${baseUrl}/api/gender`,
		type: 'GET',
		dataType: 'JSON',
		success: function(res) {
			var idgenderphp = {{$transaction->gender_id}}		
            res.forEach(function(el, i) {
				if (idgenderphp == el.id) {
					$('#gender').append(`
						<option selected value="${el.id}">${el.nama}</option>
					`)
				}else {
					$('#gender').append(`
						<option value="${el.id}">${el.nama}</option>
					`)
				}
				$('#gender').select2()
			})
		}
	})
	
	function cal(id) {
		harga =$('#harga'+id).val();
		qty =$('#qty'+id).val();
		harga = Number(harga.replace(/[^0-9-]+/g,""));
		qty = Number(qty.replace(/[^0-9-]+/g,""));
		
		hasil = harga * qty;
		$('#total_item'+id).val(hasil);
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
