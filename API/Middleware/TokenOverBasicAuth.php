<?php

namespace API\Middleware;
use Service\AuthToken;
use Utils\StandardResponse;
use Utils\AuthenticationToken;


class TokenOverBasicAuth extends \Slim\Middleware
{
    
    
    public function __construct(array $config = array())
    {
       
        if (!isset($this->app)) {
            $this->app = \Slim\Slim::getInstance();
        }
     
    }

    public function call()
     {

        global $app;
        $req = $app->request();
        $res = $this->app->response();

        $authToken = $req->headers->get('HANDSTEL-AUTH-USER');

        $url = $req->getPath();
        $action =  trim(end((explode("/",$url))));

        if (($action == "login") || ($action == "signup") || ($action == "getSchoolList"))
             $this->next->call();
        else if(!empty($authToken) && $this->verify($authToken))
             $this->next->call();
        else
        {
        $res->setStatus(403);   
        $error = array(
        'code' => ENTRY_FORBIDDEN,
        'message' => ENTRY_FORBIDDEN_MESSAGE);
         echo json_encode(new StandardResponse(FAILURE,403,$error));
         return json_encode(new StandardResponse(FAILURE,403,$error));
        }

    }

    /**
     * Check passed auth token
     *
     * @param string $token
     * @return boolean
     */
    //error in offset
    protected function verify($auth_token)
    {
        $authToken = new AuthToken;
        $tokens = explode(":",$auth_token);
        $token = trim($tokens[0]);
        $user_id = trim($tokens[1]);
        $user = $authToken->validateToken($token,$user_id);
        if(empty($user))
        {
            return false;
        }
        else
        {   
            AuthenticationToken::setToken($user);
            
        }
        return true;
    }
}

?>


