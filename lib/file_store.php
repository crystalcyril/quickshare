<?php

class FileDb {

	private $db_file;

	private $db;

	function __construct() {
		
		global $conf;
		
		$this->db_file = $conf['db_dir'] . 'data.db';
		if (!realpath($this->db_file)) {
			$this->db_file = ROOT_DIR . '/' . $conf['db_dir'] . 'data.db';
		}
	}

	public function open() {
		$db = new PDO('sqlite:' . $this->db_file);
		try {
			$db->exec('CREATE TABLE files (id varchar(40), filename varchar(1024), upload_date DATETIME)');
		} catch (PDFException $e) {
		}
		
		$this->db = $db;
	}
	
	
	public function getOriginalFilename($id) {
		$sql = "SELECT filename FROM files WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result !== FALSE) {
			return $result['filename'];
		} else {
			return null;
		}
	}
	
	
	public function saveFile($id, $originalFilename) {
		$insert = "INSERT INTO files (id, filename, upload_date) VALUES (:id, :filename, datetime('now'))";
		$stmt = $this->db->prepare($insert);
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':filename', $originalFilename);
		$stmt->execute();
		$stmt = null;
	}
	
	
	public function findOldFiles($older_than_seconds) {
		
		$sql = "SELECT id, strftime('%s','now')-strftime('%s',upload_date) AS age FROM files WHERE age >= :diff";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':diff', $older_than_seconds, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
		
	}
	
	
	public function deleteFile($id) {
		
		$sql = "DELETE FROM files WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id', $id);
		return $stmt->execute();
		
	}
	
	
	public function close() {
		$file_db = null;	
	}

}


?>