<?php
	require 'vendor/autoload.php';

	function GetDbConnection(){
		   
		$client = new MongoDB\Client("mongodb://localhost:27017/?serverSelectionTimeoutMS=5000&connectTimeoutMS=10000");
	
		$db = $client->lab2;

		return $db;
	}

	function GetNurses(){
		$dbh = GetDbConnection();
		
		return $dbh->nurse->find();
	}

	function GetDepartments(){
		$dbh = GetDbConnection();
		
		return $dbh->nurse->distinct("department");
	}

	
	function GetShifts(){
		$dbh = GetDbConnection();
		
		return $dbh->nurse->distinct("shift");
	}

	function GetWardsByUser($nurseId){
		$dbh = GetDbConnection();

		$filter = array("nurseId" => strval($nurseId));

		$wards = $dbh->nurse_ward->find($filter);
		
		$array = array();

		foreach($wards as $ward){
			$wardEntity = $dbh->ward->findOne(array("_id" => new MongoDB\BSON\ObjectID($ward["wardId"])));

			array_push($array,$wardEntity);
		}

		return $array;
	}

	function GetNursesByDeparment($deparment){
		$dbh = GetDbConnection();
		
		return $dbh->nurse->find(array("department"=>strval($deparment)));
	}

	function GetDutyByShift($shift){
		$dbh = GetDbConnection();

		$filter = array("shift" => strval($shift));

		$nurses = $dbh->nurse->find($filter);
		
		$array = array();

		foreach($nurses as $nurse){
			$wards = $dbh->nurse_ward->find(array("nurseId" => strval($nurse["_id"])));

			foreach($wards as $ward){
				$wardEntity = $dbh->ward->findOne(array("_id" => new MongoDB\BSON\ObjectID($ward["wardId"])));

				$obj = array();

				$obj["nurseName"] = $nurse["name"];

				$obj["name"] = $wardEntity["name"];

				array_push($array,$obj);
			}
		}

		return $array;
	}

	function AddNurse($nurseName, $deparment, $shift){
		$dbh = GetDbConnection();

		$date = date('Y/m/d H:i:s');
		$id = rand();
		$queryString = "INSERT INTO nurse(id_nurse, name, date, department, shift)
							VALUES(" . $id . " ,'". $nurseName . "', cast('" . $date . "' as date)," . $deparment . ",'" . $shift . "')";

		$query = $dbh->prepare($queryString);

		$query->execute();
	}

	function AddWard($name){
		$dbh = GetDbConnection();

		$id = rand();

		$query = $dbh->prepare( "INSERT INTO ward(id_ward, name)
							VALUES(:id , :name)");

		$query->bindParam(':name', $name, PDO::PARAM_STR);
		$query->bindParam(':id', $id, PDO::PARAM_INT);

		$query->execute();
	}

	function ConnectWardWithNurse($idNurse, $idWard){
		$dbh = GetDbConnection();

		$id = rand();

		$query = $dbh->prepare( "INSERT INTO nurse_ward(fid_nurse, fid_ward)
							VALUES(:idNurse , :idWard)");

		$query->bindParam(':idNurse', $idNurse, PDO::PARAM_INT);
		$query->bindParam(':idWard', $idWard, PDO::PARAM_INT);

		$query->execute();
	}
?>