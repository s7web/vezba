<?php
class Bla {
    public function menu(){
        $request_uri = $_SERVER['REQUEST_URI'];

        $url = array(
            'Home' => '/public/home/cms',
            'About us' => '/public/home/about-us',
            'Services' => '/public/home/services',
            'Downloads'=> '/public/home/downloads',
            'Projects' => '/public/home/projects',
            'Contact'  => '/public/home/contact'
        );
        echo '<div class="masthead">
        <h3 class="text-muted">S7design PHP framework</h3>
        <nav>
            <ul class="nav nav-justified">';
        foreach ( $url as $key => $value){
            $active = ( $request_uri === $value ) ? 'class="active"' : '';
            echo '<li '.$active.' ><a href="'.$value.'">'.$key.'</a></li>';
        }
            echo '</ul>
        </nav>
    </div>';
    }
}