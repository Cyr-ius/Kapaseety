<?php

class SGBD {

private 
	$mysqli,
	$NbRequetes  = 0;
	
	public function __construct(){

		$this->server = Settings::$sgbd_server;
		$this->user = Settings::$sgbd_user;
		$this->pwd = Settings::$sgbd_password;
		$this->db = Settings::$sgbd_database;
	}
	
	public function __destruct() {
		$this->disconnect();
	}
	
	public function connect() {
		$mysqli=new mysqli($this->server,$this->user,$this->pwd,$this->db);
		$mysqli->set_charset("utf8");
		if ($mysqli->connect_errno) {
			throw new exception("Echec lors de la connexion � MySQL : " . $connect_error);
			$Base = $this->mysqli->select_db(Settings::$sgbd_database,$mysqli);
			if (!$Base)
			throw new exception('Erreur de connexion � la base de donnees!!!');
		}
		return $this->mysqli = $mysqli;
	}
	
	public function disconnect() {
		$this->mysqli->close();
	}
	
	public function esc_str($string){
		return $this->mysqli->real_escape_string($string);
	}
	
	/**
	* Retourne le nombre de requ�tes SQL effectu� par l'objet
	*/
	public function RetourneNbRequetes()
	{
		return $this->NbRequetes;
	}
	/**
	* Envoie une requ�te SQL et r�cup�re le r�sult�t dans un tableau pr� format�
	*
	* $Requete = Requ�te SQL
	*/
	public function TabResSQL($Requete)
	{
		$i = 0;
		$Ressource = $this->mysqli->query($Requete);
		$TabResultat=array();
		trace($Requete);
		if (!$Ressource) throw new exception("Erreur de requ�te SQL $Requete");
		while ($Ligne = $Ressource->fetch_assoc())
		{
			foreach ($Ligne as $clef => $valeur) $TabResultat[$i][$clef] = $valeur;
			$i++;
		}
		$Ressource->free_result();
		$this->NbRequetes++;
		return $TabResultat;
	}
	/**
	* Envoie une requ�te SQL et r�cup�re le r�sult�t dans un tableau pr� format� au norme DataTable
	*
	* $Requete = Requ�te SQL
	*/	
#	public function TabProcessSQL($Requete,$Id=null)
#	{
#		$i = 0;
#		$Ressource = $this->mysqli->query($Requete);
#		$TabResultat=array();
#		trace($Requete);
#		if (!$Ressource) throw new exception("Erreur de requ�te SQL $Requete");
#		
#		while ($Ligne = $Ressource->fetch_assoc())
#		{
#			foreach ($Ligne as $clef => $valeur) {
#				if ($Id==$clef) {
#					$TabResultat[$i]["DT_RowData"] = (array($clef=>$valeur));
#				} else {
#					$TabResultat[$i][$clef] = $valeur;
#				}
#			}
#			$i++;
#		}
#		$Ressource->free_result();
#		return array("draw"=>1,"recordsTotal"=>count($TabResultat),"recordsFiltered"=>count($TabResultat),"data"=>$TabResultat);
#	}	
	
	/**
	* Envoie une requ�te SQL et r�cup�re le r�sult�t dans un tableau pr� format json
	*
	* $Requete = Requ�te SQL
	*/
	public function ResSQL($Requete)
	{
		$i = 0;
		$Ressource =  $this->mysqli->query($Requete);
		$TabResultat=array();
		$num_rows = $Ressource->num_rows;
		trace($Requete);
		if (!$Ressource) throw new exception("Erreur de requ�te ResSQL $Requete");
		while ($Ligne = $Ressource->fetch_assoc())
		{
			if ($num_rows >1) {
				$tableau =array();
				foreach ($Ligne as $key=>$valeur) {
					$tableau[] = $valeur;
				}
				$TabResultat[] = $tableau;
			} else {
				foreach ($Ligne as $key=>$valeur) {
					$TabResultat[] = $valeur;
				}
			}

		}
		$Ressource->free_result();
		$this->NbRequetes++;
		return $TabResultat;
	}	
	
	/**
	* Retourne le dernier identifiant g�n�r� par un champ de type AUTO_INCREMENT
	*
	*/
	public function DernierId()
	{
		return $this->mysqli->insert_id();
	}
	/**
	* Envoie une requ�te SQL et retourne le nombre de table affect�
	*
	* $Requete = Requ�te SQL
	*/
	public function ExecuteSQL($Requete)
	{
	$Ressource =  $this->mysqli->query($Requete);
	if (!$Ressource) throw new exception("Erreur de requ�te ExecuteSQL $Requete");
	$this->NbRequetes++;
	$NbAffectee = $this->mysqli->affected_rows;
	return $NbAffectee;
	}
}

?>
