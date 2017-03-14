<!-- CDN per inclusione di:
		Bootstrap css & js
		JQuery core 
		JQuery datatable css & js
		JQuery daterangepicker css & js
		Moment (formattazione data)js
		fontawesome css
			favicon link (rules) salvarla nella stessa cartella dell'index con estenzione .ico
			meta tag viewport per responsive (il minimo indispensabile)
			*mettere navbar in index.php
	(da Manutenzione pc)
-->
	<!doctype html>
	<html lang="it">
	  <head>
		<title>Manutenzione pc</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="logo.ico">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" >
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css"/>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
	  </head>
	  <body>
		<div id="all" class="all">
			<? if (file_exists($pg)) include_once($pg); ?>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	  </body>
	</html>

	
<!-- NAV BAR mettere nel file index.php per inserirla 1 volta sola-->	
	<ul class="w3-navbar w3-card-8 w3-light-grey">
		<li><a href="?p=home"> Home </a> </li>
		<li>
			<a href="?p=elenco">
				Storico misurazioni
			</a>
		</li>
	</ul>
	<!-- o meglio ancora (vedi agata_css - attenzione fare cambiamenti di stile (ad esempio colore, tipo di navbar...)):-->
	<nav>
		<ul>
		<li><a href="#">home</a></li>
		<li><a href="#">pippo</a></li>
		<li><a href="#">pluto</a></li>
		</ul>
	</nav>
	
	
<!-- PORTARE A VIDEO ERRORI PHP - mettere in cima a tutti i files PHP e poi cancellare a consegna (pressione pa-master)-->	
	<?php
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
		
		// poi resto del codice 
	?>

	
<!-- funzione per FORMATTARE DATA con php nella TABLE (pressione pa-master)-->
	<?php $datamisurazione = $i->datamisurazione ;
		echo date('d/m/Y',strtotime("$datamisurazione"));
	?>
				
				
<!-- FORMATTAZIONE IMPORTO come money_format ma funz. nella TABLE anche su window(ricevute fatture Ale)-->
	<?=sprintf("%.2f",$r->importo)?>
	

<!-- VALORI MEDI - MEDIA - in calce alla TABLE (pressione pa-master) 
	valori medi trovati via db non cambiano secondo range data o utente-->

<tfoot>
    <tr class="w3-red">
	<td>
	</td>
      <td>Valore medio: 
		<?php $NumSist=R::getCell( 'Select Avg(sistolica) as Medio from pressione' );
		echo $NumSist = number_format($NumSist, 0, ',', '');
		?> 
	</td>
      <td>Valore medio: 
			<?php $NumDiast=R::getCell( 'Select Avg(diastolica)as Medio from pressione' );
			echo $NumDiast = number_format($NumDiast, 0, ',', '');
			?> 
	</td>
	  <td>
	</td>
	<td>
	</td>
    </tr>
  </tfoot>
  
  
<!--  CREARE PAGINA SINGOLA ID di ricevuta(ricevute & ricevuta fatture Ale) -->

	<!-- in pagina principale della TABLE inserire: (ricevute fatture Ale)--> 
	<td>
		<a href="?p=ricevuta&id=<?=$r['id']?>">
			<?=($r->numero)?>
		</a>
	</td>
	<!-- in pagina ricevutA (single ID) inserire: (ricevuta fatture Ale)--> 
	duplicato funzionamento di pagina ricevute (form inserimento) in ricevuta 
	con design semplificato

<!-- OTTIMIZZAZIONE x la STAMPA / PRINT della pagina ricevutA (single ID) . (ricevuta fatture es2)--> 
	<!-- creo file print.css-->
		@media print {
			@page {size: 210mm 297mm; margin: 30mm;}
			h1 {display:none;}
			title {display:none;}
		}
	<!-- importo il file print.css nella pagina singola ricevuta-->
		<style>
			@import url('print.css') print;
		</style>
	<!-- imposto la visualizzazione della pagina ricevuta.php-->	
  
	
<!-- ELIMINARE RIGA TABLE con id (pressione pa-master or ricevute fatture Ale)-->
	<td>
		<a href="?p=elenco&act=del&id=<?=$i->id?>" title="elimina questa rilevazione">
			x
		</a>
	</td>	
	<!--or dinamico-->
	<td style="text-align:center" >
		<a href="?p=<?=$tbl?>&del=<?=$r['id']?>" class="btn btn-danger" tabindex="-1">
			x
		</a>
	</td>	
	
<!-- SAVE INPUT DI FORM (pressione pa-master or ricevute fatture Ale)-->

	<form action="?p=elenco" method="POST">
		<caption>Nuova misurazione:</caption>
		<input type="date" name="datamisurazione" value="<?=$today?>" max="<?=$today?>" min="<?=$mindata?>" placeholder="data" required />
		<input type="number" name="sistolica" placeholder="sistolica" required />
		<input type="number" name="diastolica" placeholder="diastolica" required />	
		<input type="number" name="peso" placeholder="peso corporeo"/>	
		<button class="w3-btn w3-red w3-text-shadow"><i>Salva</i></button>
	</form>
	<!--or-->
	<?php if ($id || $new) : ?>
	<form method="post" action="?p=<?=$tbl?>">
		<div class="form-group">
			<div class="col-sm-1">
				<input class="form-control" name="numero" placeholder="Numero"  value="<?=($record->numero)?>"/>
			</div>
			<div class="col-sm-2">
				<input class="form-control" name="dataemissione" value="<?=date('Y-m-d',strtotime($record->dataemissione))?>" type="date" max="<?=date("Y-m-d")?>"/>
			</div>
			<div class="col-sm-2">
				<select class="form-control" name="clienti_id">
					<option/>
					<?php foreach ($clienti as $opt) : ?>
						<option value="<?=$opt->id?>" <?=($opt->id==$record->clienti_id) ? 'selected' :'' ?> >
							<?=$opt->nome?>
						</option>
					<?php endforeach; ?>
				</select>
					</div>
			<div class="col-sm-3">
		<input placeholder="Descrizione" class="form-control"name="descrizione"  value="<?=$record->descrizione?>" autofocus required  />			
		</div>
			<div class="col-sm-1">
		<input placeholder="EUR" class="form-control" name="importo" value="<?=$record->importo?>" type="number" step="any" />
		</div>		
			<div class="col-sm-1">
				<button type="submit" tabindex="-1" class="btn btn-success">
					Salva
				</button>
			</div>  
			<?php if ($id) : ?>
				<input type="hidden" name="id" value="<?=$record->id?>" />
			<?php endif; ?>
		</div>
	</form>

	
<!-- funzione FORM TORNA AD ELENCO (ricevute fatture Ale)-->
	<div class="col-sm-1">
			<a href="?p=<?=$tbl?>" class="btn btn-primary" >
				Elenco
			</a>
	</div>	
	
<!-- funzione LINK AD ALTRA PAGINA (ricevute fatture Ale)-->	
	<td>
          <a href="?p=clienti"><?=($r->clienti_id) ? $r->clienti->nome : ''?></a>
	</td>
	
	
<!-- LINK alla pagina INSERISCI NUOVO(es2)-->
	<a href="?p=interventi&create=1">Inserisci nuovo</a>		
	
	
<!-- VALIDAZIONE DATA NO > DI OGGI (pressione pa-master)-->
	<?php 
	$today=date('Y-m-d')
	$mindata=date('Y-01-01');
	?>

	<input type="date" value="<?=$today?>" max="<?=$today?>" min="<?=$mindata?>" />


<!-- VALIDAZIONE CAMPO FORM DIASTOLICA > SISTOLICA nel try - catch (pressione pa-master)--> 
	<?php

	$table='pressione';
		$record=(empty($_REQUEST['id'])) ?  R::dispense($table) : R::load($table, intval($_REQUEST['id']));	
		try {
			if ($record && !empty($_REQUEST['act']) && $_REQUEST['act']=='del') R::trash($record);
			if (!empty($_POST['datamisurazione'])){
				
				//validazione controllo diastolica > sistolica 
				
				$diastolica= $_POST['diastolica'];
				$sistolica= $_POST['sistolica'];
				if($diastolica>$sistolica){
					$error = "Errore!";
				}
				
				foreach ($_POST as $k=>$v){
					$record[$k]=$_POST[$k];
				}
				if(!$error)	R::store($record);
			}
		} catch (RedBeanPHP\RedException\SQL $e) {
			?>  	
	
<!-- FILTRO DATA A DATA, SOMMA TOT e PARZIALE - range data con datatable e datarangepicker (con datatable fatture es2)--> 
	<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
			
			 // Total over all pages
                total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Total over this page
                pageTotal = api
                        .column(3, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(3).footer()).html(
                        '€' + pageTotal + 'Totale della pagina ( €' + total + ' Totale Generale)'
                        );
            }
        });
		
		/*FILTRO DATA */
		
        $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = parseInt($('#min').val(), 10);
                    var max = parseInt($('#max').val(), 10);
                    var age = parseFloat(data[1]) || 0; // use data for the age column

                    if ((isNaN(min) && isNaN(max)) ||
                            (isNaN(min) && age <= max) ||
                            (min <= age && isNaN(max)) ||
                            (min <= age && age <= max))
                    {
                        return true;
                    }
                    return false;
                }
        );

		
		// script per plugin datatable
        $(document).ready(function () {
            var table = $('#example').DataTable();
			
		// esempio preso da pressione pa-master
		// $(document).ready(function(){
			// $('#pat').DataTable();
			// });	
			

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').keyup(function () {
                table.draw();
            });
        });
    });
	
	</script>

  

