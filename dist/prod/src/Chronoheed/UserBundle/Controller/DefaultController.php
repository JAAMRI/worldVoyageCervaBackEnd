<?php

namespace Chronoheed\UserBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Put;



class DefaultController extends Controller
{
    /**
     * @Post("api/admin/users")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function newUserAction(Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $user_data = $req_data['user'];

        //Check if user exists already (if username exists)
        if(isset($user_data['username'])) {
            if ($this->doesUserExist(array("username" => $user_data['username']))) {
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => "User already exists"
                ], 409);
            }
        }//End if

        //Check if user exists already (if email exists)
        if(isset($user_data['email'])) {
            if ($this->doesUserExist(array("email" => $user_data['email']))) {
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => "User already exists"
                ], 409);
            }
        }//End if

        return $this->createUser($user_data);
    }

    public function doesUserExist($search_arr)
    {
        if($user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy($search_arr) )
        {
            return $user;
        }
        return false;
    }
    
    private function createUser($user_data){
        try {
            //Validate data and insert new User if data is valid
            $user = new User();
            $form = $this->createForm(new UserType(), $user);

            $form->submit($user_data);

            if ($form->isValid()) {
                //Set the user roles
                if(isset($user_data['roles'])){
                        $user->setRoles( $user_data['roles'] );
                }
                //Encrypt password
                $encryptedPassword = password_hash( $user_data['password'] , PASSWORD_BCRYPT);
                $user->setPassword($encryptedPassword);
                //Set enabled to true
                $user->setEnabled(true);
                try {
                    //Insert new user
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($user);
                    $em->flush();

                    return array("userId" => $user->getId());
                }catch (PDOException $e){
                    return new JsonResponse([
                        'error' => 'bad_request', 'error_description' => $e->getMessage()
                    ], $e->getCode());
                }
            }
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => $form->getErrorsAsString()
            ], 400);


        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => "Unknown error"
            ], 500);
        }
    }


    /**
     * @Get("api/admin/users/{user_id}")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function returnUserAction($user_id)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->createQueryBuilder('u')
            ->select( array( 'u.id', 'u.title', 'u.firstname', 'u.lastname', 'u.isActive', 'u.username', 'u.email', 'u.phone', 'u.color', 'u.roles', 'u.profileimg' ) )
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getOneOrNullResult();

        if($user){
            return $user;
        }else{
            return new JsonResponse([
                'error' => 'not_found', 'error_description' => "User not found"
            ], 404);
        }
    }
    
    /**
     * @Put("api/admin/users/{user_id}")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function updateUserFileAction($user_id, Request $request)
    {
        try {
            $req_data = json_decode($request->getContent(), true);
            $user_data = $req_data['user'];

            $em = $this->getDoctrine()->getManager();

            //Check if user exists
            $user = $em->find("AppBundle:User", $user_id);

            if (!$user) {//This user doesn't exist
                return new JsonResponse([
                    'error' => 'bad_not_found', 'error_description' => "User not found"
                ], 404);
            } else {

                $form = $this->createForm(new UserType(), $user);
                $form->submit($user_data);

                if ($form->isValid()) {
                    try {
                        //Check if password is also to be updated
                        if($this->isPasswordReadyToBeUpdated($user_data)){
                            //Encrypt password
                            $encryptedPassword = password_hash( $user_data['password'] , PASSWORD_BCRYPT);
                            $user->setPassword($encryptedPassword);
                        }

                        //Set the user roles
                        if(isset($user_data['roles'])){
                            $user->setRoles( $user_data['roles'] );
                        }

                        $em->persist($user);
                        $em->flush();
                        return $user;
                    } catch (PDOException $e) {
                        return new JsonResponse([
                            'error' => 'bad_request', 'error_description' => $e->getMessage()
                        ], 400);
                    }
                }
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => $form->getErrorsAsString()
                ], 400);
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'server_error', 'error_description' => $e->getMessage()
            ], 500);
        }

    }

    public function isPasswordReadyToBeUpdated($user_data){
        if( isset( $user_data['password']) && isset( $user_data['password2']) ){
            if( ($user_data['password'] == $user_data['password2']) &&  ($user_data['password'] != "" && $user_data['password'] != null) ){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @Get("api/admin/users")
     * @return array|JsonResponse
     * @View
     */
    public function returnUsersAction()
    {
        $users = $this->getDoctrine()->getRepository("AppBundle:User")->createQueryBuilder('u')
        ->select( array( 'u.id as DT_RowID', 'u.firstname', 'u.lastname', 'u.isActive', 'u.username', 'u.email', 'u.phone', 'u.color' ) )
        ->getQuery()
        ->execute();
        return array("recordsTotal" => count($users), "data" => $users);
    }

    /**
     * @Post("api/admin/users/{user_id}/photo")
     * @param Request $request
     * @param user_id
     *
     * @return array|JsonResponse
     * @View
     */
    public function newUserPhotoAction(Request $request, $user_id)
    {
        try{

            $user = $this->getDoctrine()->getManager()->find("AppBundle:User", $user_id);
            if( $user) {
                $oldPhoto = "";
                if($user->getProfileimg() != "") $oldPhoto = $user->getProfileimg();

                $image_file = $request->files->get("userPhotoFileInput");

                //Check if file  exists
                if ((!$image_file instanceof UploadedFile))
                    throw new FileNotFoundException('userPhotoFileInput');

                // Check if the file's format is in the list of allowed image formats.
                $file_extension = strtolower($image_file->getClientOriginalExtension());
                if (!in_array($file_extension, $this->getParameter('allowed_image_formats')))
                    throw new \InvalidArgumentException(sprintf('Files of type \'' . $file_extension . '\' are not allowed.', $image_file->getClientMimeType()));

                //Renaming: Uploaded file new name
                $file_name = sprintf('%s-%s-%s-%s.%s', date('Y'), date('m'), date('d'), uniqid(), $file_extension);

                //Move file to the uploads folder
                $upload_folder_absolute_path = $this->get('kernel')->getRootDir() . '/../web/' . $this->getParameter('uploads_folder_web_path');
                $image_file->move($upload_folder_absolute_path, $file_name);

                //Set File web path
                $image_file_web_path = $this->getParameter('uploads_folder_web_path') . "/" . $file_name;

                //Crop image
                $imagemanagerResponse = $this->get("liip_imagine.controller")->filterAction($request, $image_file_web_path, 'user_photo');

                $imagePath = $imagemanagerResponse->getTargetUrl();

                //Store file in S3
                //**************************************************************

                //Save to db
                $user->setProfileimg($imagePath);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                //Return link to client
                return ["photoPath" => $imagePath ];
            }//end if
            //Error the user doesn't exist
            return new JsonResponse([
                'error' => 'not_found', 'error_description' => "User does not exist"
            ], 400);
        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => $e->getMessage()
            ], 500);
        }
    }




}
