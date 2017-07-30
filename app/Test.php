<?php

namespace App;

use Slim\Http\Interfaces\RequestInterface as Request;
use Slim\Http\Interfaces\ResponseInterface as Response;

use Exception;


class Test {

    public function __construct( array $container = [] )
    {
        
    }

    public function hello( Request $request, Response $response, $page = 1, $page2 = 2 )
    {
        $data = [];
        $data['title'] = 'My title';

        return render('page', $data);
    }

    public function postHello( Request $request, Response $response, $page, $page2 )
    {
        $firstname = $request->input('firstname', 'default');
        $lastname = $request->input('lastname', 'default');


        $str = __('hello').', '.nohtml($firstname).' '.nohtml($lastname).' !';
        $str .= '('.$page.$page2.')';

    
        return write($response, $str);
    }

    public function __invoke( Request $request, Response $response )
    {
        return 'message: ok';
    }

    public function upload( Request $request, Response $response )
    {
        if( $request->isPost() )
        {
            $files = $request->getUploadedFiles();

            $example1 = $files['example1'];
            $example1_file = array_shift($example1);

            $str = 'result of single upload 1 : <br>'.print_r($example1_file, true);

            $example2 = $files['example2'];

            $str .= 'result of multiple 2 : <br>'.print_r($example2, true);

            return $str;
        }

        return render('upload');
    }

    public function throwexception()
    {
        throw new Exception('oh oh!');
    }


}