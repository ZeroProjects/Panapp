<?php
namespace Controllers;
use Resources, Models;

class Home extends Template
{    
    public function index()
    {    
        $data['title'] = 'Hello world!';
        $this->render('Home/main', $data);
    }
}