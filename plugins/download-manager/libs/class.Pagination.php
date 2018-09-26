<?php
namespace WPDM\libs;

class Pagination{



                /*Default values*/

                var $total_pages = -1;//items

                var $limit = null;

                var $target = ""; 

                var $page = 1;

                var $adjacents = 2;

                var $showCounter = false;

                var $className = "pagination";

                var $parameterName = "page";

                var $urlF = false;//urlFriendly

                var $uriTPL = '';



                /*Buttons next and previous*/

                var $nextT = "Next";

                var $nextI = ""; //&#9658;

                var $prevT = "Previous";

                var $prevI = ""; //&#9668;



                /*****/

                var $calculate = false;


                function __construct(){
                    $this->prevT = __('Previous','download-manager');
                    $this->nextT = __('Next','download-manager');
                }

                

                #Total items

                function items($value){$this->total_pages = (int) $value;}

                

                #how many items to show per page

                function limit($value){$this->limit = (int) $value;}

                

                #Page to sent the page value

                function target($value){$this->target = $value;}

                

                function urlTemplate($value){$this->uriTPL = $value;}

                

                #Current page

                function currentPage($value){$this->page = (int) $value;}

                

                #How many adjacent pages should be shown on each side of the current page?

                function adjacents($value){$this->adjacents = (int) $value;}

                

                #show counter?

                function showCounter($value=""){$this->showCounter=($value===true)?true:false;}



                #to change the class name of the pagination div

                function changeClass($value=""){$this->className=$value;}



                function nextLabel($value){$this->nextT = $value;}

                function nextIcon($value){$this->nextI = $value;}

                function prevLabel($value){$this->prevT = $value;}

                function prevIcon($value){$this->prevI = $value;}



                #to change the class name of the pagination div

                function parameterName($value=""){$this->parameterName=$value;}



                #to change urlFriendly

                function urlFriendly($value="%"){

                                if(preg_match('^ *$',$value)){

                                                $this->urlF=false;

                                                return false;

                                        }

                                $this->urlF=$value;

                        }

                

                var $pagination;



                function pagination(){}

                function show(){

                                if(!$this->calculate)

                                        if($this->calculate())

                                                return "<div class='text-center'><ul  class=\"pagination pagination-centered\">$this->pagination</ul></div>\n";

                        }

                function get_pagenum_link($id){

                                /*

                                if(strpos($this->target,'?')===false)

                                                if($this->urlF)

                                                                return str_replace($this->urlF,$id,$this->target);

                                                        else

                                                                return "$this->target?$this->parameterName=$id";

                                        else

                                                return "$this->target&$this->parameterName=$id";

                               */

                               return str_replace('[%PAGENO%]',$id,$this->uriTPL);

                        }

                

                function calculate(){

                                $this->pagination = "";

                                $this->calculate == true;

                                $error = false;

                                if($this->urlF and $this->urlF != '%' and strpos($this->target,$this->urlF)===false){

                                                //Es necesario especificar el comodin para sustituir

                                                echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";

                                                $error = true;

                                        }elseif($this->urlF and $this->urlF == '%' and strpos($this->target,$this->urlF)===false){

                                                echo "Es necesario especificar en el target el comodin % para sustituir el n?mero de p?gina<br />";

                                                $error = true;

                                        }



                                if($this->total_pages < 0){

                                                echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";

                                                $error = true;

                                        }

                                if($this->limit == null){

                                                echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";

                                                $error = true;

                                        }

                                if($error)return false;

                                

                                $n = trim($this->nextT.' '.$this->nextI);

                                $p = trim($this->prevI.' '.$this->prevT);

                                

                                /* Setup vars for query. */

                                if($this->page) 

                                        $start = ($this->page - 1) * $this->limit;             //first item to display on this page

                                else

                                        $start = 0;                                //if no page var is given, set start to 0

                        

                                /* Setup page vars for display. */

                                $prev = $this->page - 1;                            //previous page is page - 1

                                $next = $this->page + 1;                            //next page is page + 1

                                $lastpage = ceil($this->total_pages/$this->limit);        //lastpage is = total pages / items per page, rounded up.

                                $lpm1 = $lastpage - 1;                        //last page minus 1

                                

                                /* 

                                        Now we apply our rules and draw the pagination object. 

                                        We're actually saving the code to a variable in case we want to draw it more than once.

                                */

                                

                                if($lastpage > 1){

                                                if($this->page){

                                                                //anterior button

                                                                if($this->page > 1)

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($prev)."\" class=\"prev\" rel='Com'>$p</a></li>";

                                                                        else

                                                                                $this->pagination .= "<li><a class=\"disabled\">$p</a></li>";

                                                        }

                                                //pages        

                                                if ($lastpage < 7 + ($this->adjacents * 2)){//not enough pages to bother breaking it up

                                                                for ($counter = 1; $counter <= $lastpage; $counter++){

                                                                                if ($counter == $this->page)

                                                                                                $this->pagination .= "<li class=\"active\"><a class=\"current\">$counter</a></li>";

                                                                                        else

                                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($counter)."\" rel='Com'>$counter</a></li>";

                                                                        }

                                                        }

                                                elseif($lastpage > 5 + ($this->adjacents * 2)){//enough pages to hide some

                                                                //close to beginning; only hide later pages

                                                                if($this->page < 1 + ($this->adjacents * 2)){

                                                                                for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++){

                                                                                                if ($counter == $this->page)

                                                                                                                $this->pagination .= "<li class=\"active\"><a class=\"current\">$counter</a></li>";

                                                                                                        else

                                                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($counter)."\" rel='Com'>$counter</a></li>";

                                                                                        }

                                                                                $this->pagination .= "<li><a class='pagi-gap'>...</a></li>";

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($lpm1)."\" rel='Com'>$lpm1</a></li>";

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($lastpage)."\" rel='Com'>$lastpage</a></li>";

                                                                        }

                                                                //in middle; hide some front and some back

                                                                elseif($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)){

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link(1)."\" rel='Com'>1</a></li>";

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link(2)."\" rel='Com'>2</a></li>";

                                                                                $this->pagination .= "<li><a class='pagi-gap'>...</a></li>";

                                                                                for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)

                                                                                        if ($counter == $this->page)

                                                                                                        $this->pagination .= "<li class=\"active\"><a class=\"current\">$counter</a></li>";

                                                                                                else

                                                                                                        $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($counter)."\" rel='Com'>$counter</a></li>";

                                                                                $this->pagination .= "<li><a class='pagi-gap'>...</a></li>";

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($lpm1)."\" rel='Com'>$lpm1</a></li>";

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($lastpage)."\" rel='Com'>$lastpage</a></li>";

                                                                        }

                                                                //close to end; only hide early pages

                                                                else{

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link(1)."\" rel='Com'>1</a></li>";

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link(2)."\" rel='Com'>2</a></li>";

                                                                                $this->pagination .= "<li><a class='pagi-gap'>...</a></li>";

                                                                                for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)

                                                                                        if ($counter == $this->page)

                                                                                                        $this->pagination .= "<li class=\"active\"><a class=\"current\">$counter</a></li>";

                                                                                                else

                                                                                                        $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($counter)."\" rel='Com'>$counter</a></li>";

                                                                        }

                                                        }

                                                if($this->page){

                                                                //siguiente button

                                                                if ($this->page < $counter - 1)

                                                                                $this->pagination .= "<li><a href=\"".$this->get_pagenum_link($next)."\" class=\"next\" rel='Com'>$n</a></li>";

                                                                        else

                                                                                $this->pagination .= "<li><a class=\"disabled\">$n</a></li>";

                                                                        if($this->showCounter)$this->pagination .= "<div class=\"pagination_data\">($this->total_pages Pages)</div>";

                                                        }

                                        }



                                return true;

                        }

        }

