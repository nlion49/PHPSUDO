<?php
/*************************************************************************************************************************************/
/* ********************** Class Sudoku    ********************************************************************************************/
/*************************************************************************************************************************************/
Class calculGrillePleine
{
  var $SudoVide = array(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0));
  var $tirage;
  var $sudo;
  var $max;
  var $min;
  var $NbCasesHZone; //nombre de cases horyzontale dans une zone 
  var $NbCasesVZone; //nombre de cases verticale dans une zone 
  var $TimeLimitCalcul=9; //temps limite de calcule d'une grille en seconde
  var $TimeOut=false; //temps d'execution trop long Timeout
  var $fin=false;
  
  function zone($tab,$ligne, $colonne) 
  {
   while($ligne % $this->NbCasesHZone) $ligne--;
   while($colonne % $this->NbCasesVZone) $colonne--;
   for($j=$ligne; $j<$ligne+$this->NbCasesHZone; $j++) 
   {
      for($i=$colonne; $i<$colonne+$this->NbCasesVZone; $i++)
      {
         $res[]=$tab[$j][$i];
      }
   }
   return $res;
  }
  
  function colonne($colonne) 
  { 
   for($i=0; $i<$this->max; $i++) 
      $res[]=$this->sudo[$i][$colonne];
   return $res;
  }
  
  function Grille($i=0) 
  { 
   for($ligne=$i; $ligne<$this->max; $ligne++) 
    {
      for($colonne=0; $colonne<$this->max; $colonne++) 
      {
         // un chiffre dans la case ?
         if($this->sudo[$ligne][$colonne]) continue;
        
         shuffle($this->tirage); 
         for($k=0; $k<sizeof($this->tirage); $k++) 
         { 
            $nbre = $this->tirage[$k];
            // chiffre dans la ligne ?
            if(in_array($nbre,$this->sudo[$ligne])) continue;
            // chiffre dans la colonne ?
            if(in_array($nbre,$this->colonne($colonne))) continue;
            //  chiffre est dans la zone ?
            if(in_array($nbre,$this->zone($this->sudo,$ligne,$colonne))) continue;
           
            $nbre_res=$this->sudo[$ligne][$colonne]; 
            $this->sudo[$ligne][$colonne]=$nbre;
            // grille terminée ?
            if(($ligne==$this->max-1)&&($colonne==$this->max-1)) $this->fin=true;
            if($this->fin) return;
            // sinon on continu
            $this->Grille($ligne);
            // chiffre suivant
            if(!$this->fin && (time()-$this->t0)<$this->TimeLimitCalcul){$this->sudo[$ligne][$colonne]=$nbre_res;}
            else if (!$this->fin && (time()-$this->t0>=$this->TimeLimitCalcul)){break;$this->TimeOut=true;} //temps execution trop long 
         }
         return;
      }
   }
  }
}
class SuDoKu extends calculGrillePleine
{
    public $level = 0;
    public $LevelHard = 0;
    public $TabValeursPossible;
    public $YTabValeursPossible;
    public $XTabValeursPossible;
    public $LimitNiveau;
    public $GrillePleine;
    public $IncompleteGrille;
    public $TestGrilleSudoku;
    public $ValidIncompleteGrille = false;
    public $CasesVidesCoordonneesX = null;
    public $CasesVidesCoordonneesY = null;
    public $WithSymbol = false;
    public $t0;

    public function __construct($init = true, $max = 4, $min = 2, $level = 0)
    {
        $this->max = $max;
        $this->min = $min;
        $this->level = $level;
        $this->NbCasesVZone = $this->max / $this->min;
        $this->NbCasesHZone = $this->min;

        if ($init) {
            $this->init();
        }
    }

    function init()
    {
        $this->t0=time();
        
         if(isset($this->GrillePleine)) unset ($this->GrillePleine);
        $this->GetGrille();  
        
        $i=0;
        while (!$this->ValidIncompleteGrille && (time()-$this->t0)<$this->TimeLimitCalcul) //tant qu'il n'y a pas une grille qui a été trouvé et que le temps de recherche <= 10secondes
        {  
          if (isset($this->CasesVidesCoordonneesX)) unset($this->CasesVidesCoordonneesX,$this->CasesVidesCoordonneesY,$this->TestGrilleSudoku,$this->IncompleteGrille,$this->XTabValeursPossible,$this->YTabValeursPossible,$this->TabValeursPossible);
          $this->create_grille_sudoku();  
        }
        
        if (!$this->ValidIncompleteGrille) //si la grille n'a pas été trouvé on recharge la page => pour eviter timeout
        {          
              $this->TimeOut=true;     
        }
    }
    
    function GetGrille()
    {
        $this->tirage = range(1,$this->max);
        $this->sudo=$this->SudoVide; $this->Grille();
        $this->GrillePleine=$this->sudo;
    }

    function NiveauDifficulte()
    {
      //$level=0 par defaut
      $statut=false;
      switch ($this->level)
      {
            case 0:
            //facile
            if ($this->LevelHard==0)
              $statut=true;
            break;
            
            case 1 :
              if($this->LevelHard>=20 && $this->LevelHard<=70 && $this->max>4)
              {
                $statut=true;
              }
              else if(($this->LevelHard==0 && $this->max==4) ||($this->LevelHard==0 && $this->max==16))
              {
                $statut=true;
              }
            break;
            
            case 2 :
              if($this->LevelHard>=100 && $this->max>4)
              {
                $statut=true;
              }
              else if(($this->LevelHard==0 && $this->max==4)||($this->LevelHard==0 && $this->max==16)) //moyen = difficile pour la grille 16*16 sinon temps de calcul trop grand
              {
                $statut=true;
              }
            break;
            
            default :
            exit ('Le niveau renseigné n\'existe pas');
            break;
      
      }
      return $statut;
    }
    
    function create_grille_sudoku()
    {
      // future raisonement : à chaque suppression de valeur, il faut verifier que celle-ci peut être retrouvée
      // Gain en rapidité ? non traitement plus long car solveur lancé à chaque suppression de valeur
      // A tester... 	
      //-------------------------------------------------------------------------------------------------------
    	
      $new_grille=$this->GrillePleine;
    	switch ($this->max)
    	{
    	 case 16 :
    	 if($this->level>=1)
    	   $NbrInconnus=115;
    	 else
    	     $NbrInconnus=65;
            break;
    	  case 14 :
    	      $NbrInconnus=90;
            break;
        case 12 :
            $NbrInconnus=73;
            break;
    	   case 10 :
            $NbrInconnus=56;
            break;
            
          case 9 :
          if($this->level>1)
          $NbrInconnus=47;
          else
            $NbrInconnus=44;
            break;
            
          case 8:
            $NbrInconnus=38;
            break;  
          case 6:
            $NbrInconnus=22;
            break;
          case 4:
          if($this->level==1)
          $NbrInconnus=10;
          else if($this->level==2)
          $NbrInconnus=11;
          else
          $NbrInconnus=6;
            break;
      }
      
          for ($i=0;$i<=$NbrInconnus;$i++)
        	{
        	     $X = mt_rand(0,$this->max-1); 
               $Y = mt_rand(0,$this->max-1); 
              
                //on verfit que ces coordonnées ne sont pas déjà utilisés
                 while($new_grille[$X][$Y]==0)
                { 
                  $X = mt_rand(0,$this->max-1); 
                  $Y = mt_rand(0,$this->max-1); 
                }
        	     $new_grille[$X][$Y]=0;
        	     $this->CasesVidesCoordonneesX[]=$X;
               $this->CasesVidesCoordonneesY[]=$Y;	
          }
        
        //il faut tester si la grille à une seule solution !
        $this->IncompleteGrille=$new_grille;
        $this->TestGrilleSudoku=$this->IncompleteGrille;
        
        $grillevalide=$this->CalculSolutionDeduction();
        
        $this->ValidIncompleteGrille=$grillevalide;
        
        if ($this->ValidIncompleteGrille)
        {
          $this->ValidIncompleteGrille=$this->NiveauDifficulte();
        }
        $this->CountSearch=0; 

      //par moment la grille n'est pas vailde, il y a une erreur algo de resolution non résolu encore. 
      //On teste donc si la grille résolu avec algo de resolution ne contient pas d'erreur
      /* TEMPORAIRE */
      $res=$this->Debug($this->TestGrilleSudoku);
      if($res>=1 && $this->ValidIncompleteGrille)
       $this->ValidIncompleteGrille=false;
          
       //echo    $res.'/'.$this->LevelHard.'<br/>';
       /* FIN */ 
        
        return $grillevalide;
    }

    function replace_by_symbol($Val,$transform=false)
    {
    $tab=$Val;
        // On remplace les numéro par des symbole (lettres)
        switch ($transform)
        {
          case 'symbole' :
          $tab=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P');
          break;
          
          case 'Mixte':
          //$tab=array(1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'A',10=>'B',11=>'C',12=>'D',13=>'E',14=>'F',15=>'G',16=>'H');
         // break;  
         //$tab=array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>'A',10=>'B',11=>'C',12=>'D',13=>'E',14=>'F',15=>'G',16=>'H');
          $tb=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P');
          $div=$this->max/2;  
			if(is_int($div))
			{
				  unset($tab);  
				  for($w=1;$w<=$div;$w++)
				  {
					$tab[$w]=$w;
				  }  
				  $k=1;  
				  for ($w=$div+1;$w<=$this->max;$w++)
				  {
					$tab[$w]=$tb[$k];
					$k++;
				  }
			}
			else return $Val;
				  break;   
				  
				  default :
				  return $Val;
				  
				}
				  return $tab[$Val];   
			}

function lineariser_grilles($grille)
{
	$grillebdd='';

	for($y=0;$y<$this->max;$y++)
        {
		
		for ($x=0;$x<$this->max;$x++)
		{
			//if($sudo->IncompleteGrille[$x][$y]=='')$grillebdd.='0';
			$grillebdd.=$grille[$x][$y];
			/*
			if($sudo->IncompleteGrille[$x][$y]!='' && $sudo->IncompleteGrille[$x][$y]!=0 )
			$grillebdd.=$this->replace_by_symbol($grille[$x][$y],$this->WithSymbol);*/
			if($x==($this->max-1) && $y==($this->max-1))
			{
			}
			else
			if($y<=($this->max-1))			
			$grillebdd.=',';
		}
		
	}
		return $grillebdd;
}

    // On dessine le sudoku
    function drawing($grille,$template)
    {
		//echo $this->max;
		//echo 'tetete';
		
		$classCSS='';
        //Utilisation du template !
        //echo 'test';
        $tpl = new template(); 
        for($y=0;$y<$this->max;$y++)
        {
          
          //on cadrille la grille
          if ($y%$this->NbCasesVZone==0)
					{
                $classCSS.='TopCase BorderRightNull BorderBottomNull'; 
							 if (($y<=1) || ($y<=($this->NbCasesVZone+1) && $y>=$this->NbCasesVZone) || ($y<=($this->max-$this->NbCasesVZone+1) && $y>=($this->max-$this->NbCasesVZone)) || (($this->max%$this->NbCasesVZone==0))) $classCSS.=' BorderLeft'; 
          }
          else 
					{
							$classCSS='BorderLeft BorderTop BorderRightNull BorderBottomNull'; 
							if ($y==$this->max-1){ $classCSS.=' BottomCase';$classCSS=str_replace('BorderBottom','',$classCSS);} 
					}

          for ($x=0;$x<$this->max;$x++)
					{
					  
					  if ($x%$this->NbCasesHZone==0) 
						{
						    $classCSSFinal=str_replace('BorderLeft','LeftCase',$classCSS);						
						}
						else 
						{
								if ($x==($this->max-1)){$classCSS.=' RightCase';$classCSS=str_replace('BorderRightNull','',$classCSS);}	
								 $classCSSFinal.=$classCSS;
						}
					
					  if ($x==0)
					  {
              $array['DIV']='<div style=\'margin-top:-3px;padding-top:0px;\'>';
              $array['/DIV']='';
            }
            else
            { 
              $array['DIV']='';
					  }
					  
					  if ($x==($this->max-1)) $array['/DIV']='</div>';
					   
					    $array['class']=$classCSSFinal;
              $array['coord']=$x.'_'.$y;
              
              if ($grille[$x][$y]!=0)
              {
                $array['Disabled']=' readonly=\'readonly\''; 
                $array['class']=$array['class'].' Disabled'; 
                $array['value']=$this->replace_by_symbol($grille[$x][$y],$this->WithSymbol);
              }
              else
              {
                $array['Disabled']='';
                $array['value']='';
                if (isset($_POST['case_'.$x.$y]))
                  $array['value']=$_POST['case_'.$x.$y]; //a revoir
              }
              
              $tpl->bloc('Cases',$array,$template);  
              
              $array['DIV']='';
              
              $classCSSFinal='';
          }
          $classCSS='';
       }


        return $tpl; 
    }

 function Correction($Values) //values=contient variables posts
 {
    //correction 
      /*
        On verifit que toutes les cases ont été remplies
        + Mise en forme du formulaire pour avoir la même structure que la variable sessions $_SESSION['GrilleSolution']
        + On compare les grilles à la solution
      */
      $nbr_erreurs=0;
      for ($y=0;$y<$this->max;$y++)
      {
          for ($x=0;$x<$this->max;$x++)
          {
              if (isset($Values['case_'.$x.'_'.$y])) //les cases remplies sont disabled donc variable post n'existe pas
              {
                  //On verifit que la case est remplie
                  if($Values['case_'.$x.'_'.$y]!='')
                  {
                      //on compare à la solution
                      if (!is_int(htmlentities($Values['case_'.$x.'_'.$y]))) $Values['case_'.$x.$y]=strtoupper(htmlentities($Values['case_'.$x.$y])); //si symbole et que l'utilisateur ecris en minuscule
                      if(htmlentities($Values['case_'.$x.'_'.$y])!=$this->replace_by_symbol($this->GrillePleine[$x][$y],$this->WithSymbol))
                      {
                          $nbr_erreurs++;
                      }
                  }
                  else
                  {
                      $nbr_erreurs++;
                  }
              }
          }
      }
      return $nbr_erreurs;
 }

function UnsetInconnus($x, $y)
{
    $NewArraX = array();
    $NewArraY = array();
    $NewTabValeursPossible = array();
    $NewXTabValeursPossible = array();
    $NewYTabValeursPossible = array();

    for ($i = 0; $i < count($this->CasesVidesCoordonneesX); $i++) {
        if (
            isset($this->CasesVidesCoordonneesX[$i]) &&
            isset($this->CasesVidesCoordonneesY[$i]) 
            
        ) {
            if ($this->CasesVidesCoordonneesX[$i] == $x && $this->CasesVidesCoordonneesY[$i] == $y) {
                // On ne conserve pas cette valeur
            } else {
                $NewArraX[] = $this->CasesVidesCoordonneesX[$i];
                $NewArraY[] = $this->CasesVidesCoordonneesY[$i];
				if(isset($this->TabValeursPossible[$i]) &&
            isset($this->XTabValeursPossible[$i]) &&
            isset($this->YTabValeursPossible[$i]))
			{
                $NewTabValeursPossible[] = $this->TabValeursPossible[$i];
                $NewXTabValeursPossible[] = $this->XTabValeursPossible[$i];
                $NewYTabValeursPossible[] = $this->YTabValeursPossible[$i];
            }
			}
        }
    }

    $this->CasesVidesCoordonneesX = $NewArraX;
    $this->CasesVidesCoordonneesY = $NewArraY;
    $this->TabValeursPossible = $NewTabValeursPossible;
    $this->XTabValeursPossible = $NewXTabValeursPossible;
    $this->YTabValeursPossible = $NewYTabValeursPossible;
}

function ValeursInterdites($x,$y)
 {   

      $val[]=0;
      //methode en croix !
      
      //ligne
      for ($j=0;$j<$this->max;$j++)
      {
          if ($this->TestGrilleSudoku[$x][$j]!=0)
          {
            if(!in_array($this->TestGrilleSudoku[$x][$j],$val))
              $val[]=$this->TestGrilleSudoku[$x][$j];      
          }
      }
      //colonne
      for ($i=0;$i<$this->max;$i++)
      {
        if ($this->TestGrilleSudoku[$i][$y]!=0)
        {
              //on verfit que la valeur interdite n'est pas déjà stoqué
              if(!in_array($this->TestGrilleSudoku[$i][$y],$val))
                $val[]=$this->TestGrilleSudoku[$i][$y];           
        }
      }
      while($x % $this->NbCasesHZone) $x--;
      while($y % $this->NbCasesVZone) $y--;
      for($i=$x; $i<$x+$this->NbCasesHZone; $i++) 
      {
        for($j=$y; $j<$y+$this->NbCasesVZone; $j++)
        {
          if(!in_array($this->TestGrilleSudoku[$i][$j],$val))
                $val[]=$this->TestGrilleSudoku[$i][$j];
        }
      }  
      //$val contient toutes les valeurs interdites  
      return $val;
 } 
  
 function DeductionParValeurInterdites($x,$y,$valsinterdits)
 {
    //$ValeurPossible=array(1,2,3,4,5,6,7,8,9 ...)
    //valeurs possible
	$val=array();
    for ($i=1;$i<=$this->max;$i++)
    {
        //on cherche si les valeur possibles est dans les valeurs interdites
        if(!in_array($i,$valsinterdits))
        {
            //cette valeur n'est pas interdite
              $val[]=$i;  
        }
    }
  return $val;
 }
 
function DeductionSolitaireNu($X, $Y)
{
    $find = false;

    for ($u = 0; $u < $this->max; $u++) {
        $keys = array_keys($Y, $u);
        $TabValeursPossibles = array(); // Initialisation ici

        for ($w = 0; $w < count($keys); $w++) {
            $TabValeursPossibles[] = $this->TabValeursPossible[$keys[$w]];
            $y[] = $Y[$keys[$w]];
            $x[] = $X[$keys[$w]];
        }

        for ($n = 0; $n < count($TabValeursPossibles); $n++) {
            $count = 0;
            $ValsATester = $TabValeursPossibles[$n];
            $x_ = $x[$n];
            $y_ = $y[$n];

            foreach ($ValsATester as $valatest) {
                foreach ($TabValeursPossibles as $j => $TabValeursPossiblesAComparer) {
                    if (count($TabValeursPossiblesAComparer) > 0 && in_array($valatest, $TabValeursPossiblesAComparer) && $j != $n) {
                        $count++;
                    }
                }

                if ($count == 0) {
                    if ($this->TestGrilleSudoku[$x_][$y_] == 0) {
                        $this->TestGrilleSudoku[$x_][$y_] = $valatest;
                        $this->LevelHard += 10;
                        $find = true;
                        $this->UnsetInconnus($x_, $y_);
                        break(3);
                    }
                }
            }
        }

        unset($TabValeursPossibles, $y, $x);
    }

    return $find;
}
 
 function SearchPairesNues($TabValeursPossibles,$x,$y)
 {
  
  $find=FALSE;
    for ($n=0;$n<count($TabValeursPossibles);$n++)
    {     
        $ValsATester=$TabValeursPossibles[$n]; 
        $x_=$x[$n];
        $y_=$y[$n];
         
        if(count($ValsATester)==3)   
        for ($i=0;$i<count($ValsATester);$i++)
        {
                //groupes de paires à chercher : [0][1];[0][2];[1][2];  
                switch ($i)
                {
                    case 0 :
                      $valatest[0]=$ValsATester[0];
                      $res=$ValsATester[2];
                      $valatest[1]=$ValsATester[1];
                    break;
                              
                    case 1 :
                      $valatest[0]=$ValsATester[0];
                      $res=$ValsATester[1];
                      $valatest[1]=$ValsATester[2];
                    break;
                              
                    case 2:
                      $valatest[0]=$ValsATester[1];
                      $res=$ValsATester[0];
                      $valatest[1]=$ValsATester[2];
                    break;
                }
                $count=0;
                for($j=0;$j<count($TabValeursPossibles);$j++)
                { 
                    $TabValeursPossiblesAComparer=$TabValeursPossibles[$j];
                    //il faut trouver les paires identiques dans la ligne
                    if(count($TabValeursPossiblesAComparer)==2 && !in_array($res,$TabValeursPossiblesAComparer) && in_array($valatest[0],$TabValeursPossiblesAComparer) && in_array($valatest[1],$TabValeursPossiblesAComparer) && $j!=$n)
                    {
                       $count++;  
                    }   
                }    
                
                if($count==2)
                {   
                     $this->TestGrilleSudoku[$x_][$y_]=$res;
                          
                      // on a une valeure unique debug
                      //echo 'X='.$x_.' : Y='.$y_.'-->'.$res."<br />";
                      $this->LevelHard=$this->LevelHard+60;
                      $find=true;
                      $this->UnsetInconnus($x_,$y_);
                      break(2);
                               
                }                                   
                
      }    
  } 
  return $find;
 }
 function SearchPairesNuesCache($TabValeursPossibles,$x,$y)
 {
  
  $find=FALSE;
    for ($n=0;$n<count($TabValeursPossibles);$n++)
    {     
        $ValsATester=$TabValeursPossibles[$n];
        $nbrValeurs=count($ValsATester);   
        
            //groupes de paires à chercher : [0][1];[0][2];[0][3];[0][4];[0][5];[0][6];[0][7];[1][2];[1][3];...;[2][3]
            if($nbrValeurs>=2)
            for($i=0;$i<$nbrValeurs-1;$i++)
            {
                 
                $c=$i+1; 
                //création des groupes de paires
                for($j=$c;$j<$nbrValeurs;$j++)
                {   
                    $paire[0]=$ValsATester[$i];
                    if(isset($ValsATester[$j]))
                      $paire[1]=$ValsATester[$j];
                    
                    //on cherche cette paire dans toutes les cases
                    $count=0;
                    if(count($paire)==2)
                    for($z=0;$z<count($TabValeursPossibles);$z++)
                    { 
                      
                      $TabValeursPossiblesAComparer=$TabValeursPossibles[$z];
                      //il faut trouver les paires identiques dans la ligne
                      if(count($TabValeursPossiblesAComparer)>0)
                      if (in_array($paire[0],$TabValeursPossiblesAComparer) && in_array($paire[1],$TabValeursPossiblesAComparer))
                      {
                        $positionx[]=$x[$z];
                        $positiony[]=$y[$z];
                        $count++;
                      }  
                      else if(in_array($paire[0],$TabValeursPossiblesAComparer) || in_array($paire[1],$TabValeursPossiblesAComparer))
                      {
                        $count=3;//si une des 2 valeurs est présente dans un tableau ça annule la paire nue cachée
                        break;
                      }
                      if($count>2) break; 
                    }
                    if($count==2)
                    {
                        
                        $Countmaj=0;
                        $update=false;
                        //il faut chercher index 
                        for($w=0;$w<count($this->TabValeursPossible);$w++)
                        {
                          if(($this->YTabValeursPossible[$w]==$positiony[0] && $this->XTabValeursPossible[$w]==$positionx[0]) || ($this->YTabValeursPossible[$w]==$positiony[1] && $this->XTabValeursPossible[$w]==$positionx[1]))
                          {
                              if(count($this->TabValeursPossible[$w])>2){$update=true;}
                              $this->TabValeursPossible[$w]=$paire;
                              $Countmaj++;
                          }
                          if($Countmaj==2 && $update){$find=true;$this->LevelHard=$this->LevelHard+150;break;} 
                        }
                        break(3);
                    }
                    else
                    {
                      unset($positiony,$positionx,$paire);
                    }    
                }  
                //on doit enregistre la paire cherché pour ne pas lancer une nouvelle recherche avec les même valeurs
            }
  } 
  return $find;
 }
    
 function SelectValues($X, $Y, $type = 'x', $Methode = 'PairesNues')
{
    $find = false;
    $TabValeursPossibles = array(); // Initialisation ici

    if ($type == 'x' || $type == 'y') {
        for ($u = 0; $u < $this->max; $u++) {
            switch ($type) {
                case 'x': // colonne
                    $keys = array_keys($Y, $u);
                    break;
                case 'y': // ligne
                    $keys = array_keys($X, $u);
                    break;
            }

            $TabValeursPossibles = array(); // Réinitialisation à chaque itération

            foreach ($keys as $key) {
                $TabValeursPossibles[] = $this->TabValeursPossible[$key];
                $y[] = $Y[$key];
                $x[] = $X[$key];
            }

            switch ($Methode) {
                case 'PairesNues':
                    $find = $this->SearchPairesNues($TabValeursPossibles, $X, $Y);
                    if ($find) {
                        break 2;
                    }
                    break;
                case 'PairesNuesCache':
                    $find = $this->SearchPairesNuesCache($TabValeursPossibles, $X, $Y);
                    if ($find) {
                        break 2;
                    }
                    break;
            }

            unset($y, $x);
        }
    } elseif ($type == 'xy') {
        // ... (Aucun changement nécessaire ici)
    }

    return $find;
}

 function CalculSolutionDeduction()
 {
    /* Solveur de grille par déduction */
        $NbrInconnus=count($this->CasesVidesCoordonneesX);
        $search=true;
        $ALLFind=false;
    
        if ($NbrInconnus==0)
        {
          $ALLFind=true;
          $search=false;
        }
        else
        {
            $this->LevelHard=0;
            
            while ($search)
            {   
                    $NbrInconnus=count($this->CasesVidesCoordonneesX);
                    $find=array();
                    for($i=0;$i<$NbrInconnus;$i++)
                    {
                      //liste des valeurs interdites
                      if($this->TestGrilleSudoku[$this->CasesVidesCoordonneesX[$i]][$this->CasesVidesCoordonneesY[$i]]!=0)
                      {
                        //la case est déjà remplie
                        $find[]=2;
                      }
                      else
                      {
                        $res=$this->ValeursInterdites($this->CasesVidesCoordonneesX[$i],$this->CasesVidesCoordonneesY[$i]);

                          $ResDeductionParValeurInterdites=$this->DeductionParValeurInterdites($this->CasesVidesCoordonneesX[$i],$this->CasesVidesCoordonneesY[$i],$res);
                          
                          if (count($ResDeductionParValeurInterdites)==1)
                          {
                            
                            $this->TestGrilleSudoku[$this->CasesVidesCoordonneesX[$i]][$this->CasesVidesCoordonneesY[$i]]=$ResDeductionParValeurInterdites[0];
                            
                            $find[]=1; 
  
                            //on peut supprimer coordonnées de cette inconnue
                            $this->UnsetInconnus($this->CasesVidesCoordonneesX[$i],$this->CasesVidesCoordonneesY[$i]);
                            $NbrInconnus--;
                            $i--;
                          }
                          else
                          {
                            $find[]=0;
                              $this->TabValeursPossible[]=$ResDeductionParValeurInterdites; //contient l'ensemble des valeurs possibles pour chaque cellule
                              ////liste des coordonées
                              $this->XTabValeursPossible[]=$this->CasesVidesCoordonneesX[$i];
                              $this->YTabValeursPossible[]=$this->CasesVidesCoordonneesY[$i];
                            
                          }
                        }
                      }
                    if (!in_array(0,$find))
                    {
                      $ALLFind=true; // toutes les valeurs ont été trouvé sur cette boucle
                      $search=false;
                    }
                    else
                    {
                      if (in_array(1,$find)) // au moin une valeur a été trouvée, on continue la recherche
                      {
                        $search=true;
                      
                      }
                      else //aucune valeur n'a été trouvé sur la boucle
                      {
                        $search=false; 
                        $ALLFind=false;
                        
                        
                        // difficulté supperieur
                        // Recherche par deduction toujours mais methode supplémentaire
                        $this->LevelHard++;
 
                        //Algo de resolution -->raisonement humain cas par cas
                        $search=$this->DeductionSolitaireNu($this->XTabValeursPossible,$this->YTabValeursPossible);  

                          if(!$search && $this->level>=1) $search=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'x','PairesNues'); //vertical
                          if(!$search && $this->level>=1) $search=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'y','PairesNues'); //horizontale
                          if(!$search && $this->level>=1) $search=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'xy','PairesNues') ; //zone    
 
                          //$this->TabValeursPossible contient toutes les valeurs possibles de chaque cellules
                          if(!$search && $this->level>=2)
                          {
                              //maj $this->TabValeursPossible                              
                              $Newsearch=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'xy','PairesNuesCache');
                              if (!$Newsearch) $Newsearch=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'x','PairesNuesCache');
                              if (!$Newsearch) $Newsearch=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'y','PairesNuesCache');  
                              
                              
                              if($Newsearch) $search=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'xy','PairesNues'); //vertical
                              if(!$search && $Newsearch) $search=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'x','PairesNues'); //horizontale
                              if(!$search && $Newsearch) $search=$this->SelectValues($this->XTabValeursPossible,$this->YTabValeursPossible,'y','PairesNues') ; //zone    
 
                          }
 
 
                      }
                    } //si false aucune valeur deduite dans la boucle on peut arrêter, la grille ne peut pas être resolu par deduction
                    //est ce que toutes les valeurs ont été trouvées ? 
                 unset ($find,$this->TabValeursPossible,$this->XTabValeursPossible,$this->YTabValeursPossible);
            }  
        }

        return $ALLFind; 
 }

  function Debug($Values) //values=contient variables posts
 {
    //correction 

      $nbr_erreurs=0;
      for ($y=0;$y<$this->max;$y++)
      {
          for ($x=0;$x<$this->max;$x++)
          {
              
                  //On verifit que la case est remplie
                 
                  
                      //on compare à la solution
                      if($Values[$x][$y]!=$this->GrillePleine[$x][$y])
                      {
                          $nbr_erreurs++;
                      }    
          }
      }
      return $nbr_erreurs;
 }
}
