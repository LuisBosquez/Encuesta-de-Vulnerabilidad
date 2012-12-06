<?php
    class templateParser {

        var $output;


        function templateParser($templateFile){


        if($templateFile == '')$templateFile = './templates/main.tp';

   		(file_exists($templateFile))?$this->output=file_get_contents($templateFile):die('Error:Template file '.$templateFile.' not found');

        }

        function parseTemplate($tags=array()){

              if(count($tags)>0){

                   foreach($tags as $tag=>$data){


						$data = utf8_decode($data);

                        $data=(strlen($data)<100&&(file_exists(trim($data))))?$this->parseFile($data):$data;
                        if($tag=='mensaje.error' && $data!="")$this->output=str_replace('{'.$tag.'}','<p class="alert">'.$data.'</p>',$this->output);
                        else if($tag=='mensaje.nota' && $data!="")$this->output=str_replace('{'.$tag.'}','<p class="info">'.$data.'</p>',$this->output);
                        else if($tag=='mensaje.demo' && $data!="")$this->output=str_replace('{'.$tag.'}','<p class="info">'.$data.'</p>',$this->output);
                        else $this->output=str_replace('{'.$tag.'}',$data,$this->output);


                   }
              }

              else {

                   die('Error: No tags were provided for replacement');

              }

        }

        function parseFile($file){

              ob_start();

              include(trim($file));


              $content=ob_get_contents();

              ob_end_clean();

              return $content;

        }

        function display(){




              return ((($this->output)));
              //return ($this->output);

        }






    }

?>