<?php 
	$msg='';
	$fromm = (empty($_POST['fromm'])) ? date('Y-01-01') : $_POST['fromm']; 
	$to = (empty($_POST['to'])) ? date('Y-m-d') : $_POST['to'];   
	$u = (!empty($_REQUEST['upd'])) ? intval($_REQUEST['upd']) : false;
	if ($u) $movimento=R::load('movimenti', $u);
	if (!empty($_REQUEST['importo'])) : 
		$movimento=(empty($_REQUEST['id'])) ?  R::dispense('movimenti') : R::load('movimenti', intval($_REQUEST['id']));
		$movimento->datamovimento = $_POST['datamovimento']; 
		$movimento->movimento = $_POST['movimento'];
		$movimento->categorie_id = (!empty($_POST['categorie_id'])) ? $_POST['categorie_id'] : null;
		$movimento->importo = $_POST['importo'];
		try {
			R::store($movimento);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;	
	
	if (!empty($_REQUEST['del'])) : 
		$movimento=R::load('movimenti', intval($_REQUEST['del']));
		try{
			R::trash($movimento);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;
	$movimenti = R::find('movimenti', 'datamovimento BETWEEN "' . $fromm . '" AND "' . $to . '" ORDER by id ASC LIMIT 999');
	$categorie=R::findAll('categorie');
	$tot=R::getCell('select SUM(importo) from movimenti');
?>
<div class="container">
<h1>
	Movimenti
</h1>
</div>
<h4 class="msg"><?=$msg?></h4>
<div class="container">
<h4>Seleziona un periodo di tempo:</h4>
<form method="post" action="?p=movimenti">
            <label for="da">
            From 
        </label>
        <input name="fromm" type="date"  value="<?= $fromm ?>"   />
        <label for="a">
            To
        </label>
        <input name="to"  type="date" value="<?= $to ?>"   />

        <button type="submit" tabindex="-1" class="btn-primary">
            Filtra
        </button>
		</form>
        </br>
	<div class="table-responsive">
	<table class="table" id="move">
		<colgroup>
			<col style="width:150px" />
		</colgroup>
		<thead>
			<tr class="success">
				<th>Data</th>
				<th>Descrizione</th>
				<th>Categoria</th>
				<th>Importo in €</th>
				<th style="width:60px;text-align:center">Modifica</th>
				<th style="width:60px;text-align:center">Cancella</th>
			</tr>
		</thead>
		<tbody>
		<? foreach ($movimenti as $r) : ?>
		<? if ($u==$r->id) : ?>
			<tr>
				<td>
					<input type="date" name="datamovimento" value="<?=date('Y-m-d',strtotime($r->datamovimento))?>" onchange="chg(this)" autofocus />
				</td>
				<td>
					<input name="movimento" value="<?=$r->movimento?>" onchange="chg(this)" />
				</td>
				<td>
					<select name="categorie_id" placeholder="categoria" onchange="chg(this)" >	
						<option></option>
						<? foreach ($categorie as $cat) : ?>
							<option value="<?=$cat->id?>" <?=($r->categorie_id==$cat->id) ? 'selected' : '' ?> >  <?=$cat->categoria ?> </option>
						<? endforeach ?>
					</select>
				</td>
				<td>
					<input name="importo" type="number" step="any" value="<?=$r->importo?>" onchange="chg(this)"  style="text-align:right" /> 
				</td>
				<td>
					<form id="frm" method="POST" action="?p=movimenti">
						<input type="hidden" name="id" value="<?=$r->id?>" />
						<input type="hidden" name="datamovimento" value="<?=$r->datamovimento?>" />
						<input type="hidden" name="movimento" value="<?=$r->movimento?>" />
						<input type="hidden" name="categorie_id" value="<?=$r->categorie_id?>" />
						<input type="hidden" name="importo" value="<?=$r->importo?>" />
						<button type="submit" class="">
							Salva
						</button>
					</form>
				</td>
				<td>
					&nbsp;
				</td>							
			</tr>
		<? else : ?>
			<tr>
				<td>
					<?=date('d/m/Y',strtotime($r->datamovimento))?>
				</td>
				<td>
					<p>
						<?=$r->movimento?>
					</p>
				</td>
				<td>
						<?=($r->categorie_id) ? $r->categorie->categoria : ''?>
				</td>
				<td style="text-align:right" >
					<?=0-($r->importo)?>
				</td>
				<td style="text-align:center" >
					<a href="?p=movimenti&upd=<?=$r['id']?>" class="btn btn-primary" style="text-decoration:none;">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</a>
				</td>
				<td style="text-align:center" >
				
						<a href="?p=movimenti&del=<?=$r['id']?>" tabindex="-1" class="btn btn-danger" style="text-decoration:none;">
							<strong><i class="fa fa-trash" aria-hidden="true"></i></strong>
						</a>
					
				</td>							
			</tr>		
		<? endif; ?>
		<? endforeach; ?>
		</tbody>
		
			<tfoot class="bg-warning">
				<tr>
				
				<td colspan="6"></td>
				</tr>
			</tfoot>
	</table>
	</div>
	<br/>
	<div  style="border: solid 1px #ccc; border-radius: 25px; padding:1em; padding-bottom:0px; padding-top:0px; margin-bottom:1em;"  class="container bg-success">
	<div class="form-group">
	<h4 style="text-align:center;"><strong>Inserisci una nuova voce di spesa</strong></h4>
		<? if (!$u) : ?>
		<div class="col-md-3">
		<label for="data">
				Data
			</label>
			<input type="date" class="form-control" name="datamovimento" value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>" onchange="chg(this)"  autofocus />
		</div>
		<div class="col-md-6">
		<label for="categoria">
				Seleziona una categoria
			</label>
			<select name="categorie_id" onchange="chg(this)" class="form-control">	
					<option></option>
					<? foreach ($categorie as $cat) : ?>
						<option value="<?=$cat->id?>">  <?=$cat->categoria ?> </option>
					<? endforeach ?>
			</select>
		</div>
		<div class="col-md-3">	
		<label for="importo">
				Importo in €
			</label>
		<input name="importo" placeholder="00.00" class="form-control" type="numer" step="any" value="" onchange="chg(this)" />
		</div>
		<br/>
		<div class="col-md-12">
		<label for="descrizione">
				Descrizione
			</label>
			<textarea placeholder="inserisci qui la descrizione della tua spesa..." name="movimento" class="form-control" value="" onchange="chg(this)"></textarea>
		</div>
		
		
		<form id="frm" method="POST" action="?p=movimenti">
			<input type="hidden" name="datamovimento" value="<?=date('Y-m-d')?>" />
			
			<input type="hidden" name="categorie_id" value="" />
			<input type="hidden" name="importo" value="" />
			<input type="hidden" name="movimento" value="" />
			
			<br/>
			
			<div style="text-align:center;">
			<button type="submit" class="btn btn-success"style="margin-top:1em; width:30%;">
				Salva
			</button>
			</div>
		</form>
		<? endif; ?>
	</div>
	</div>


<script src="https://code.jquery.com/jquery-3.1.1.js"></script>

<script>
	var chg=function(e){
		document.forms.frm.elements[e.name].value=(e.value) ? e.value : null
		//if (e.options && e.options[e.options.selectedIndex]) document.forms.frm.elements[e.name].value=e.options[e.options.selectedIndex].value
	}	
</script>

<script>
	$(document).ready(function() {
    $('#move').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
			
                total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                
				
                pageTotal = api
                        .column(3, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                
                $(api.column(3).footer()).html(
                        '<strong>Totale uscite relative alla selezione: '  + pageTotal + ' € <br/> Totale uscite: <?= 0-($tot);?> €</strong></p>'
                        );
            }
        });
        $(document).ready(function () {
            var table = $('#move').DataTable();
				
        });
    });
	
	</script>

