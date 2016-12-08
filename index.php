<?php
$pageTitle = 'Списък разходи';
include './includes/header.php';
$selectedGroup = -1;
//var_dump($_GET['group']);

if (isset($_GET['dategroup']) && !empty($_GET['dategroup'])) {
    $selectedDategroup = $_GET['dategroup'];
} else {
    $selectedDategroup = -1;
}

if ($_GET) {
    $selectedGroup = (int) $_GET['group'];
}
if ($_POST) {
    if (file_exists('./date.txt')) {
        $result = file('./date.txt');

        foreach ($_POST['delete'] as $value) {
            unset($result[(int) $value]);
        }
        if (file_put_contents('./date.txt', implode('', $result))) {
            echo 'Записът е изтрит успешно';
        }
    }
}

?>

<a href="http://localhost/Razhod/razhod.php">Добавяне на нов разход</a>
<form method="GET">
<select name="group">
	<option value="-1">Всички</option>
	<?php
foreach ($groups as $key => $value) {
    echo '<option value="' . $key . '" ' . ($key == $selectedGroup ? 'selected' : '') . '>' . $value . '</option>';
}
?>
</select>
<input type="date" name="dategroup">
<input type="submit" value="Филтрирай" />
</form>
<form method="POST">
<table border="1">
	<tr>
		<td>Дата</td>
		<td>Име</td>
		<td>Сума</td>
		<td>Вид</td>
		<td>Редактиране</td>
		<td><input type="submit" value="Изтрий" /></td>
	</tr>

	<?php
if (file_exists('./date.txt')) {
    $result = file('./date.txt');
    $sum    = 0;
    foreach ($result as $k => $value) {

        $columns = explode('!', ($value));

        $date   = $columns[0];
        $name   = $columns[1];
        $amount = $columns[2];
        $group  = $columns[3];

        if (($selectedGroup == -1 && $selectedDategroup == -1) || ($selectedGroup == -1 && $selectedDategroup == $date) || ($selectedGroup == $group && $selectedDategroup == -1) || ($selectedGroup == $group && $selectedDategroup == $date)) {

            echo '<tr>
					<td>' . $date . '</td>
					<td>' . $name . '</td>
					<td>' . $amount . '</td>
					<td>' . $groups[trim($group)] . '</td>
					<td><a href="/Razhod/edit.php?red=' . $k . '">Редактирай</a></td>
					<td><input type="checkbox" name="delete[]" value="' . $k . '"></td>
				</tr>';
            $sum += $amount;
        }
    }
//var_dump($result);
}
?>

	<tr>
			<td>--</td>
			<td>--</td>
			<td><?=$sum;?></td>
			<td>--</td>
			<td>--</td>
			<td>--</td>
	</tr>
</table>
</form>

<?php
include './includes/footer.php';
?>
