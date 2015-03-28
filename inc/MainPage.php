<?php

class MainPage  extends Header
{
	private $menu;
	private $menubar;
	private $general;
	private $about;
	public $content;
	
	function __construct(){
		/// MenuBar
		$this->menubar 		= 	new Menubar();
		// About Kapaseety
		$this->about = new About();
		/// Footer
		$this->footer		= new Footer();
		
	}
	
	function toHTML() {
		$this->header();
		echo '<body>';
		echo '<div id="wrapper">';
		$this->menubar->toHTML();
		  echo '<div id="page-wrapper">';
			echo '<div class="row">';
				echo '<div id="content" class="col-lg-12" >';
					$this->content->toHTML();		
				echo '</div>';		
			echo '</div>';
		  echo '</div>';
		echo '</div>';
		//~ $this->about->toHTML();
		$this->footer->toHTML();
		$this->footer->scripts();
		echo'</body>';
	}
}

?>
