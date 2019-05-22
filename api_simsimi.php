<?php

	/**
    * Simsimi Auto Bot API
    *
    * @author -Nguyen Phuong-
    * @param 
    *	-GET-
    *		Default : key = 'phuongdz'
    *		Action  : 'chat' -> message='your-question' -> return bot's answer
    *						-ex : ?key=phuongdz&action=chat&message=hello
    *				  'teach' -> ask='question' , ans='this answer'
    *				  		-ex : ?key=phuongdz&action=teach&ask=hi&ans=hi cc
    */

	function JsonResponse($obj){
		echo json_encode($obj,JSON_UNESCAPED_UNICODE);
	}

	function connectDatabase(){
		$database = new PDO('mysql:host=localhost;dbname=simsimi;charset=utf8mb4', 'root', '');
		return $database;
	}

	function selectAnsByAsk($ask){
		$database = connectDatabase();
	    $sql = "select * from sim where ask like '%$ask%'";
		$result = $database -> prepare($sql);
		$result -> execute();
		$result = $result -> fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	function selectRandAnsByAsk($ask){
		$listAns = selectAnsByAsk($ask);
		$ans = $listAns[rand(0,count($listAns)-1)];
		return $ans;
	}

	function selectRandomAsk(){
		$database = connectDatabase();
		$sql = "select * from sim";
		$result = $database -> prepare($sql);
		$result -> execute();
		$result = $result -> fetchAll(PDO::FETCH_ASSOC);
		return $result[rand(0,count($result)-1)];
	}

	function insertIntoDatabase($ask,$ans){
		$database = connectDatabase();
		$sql = "INSERT INTO sim VALUES ('','$ask','$ans','','')";
		$stmt = $database->exec($sql);
		return $stmt;
	}

	function validate(){
		$data = $_GET;
		if($data){
			if(isset($data['key']) && $data['key']=='phuongdz' && isset($data['action'])){
				if($data['action'] == 'chat'){
					if(isset($data['message'])){
						$data['message'] = addslashes($data['message']);
						return $data;
					}
					return false;
				}
				if($data['action'] == 'teach'){
					if(isset($data['ask']) && isset($data['ans'])){
						$data['ask'] = addslashes($data['ask']);
						$data['ans'] = addslashes($data['ans']);
						return $data;
					}
					return false;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	function main(){
		$data = validate();
		if($data){
			if($data['action'] == 'chat'){
				if(selectAnsByAsk($data['message'])){
					JsonResponse([
						'result' => 200,
						'message' => selectRandAnsByAsk($data['message'])['ans']
					]);
				}
				else{
					JsonResponse([
						'result' => 201,
						'message' => selectRandomAsk()['ask']
					]);
				}
			}
			if($data['action'] == 'teach'){
				if(insertIntoDatabase($data['ask'],$data['ans'])){
					JsonResponse([
						'result' => 200,
						'message' => 'ok,khi m hỏi là -'.$data['ask'].'- thì tau trả lời là -'.$data['ans'].'- chứ gì'
					]);
				}
				else{
					JsonResponse([
						'result' => 404,
						'message' => 'hình như lỗi cmnr'
					]);
				}
			}
		}
		else{
			JsonResponse([
				'result' => 404,
				'message' => 'request sai hoặc key sai'
			]);
		}
	}

	main();