<?php

namespace Controllers;

use Resources,
    Models,
    Libraries;

class Template extends Resources\Controller {

    public $template = 'layout';
    public $website;
    
    function __construct() {
        $this->website = Resources\Config::website();
        parent::__construct();
    }
    
    public function render( $tpl, $data=array() ) {
        $styles = array(
            $this->website['base_url'] . 'statics/bootstrap/css/bootstrap.css' => '',
            $this->website['base_url'] . 'statics/bootstrap/css/bootstrap-responsive.css'=>'',
            $this->website['base_url'] . 'statics/css/style.css'=>'',
        );
        
        $data['styles'] = $styles;
        $data['scripts'] = array();
        
        if($tpl){
            $data['content'] = $this->output($tpl,$data,TRUE);
        }
        
        
        $this->output($this->template, $data);
        
    }

}