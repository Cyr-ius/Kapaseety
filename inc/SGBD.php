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
			throw new exception("Echec lors de la connexion à MySQL : " . $connect_error);
			$Base = $this->mysqli->select_db(Settings::$sgbd_database,$mysqli);
			if (!$Base)
			throw new exception('Erreur de connexion à la base de donnees!!!');
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
	* Retourne le nombre de requêtes SQL effectué par l'objet
	*/
	public function RetourneNbRequetes()
	{
		return $this->NbRequetes;
	}
	/**
	* Envoie une requête SQL et récupère le résultât dans un tableau pré formaté
	*
	* $Requete = Requête SQL
	*/
	public function TabResSQL($Requete)
	{
		$i = 0;
		$Ressource = $this->mysqli->query($Requete);
		$TabResultat=array();
		trace($Requete);
		if (!$Ressource) throw new exception("Erreur de requête SQL $Requete");
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
	* Envoie une requête SQL et récupère le résultât dans un tableau pré formaté au norme DataTable
	*
	* $Requete = Requête SQL
	*/	
#	public function TabProcessSQL($Requete,$Id=null)
#	{
#		$i = 0;
#		$Ressource = $this->mysqli->query($Requete);
#		$TabResultat=array();
#		trace($Requete);
#		if (!$Ressource) throw new exception("Erreur de requête SQL $Requete");
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
	* Envoie une requête SQL et récupère le résultât dans un tableau pré format json
	*
	* $Requete = Requête SQL
	*/
	public function ResSQL($Requete)
	{
		$i = 0;
		$Ressource =  $this->mysqli->query($Requete);
		$TabResultat=array();
		$num_rows = $Ressource->num_rows;
		trace($Requete);
		if (!$Ressource) throw new exception("Erreur de requête ResSQL $Requete");
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
	* Retourne le dernier identifiant généré par un champ de type AUTO_INCREMENT
	*
	*/
	public function DernierId()
	{
		return $this->mysqli->insert_id();
	}
	/**
	* Envoie une requête SQL et retourne le nombre de table affecté
	*
	* $Requete = Requête SQL
	*/
	public function ExecuteSQL($Requete)
	{
	$Ressource =  $this->mysqli->query($Requete);
	if (!$Ressource) throw new exception("Erreur de requête ExecuteSQL $Requete");
	$this->NbRequetes++;
	$NbAffectee = $this->mysqli->affected_rows;
	return $NbAffectee;
	}
}

?>
