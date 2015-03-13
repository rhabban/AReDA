<?php

require_once("control/URLBuilder.php");
require_once("View.php");

/**
 * A class for preparing and rendering pages for the XSports web site when no user is logged in.
 * The basic usage of this class consists of calling one of the "makeXXXPage" methods, then
 * calling the "render" method.
 */
abstract class PrivateView extends View {

	/** The user logged in (as an instance of class Person). */
		protected $user;

		/**
		 * Builds a new instance.
		 * @param $user The user logged in
		 * @param $urlBuilder An object for building URLs
		 * @param $feedback The feedback to be displayed (once) to the user
		 */
		public function __construct ($user, URLBuilder $urlBuilder, $feedback=null) {
				parent::__construct($urlBuilder, $feedback);
				$this->user = $user;
		}

		/**
		 * Prepares a page where users are listed 
		 */
		public function makeUserAdministrativeDataPage($userCategories) {
			$this->title = "Tous vos formulaires";
			$this->content.= $this->makeUserAdministrativeDataMenu();
			$this->content.="<h2>" . $this->title . "</h2>";
			$this->content.="<div id='actions'>";
			$this->content.="<a id='edit' class='btn btn-primary btn-lg' data-toggle='tooltip' title data-original-title='Editer' role='button'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";
			$this->content.="&nbsp;<span data-toggle='modal' data-target='#modalAddCategory' aria-hidden='true'>
								<a id='addCategory' class='btn btn-success btn-lg' data-toggle='tooltip' title data-original-title='Ajouter une catégorie' role='button'>
									<span class='glyphicon glyphicon-plus'></span>
								</a>
							</span>";
			$this->content.='<div id="modalAddCategory" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="'.$this->urlBuilder->getSaveCategoryCreationURL()[0].'" method="post">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">Ajouter une catégorie</h4>
											</div>
											<div class="modal-body">
												<form>
													<div class="form-group">
														<label for="name_category">Nom de la catégorie</label>
														<input type="text" class="form-control" name="name_category" id="name_category" placeholder="Entrer le nom de la catégorie">
													</div>
													<label>Elements de la catégorie</label>
													<ul id="list_item">
														<li><a class="btn btn-success" id="addItem"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add item</a></li>
													</ul>
												</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Enregistrer la nouvelle catégorie</button>
											</div>
										</form>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->';
			$this->content.="</div><!-- /#modalAddCategory -->";
			$this->content.="</div>";
			$this->content.="<div class='row'>";

			foreach ($userCategories as $category){
				$this->content.="<div class='col-md-6'>";
				$this->content.="<div class='panel panel-default'>";
				$this->content.="<div class='panel-heading'><h3 style='margin:0px'>" . $category->getName() . "</h3></div>";
				$this->content.="<div class='panel-body'>";
				$this->content.="<ul>";
				$modalContent = "";
				foreach($category->getItemsList() as $item){
					// List items by categories
					$this->content.="<ul>";
					$this->content.="<li><b>" .$item->getName() ."</b></li>";
					$this->content.="<li><a href='#' name='" .$item->getName(). "'class='value_item'>" . $item->getValue() . "</a></li>";
					$this->content.="</ul>";
					// Pre-load modalContent per item
					$modalContent.= '<div class="form-group">
										<div>
											<label for="id_item_' .$item->getId(). '">' .$item->getName().'</label><span>&nbsp;|&nbsp;<span id="id_item_' .$item->getId(). '" class="editName_item" data-url="' .$this->urlBuilder->getSaveCategoryEditionURL()[0]. '"><a>Modifier</a></span></span>
										</div>
										<input type="text" class="form-control" name="value_id_item_' .$item->getId(). '" value="' .$item->getValue().'">
									</div>';
				}
				$this->content.="</ul>";
				$this->content.="<span data-toggle='modal' data-target='#modalEditCategory_" .$category->getId() ."' aria-hidden='true'><a>Modifier</a></span>";
				$this->content.="</div><!-- /.panel-body -->";
				$this->content.="</div><!-- /.panel -->";
				$this->content.="</div><!-- /.col-md-6 -->";

				// Edit Modal creation
				$this->content.='<div id="modalEditCategory_' .$category->getId(). '" class="modal editModal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="'.$this->urlBuilder->getSaveCategoryEditionURL()[0].'" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title">Modifier une catégorie : <span class="name_category">' .$category->getName(). '</span>&nbsp;
													<button type="button" id="id_category_' .$category->getId(). '" class="editName_category btn btn-primary" data-toggle="tooltip" data-placement="right"  data-url="' .$this->urlBuilder->getSaveCategoryEditionURL()[0]. '" title="Modifier le nom de la catégorie"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></h4>
												</div><!-- /.modal-header -->
												<div class="modal-body">';
					//Loading modalContent with all items								
				$this->content.= $modalContent;
				$this->content.=			'</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
													<button type="submit" class="btn btn-primary">Enregistrer</button>
												</div>
											</form>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->';
				$this->content.="</div><!-- /#modalEditCategory -->"; 
			}
			$this->content.="</div><!-- /.row -->";  

		}

		/**
		 * Prepares a page displaying a form for creating an artwork.
		 * @param $data An associative map fieldName => prefilledValue
		 * @param $errors An associative map fieldName => inputError (as strings)
		 * @param $newArtworkId The id for the new artwork
		 */
		public function makeArtworkCreationForm (array $data, array $errors, $newArtworkId) {
			$this->title="Formulaire d'ajout d'&oelig;uvre";
			$this->content.= $this->makeWorksMenu();
			$this->content.="<h2>" . $this->title . "</h2>";
			$this->content.= "<form action='{$this->urlBuilder->getSaveArtWorkURL($newArtworkId)[0]}' method='post'>".PHP_EOL;
			$elements=ArtworkForms::makeFormElements($data);
			$fields=array("title" => "", "method" => "", "date" => "");
			foreach ($fields as $fieldName => $label) {
				if (isset($errors[$fieldName])) {
						$this->content .= "<div class='form-group has-error'>";
						$this->content .= $elements[$fieldName];
						$this->content .= " <span class='control-label'>$errors[$fieldName]</span>";
				} else {
						$this->content .= "<div class='form-group'>";
						$this->content .= $elements[$fieldName];
				}
				$this->content .= "</div>".PHP_EOL;
			}
			$this->content .= "<button type='submit' class='btn btn-default'>Ajouter</button>".PHP_EOL;
			$this->content .= "</form>".PHP_EOL;
		}

		/**
		 * Prepares menu to user Administrative data page
		 */
		public function makeUserAdministrativeDataMenu(){
				$url = ".?";
				$url.= $_SERVER["QUERY_STRING"];
				$userAdministrativeDataMenu = "<nav>";
				$userAdministrativeDataMenu.= "<ul class='nav nav-tabs'>";
				foreach ($this->getUserAdministrativeDataMenu() as $link) {
						$slug = $link[1];
						$link = $link[0];
						
						if($link == $url){
								$userAdministrativeDataMenu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
						} else if($link == '.' && $_SERVER["QUERY_STRING"] == ''){
								$userAdministrativeDataMenu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
						} else if (count(explode('&amp;', $link))>1 && count(explode('&', $url))>1 ){
								$firstChild = explode('&amp;', $link)[1];
								if ($firstChild == explode('&', $url)[1]){
										$userAdministrativeDataMenu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
								}
						} else{
								$userAdministrativeDataMenu.= "<li><a href=\"".$link."\">".$slug."</a></li>";   
						}
				}
				$userAdministrativeDataMenu.= "</ul>";
				$userAdministrativeDataMenu.= "</nav>";
				return $userAdministrativeDataMenu;
		}


		/**
		 * Returns a menu for all pages.
		 * @return An associative array $url => $text
		 */
		protected function getMenu () {
				return array(
										 $this->urlBuilder->getWelcomeURL(),
										 $this->urlBuilder->getUserAdministrativeDataURL(),
								);
		}

		/**
		 * Returns a menu for user Administrative data pages.
		 * @return An associative array $url => $text
		 */
		protected function getUserAdministrativeDataMenu () {
				return array(
										 $this->urlBuilder->getUserAdministrativeDataURL('Afficher')
						);
		}

		protected function getLogBoxOrSalutation () {
				$url = $this->urlBuilder->getWelcomeURL();
				$res ="";
				$res.="<p>";
				$res.="Connect&eacute; comme ".$this->user->getSalutation();
				$res.=" , rang : ".$this->user->getRank();
				$res.="<form action='".$url[0]."' method='post'>";
				$res.="<input type='hidden' name='login-logout' value='logout'>";
				$res.="<input type='submit' value='D&eacute;connexion' />";
				$res.="</form>";
				$res.="</p>";
				return $res;
		}

}

?>
