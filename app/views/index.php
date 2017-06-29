<?php 
	require_once(__DIR__.'/../controllers/SquaresController.php');
	$squares = SquaresController::selectAll();
	$used_positions = [];
	foreach ($squares as $square) {
		array_push($used_positions, $square->position);
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../assets/css/stylesheet.css">
	<link rel="stylesheet" href="../assets/css/font-awesome.css">
	<script src="../assets/js/jquery-3.1.1.js"></script>	
	<title>Interface WEB</title>
</head>
<body>
	<header>
		
	</header>
	<section>
		<?php if(!empty($squares)):  ?>
			<?php foreach ($squares as $square):  ?>
				<div id="<?= 'edit'.$square->id; ?>" class="edit-box">
				<h4>Editar Objeto <span onclick="Close(<?= $square->id; ?>)" class="close"><i class="fa fa-times"></i></span></h4>
				<form id="<?= 'form'.$square->id; ?>" action="../controllers/SquaresController.php" method="POST">
					<label for="nome">Nome:</label>
					<input id="nome" type="text" name="square[name]" value="<?= $square->name; ?>" required>
					<input type="checkbox" name="changeallname" value="true"><span style="font-size: 11pt;">&nbspMarque para modificar o nome em todos os objetos de mesma cor.</span> 

					<label for="number">Número:</label>
					<input id="number" type="number" name="square[number]" value="<?= $square->number; ?>" required>

					<label for="<?= 'cor'.$square->id; ?>">Cor:</label>
					<div class="color-input">
	                    <input id="<?= 'cor'.$square->id; ?>" type="color" name="square[color]" value="<?= $square->color;?>" required> 
	                    <label class="edit-label-color" style="background-color:<?= $square->color;?>;" id="<?= 'colorlabel'.$square->id; ?>" for="<?= 'cor'.$square->id; ?>"><i class="fa fa-paint-brush" aria-hidden="true"></i></label>
	                </div>
									
					<label for="<?= 'position'.$square->id; ?>">Posição:</label>
					<select name="square[position]" id="<?= 'position'.$square->id; ?>">
					<option value="<?= $square->position; ?>" selected><?= $square->position; ?></option>
						<?php for($i = 1; $i<=18; $i++): ?>
							<?php if(!in_array($i, $used_positions)): ?>
								<option value="<?= $i; ?>"><?= $i; ?></option>
							<?php endif; ?>
						<?php endfor; ?>
					</select>
					<input type="hidden" name="square[id]" value="<?= $square->id; ?>">
					<button type="submit" name="action" value="update">Editar</button>
				</form>
			</div>	
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="create-box">
			<h4>Criar novo Objeto</h4>
			<form action="../controllers/SquaresController.php" method="POST">
				<label for="nome">Nome:</label>
				<input id="nome" type="text" name="square[name]" required>
				
				<label for="number">Número:</label>
				<input id="number" type="number" name="square[number]" required>

				<label for="color">Cor:</label>
				<div class="color-input">
                    <input id="color" type="color" name="square[color]" required> 
                    <label id="colorlabel" for="color"><i class="fa fa-paint-brush" aria-hidden="true"></i></label>
                </div>
								
				<label for="position">Posição:</label>
				<select name="square[position]" id="position">
					<?php for($i = 1; $i<=18; $i++): ?>
						<?php if(!in_array($i, $used_positions)): ?>
							<option value="<?= $i; ?>"><?= $i; ?></option>
						<?php endif; ?>
					<?php endfor; ?>
				</select>

				<button type="submit" name="action" value="create">Criar</button>
			</form>
		</div>

		<div class="table-grid">
			<?php for($i=1; $i<=9; $i++): ?>
				<div ondrop="Drop(event)" ondragover="AllowDrop(event)" class="container" id="<?= $i; ?>">
					<?php $square = SquaresController::getByPosition($i); ?>
					<?php if(!empty($square)): ?>
						<div id="<?= 'box'.$square->id; ?>" ondragstart="Drag(event)" draggable="true" class="square" style="background-color: <?= $square->color; ?>" >
							<div  class="square-btns">
								<a onclick="EditModal(<?= $square->id; ?>)" href="#"><i class="fa fa-pencil"></i></a>
								<a href="../controllers/SquaresController.php?remove=<?= $square->id;?>"><i class="fa fa-trash"></i></a>
							</div>
							<div class="square-name"><?= $square->name; ?></div>
							<div class="square-number"><?= $square->number; ?></div>
						</div>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>
		<div class="table-line">
			<?php for($i=10; $i<=18; $i++): ?>
				<div ondrop="Drop(event)" ondragover="AllowDrop(event)" class="container" id="<?= $i; ?>">
					<?php $square = SquaresController::getByPosition($i); ?>
					<?php if(!empty($square)): ?>
						<div id="<?= 'box'.$square->id; ?>" ondragstart="Drag(event)" draggable="true" class="square" style="background-color: <?= $square->color; ?>" >
							<div class="square-btns">
								<a onclick="EditModal(<?= $square->id; ?>)" href="#"><i class="fa fa-pencil"></i></a>
								<a href="../SquaresController.php?remove=<?= $square->id;?>"><i class="fa fa-trash"></i></a>
							</div>
							<div class="square-name"><?= $square->name; ?></div>
							<div class="square-number"><?= $square->number; ?></div>
						</div>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>
		</div>
	</section>
	<footer>
		
	</footer>
	<script>
		<?php foreach ($squares as $square): ?>
			$(document).on("change", "<?= "#cor".$square->id; ?>", function(e) {
	            $('<?= "#colorlabel".$square->id; ?>').css("background-color",this.value);
	        });
		<?php endforeach; ?>

		$('#color').val("#008080");
		$(document).on("change", "#color", function(e) {
            $('#colorlabel').css("background-color",this.value);
        });

        var EditModal = function($id) {
        	$('#edit'+$id).css("display", "block");
        }

        var Close = function($id) {
        	$('#edit'+$id).css("display", "none");
        }

        var Drag =  function(event) {
		    event.dataTransfer.setData("text", event.target.id);
		}

		var AllowDrop = function(event) {
		    event.preventDefault();
		}

		var Drop = function(event) {
		    event.preventDefault();
		    var data = event.dataTransfer.getData("text");
		    event.target.appendChild(document.getElementById(data));
		    var targetId = event.target.id;
		    var squareId = data.substring(3, 4);
		    
		    $('#position'+squareId).val(targetId);
		    SubmitForm(targetId, squareId);
		}

		var SubmitForm = function(targetId, squareId) {
            $.ajax({url: "../controllers/SquaresController.php?position="+targetId+"&id="+squareId, success: function(result) {
                location.reload();
            }});
            event.preventDefault();  
		}
	</script>
</body>
</html>