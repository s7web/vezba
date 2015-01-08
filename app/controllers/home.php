<?php

class Home extends Controller {

    public function index()
    {
        $this->view( 'home/index.html', array() );
    }

    public function about()
    {
        $this->view( 'home/about.html', array() );
    }

    public function services( $request )
    {

        $this->view( 'home/services.html', array() );
    }

    public function downloads()
    {

        $this->view( 'home/downloads.html', array() );
    }

    public function projects()
    {

        $this->view( 'home/projects.html', array() );
    }

    public function contact( $request )
    {

        $this->view( 'home/contact.html', array( 'csrf_protect' => $request->request->csrf_protect()) );
    }

    public function changeLang( $request )
    {

        $lang = $request->request->requestGetParam( 'lang' );
        $_SESSION[ 'lang' ] = filter_var( $lang, FILTER_SANITIZE_STRING );


        return \Response\Response::redirect_back();


    }
}