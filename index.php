<?php
	require("dbContext.php");

	if (isset($_GET["nurses"])){
		$resultNurses = GetWardsByUser($_GET["nurses"]);
	}

	if (isset($_GET["departments"])){
		$resultDepartments = GetNursesByDeparment($_GET["departments"]);
	}

	if (isset($_GET["shifts"])){
		$resultShifts = GetDutyByShift($_GET["shifts"]);
	}
?>

<html>
<head>
	<link rel="stylesheet" href="styles/index.css">
</head>

<body>
<h1 class="title">Проект клініка</h1>


<div class="content">
	<div class="output">
		<div class="content-item nurses ">
			<p class="content-title">Оберіть медсестру</p>
			<form action="index.php" method="GET">
				<select name="nurses" onchange="this.form.submit()">
					<?php
						foreach(GetNurses() as $nurse)
						{
							if (isset($_GET["nurses"]) && $nurse["_id"] == $_GET["nurses"]){
								echo '<option selected="selected" value=' . $nurse["_id"] . '>' . $nurse["name"] . '</option>';
							}
							else
							{
								echo "<option value=" . $nurse["_id"] . ">" . $nurse["name"] . "</option>";
							}
						}
					?>
				</select>
			</form>
			<div class="results">
				<span class="results-title">Перечінь палат, в яких дежурить обрана медсестра:</span>
				<?php
				if (isset($resultNurses))
				{
					foreach($resultNurses as $resultNurse)
					{
						echo "<span>" . $resultNurse["name"] . "</span>";
					}
				}
				?>
			</div>
		</div>

		<div class="content-item wards">
			<p class="content-title">Оберіть відділення</p>
			<form action="index.php" method="GET">
				<select name="departments" onchange="this.form.submit()">
					<?php
						foreach(GetDepartments() as $department)
						{
							if (isset($_GET["departments"]) && $department == $_GET["departments"]){
								echo '<option selected="selected" value=' . $department . '>' . $department . '</option>';
							}
							else
							{
								echo "<option value=" . $department . ">" . $department . "</option>";
							}
						}
					?>
				</select>
			</form>
			<div class="results">
				<span class="results-title">Медсестри обраного відділення:</span>
				<?php
				if (isset($resultDepartments))
				{
					foreach($resultDepartments as $resultDepartment)
					{
						echo "<span>" . $resultDepartment["name"] . "</span>";
					}
				}
				?>
			</div>
		</div>

		<div class="content-item shift">
			<p class="content-title">Оберіть зміну</p>
			<form action="index.php" method="GET">		
				<select name="shifts" onchange="this.form.submit()">
					<?php
						foreach(GetShifts() as $shift)
						{
							if (isset($_GET["shifts"]) && $shift == $_GET["shifts"]){
								echo '<option selected="selected" value=' . $shift . '>' . $shift . '</option>';
							}
							else
							{
								echo "<option value=" . $shift . ">" . $shift . "</option>";
							}
						}
					?>
				</select>
			</form>
			<div class="results">
				<span class="results-title">Чергування в обрану зміну:</span>
				<?php
				if (isset($resultShifts))
				{
					foreach($resultShifts as $resultShift)
					{
						echo "<span>" . $resultShift["nurseName"] . " - " . $resultShift["name"] . "</span>";
					}	
				}
				?>
			</div>
		</div>
	</div>
</div>
</body>
</html>