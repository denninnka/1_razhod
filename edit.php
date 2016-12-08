<?php
date_default_timezone_set('Europe/Sofia');
$pageTitle='Редактиране разход';
include './includes/header.php';

if (file_exists('./date.txt')) {
	$result=file('./date.txt');
//	var_dump($result);	
	$red = (int)$_GET['red'];
//	var_dump($result);
	if(!array_key_exists($red, $result)){
	
	?>
		<h3>Няма такъв запис във базата</h3>
		<a href="http://localhost/Razhod/index.php">Виж всички разходи</a>
	<?php
		/// die не бива да се използва по този начин
		die();
	} 	

	$red_danni  = trim( $result[ $red ] );
//	var_dump($red_danni);
	$columns=explode('!', ($red_danni));
//	var_dump($columns);
	$date = $columns[0];
	$name = $columns[1];
	$amount = $columns[2];
	$group = $columns[3];	
}

if($_POST){
	$den=$date;
	$day=$_POST['newday'];
	$username=trim($_POST['username']);
	$username=str_replace('!', ' ', $username);
	$suma=trim($_POST['suma']);
	$suma=str_replace('!', ' ', $suma);
	$selectedGroup=(int)$_POST['group'];
	$error=false;
	if ($_POST['newday']>=date("Y-m-d")) {
		echo "<p>Дата неможе да бъде бъдеща</p>";
		$error=true;
	}
	if (empty($_POST['newday'])) {
		$day=$den;
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
		$result[$red]=$day.'!'.$username.'!'.$suma.'!'.$selectedGroup.'!'."\n";
		if(file_put_contents('./date.txt', implode('', $result))){
			echo 'Записът е редактиран успешно';
		}
	}	
}
?>

<a href="http://localhost/Razhod/index.php">Виж всички разходи</a>
<h2>Редактиране разход</h2>
<form method="POST">
<div>Дата:<input type="date" name="newday"></div>
<div>Име:<input type="text" name="username" value="<?=$name;?>"></div>
<div>Сума:<input type="text" name="suma" value="<?=$amount;?>"></div>
<div>Вид:
	<select name="group" value="">
		<?php 
		foreach ($groups as $key=>$value) {
			echo '<option value="'.$key.'" '.($key == $group ? 'selected':'').'>' .$value. '</option>';
		}
	?>
	</select>
</div>
<div><input type="submit" value="Редактирай" /></div>
</form>

<?php
include './includes/footer.php';
?>