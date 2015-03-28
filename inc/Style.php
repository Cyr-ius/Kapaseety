<?php
class Style {

	function Label($id="myLabel",$type="success",$titre=null,$class=null) {
		echo '<div id="'.$id.'" class="'.$class.' labelinfo" data-href="'.urlencode($titre).'"><center><h1><span class="label label-'.$type.'"></span></h1><h5 style="padding-top: 5px;">'.$titre.'</h5></center></div>';
		//~ echo '<div id="'.$id.'" class="'.$class.' labelinfo" data-href="'.urlencode($titre).'"><center><button type="button" class="btn btn-lg btn-'.$type.'"><span class="badge"></span><br/>'.$titre.'</button></center></div>';
	}

	function Label2($id="myLabel",$type="success",$titre=null,$class=null) {
		echo '<div id="'.$id.'" class="'.$class.' labelinfo" data-href="'.urlencode($titre).'"><center><h3><span class="label label-'.$type.'"></span></h3><h5 style="padding-top: 5px;">'.$titre.'</h5></center></div>';
	}
	
	function Graph($id="myGraph",$class=null,$height=300) {
		echo '<div id="'.$id.'" class="gauge '.$class.'" style="height:'.$height.'px"></div>';
	}

	// Fonction Tableau , prend en argument $this->Resultat , si $this->link vaut TRUE alors crée un lien avec la deuxième colonne en passant en lien 'data-moref' de la premiere colonne.
	function Tableau($resultat=array(),$classtype=null,$title=null,$nolink=false,$tableclass="table-paging") {
		if (count($resultat) == 0)  {
			return;
		}
		echo "
		<div class='cold-lg-12'>
		<div class='panel panel-default panel-stats ".$classtype."' style='margin: 9px 9px 0px '>";

		if (isset($title)) {echo "<div class='panel-heading'>".$title."</div>";}

		echo "
		<table class='table table-striped table-hover ".$tableclass."' cellspacing='0' width='100%'>\n
		<thead>\n
		<tr>\n";
		$j=0;
		$thead = array_keys($resultat[0]);
		foreach ($thead as $th) {
			if ($j ==0) {
				if (!$nolink) {
					next($thead);
				} else {
					echo "<th>$th</th>\n";
				}
			} else {
				echo "<th>$th</th>\n";
			}
				
		$j++;
		}
		echo "
		</tr>\n
		</thead>\n
		<tbody>\n";
		foreach ($resultat as $value)  {
			echo "<tr>\n";
			$i=0;
			$tr = array_keys($value);
			foreach ($tr as $index) {
				if ($i ==0) {
					if (!$nolink) {
						$moref=$value[$index];
						next($tr);
						
					} else {
						echo '<td>'.$value[$index].'</td>';
					}
				}  else {
					if ($i==1) {
						if (!$nolink) {	
							echo '<td><a href="#" data-href="'.$value[$index].'" data-moref="'.$moref.'" >'.$value[$index].'</a></td>';
						} else {
							echo '<td>'.$value[$index].'</td>';
						}
					} else {
						echo '<td>'.$value[$index].'</td>';
					}
				}
			$i++;
			}
			echo "</tr>\n";
		}
		echo "
		</tbody>\n
		</table>\n
		</div>\n
		</div>";
	}	

}
?>