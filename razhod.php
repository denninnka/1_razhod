<?php
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Sofia');
$pageTitle='Добавяне на нов разход';
include './includes/header.php';
if($_POST){
	$day=$_POST['day'];
	$username=trim($_POST['username']);
	$username=str_replace('!', ' ', $username);
	$suma=trim($_POST['suma']);
	$suma=str_replace(',', '.', $_POST['suma']);
	$suma=str_replace('!', ' ', $suma);
	$selectedGroup=(int)$_POST['group'];
	$error=false;
	if ($_POST['day']>=date("Y-m-d")) {
		echo "<p>Дата неможе да бъде бъдеща</p>";
		$error=true;
	}
	if (empty($_POST['day'])) {
		$day=date("Y-m-d");
	}
	if (mb_strlen($username)<3) {
		echo "<p>Името е прекалено късо</p>";
		$error=true;
	}
	if (!preg_match('/^[а-яА-Яa-zA-Z ]+$/u', $username)) {
		echo '<p>Името не трябва да съдържа числа и специални символи</p>';
		$error=true;
	}
	if (!is_numeric($suma)) {
		echo "<p>Сумата трябва да е число</p>";
		$error=true;
	}
	if ($suma<=0) {
		echo "<p>Сумата трябва да е положително число</p>";
		$error=true;
	}
	
	if (!array_key_exists($selectedGroup, $groups)) {
			echo '<p>Невалидна група</p>';
			$error=true;
		}
	if (!$error) {
		$result=$day.'!'.$username.'!'.$suma.'!'.$selectedGroup.'!'."\n";
		if(file_put_contents('./date.txt', $result, FILE_APPEND)){
			echo 'Записът е успешен';
		}
	}	
}

?>
<a href="http://localhost/Razhod/index.php">Виж всички разходи</a>
<h2>Въведете разход</h2>
<form method="POST">
	<div>Дата:<input type="date" name="day"></div>
	<div>Име:<input type="text" name="username"></div>
	<div>Сума:<input type="text" name="suma"></div>
	<div>Вид:
		<select name="group">
			<?php 
			foreach ($groups as $key=>$value) {
				echo '<option value="'.$key.'">' .$value. '</option>';
			}
			?>
		</select>
	</div>
<div><input type="submit" value="Добави" /></div>
</form>

<?php
include './includes/footer.php';
?>