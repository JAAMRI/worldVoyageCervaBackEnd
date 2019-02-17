<?php

namespace Zfzmyrjlmj\AuthenticationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;

class AuthController extends Controller
{
    /**
     * @Post("auth/login")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function loginAction(Request $request)
    {
        $credentials = json_decode($request->getContent(), true);

        //Make sure this user exists
        if($oauth = $this->oauthlogin($credentials)){

            $user = $this->get('fos_user.user_manager')->loadUserByUsername($credentials['username']);
            $oauth['scope'] = $user->getRoles();

            return array("user" => $user, "auth" => $oauth);

        }else{//User not found
            return new JsonResponse([
                'error' => 'user_not_found', 'error_description' => "User not found"
            ], 404);
        }
    }

    /**
     * Oauth Password Grant type
     *
     * @param $credentials
     * @return array|mixed|null
     *
     */
    private function oauthlogin($credentials){
        try {
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->getParameter('zfzmyrjlmj_api_host') . "/oauth/v2/token?grant_type=password&client_id=" . $this->getParameter('oauth_client_id') . "&client_secret=" . $this->getParameter('oauth_secret') . "&username=" . $credentials['username'] . "&password=" . $credentials['password'],
                CURLOPT_USERAGENT => 'Get an Access Token for Yara clinica User'
            ));
            // Send the request & save response to $resp
            $resp = curl_exec($curl);
            // Close request to clear up some resources
            curl_close($curl);
            $oauthResponse = json_decode($resp, true);
            if(isset($oauthResponse['access_token'])){//Make sure access_token is retrieved
                return $oauthResponse;
            }
            return null;
        }catch (\Exception $e){
            return array("error" => $e->getMessage());
        }
    }


    /**
     * @Get("api/users/me")
     * @return array|JsonResponse
     * @View
     */
    public function userprofileAction()
    {
        if ($user = $this->get('security.token_storage')->getToken()->getUser()) {
            return $user;
        }
        return new JsonResponse([
            'error' => 'bad_request', 'error_description' => "Not able to retrieve the user with this token"
        ], 400);
    }


    private function generateOauthClient(){
//        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
//        $client = $clientManager->createClient();
//        $client->setRedirectUris(array('http://www.example.com'));
//        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'password', 'client_credentials'));
//        $clientManager->updateClient($client);
//
//        return array("client" => $client->getPublicId(), "Secret" => $client->getSecret() );
    }
}
