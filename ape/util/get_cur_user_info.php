<?php
/**
 * Function that returns an array of current user information, includings:
 * - userId
 * - userType
 * - userSession
 * - userFname
 * - userLname
 * - userEmail
 * @author: Tu Nguyen
 * @version: 1.0
 */
    if(isset($_GET["is_client"]))
    {
        $isClient = $_GET["is_client"];
        getCurUserInfo($isClient);
    }
    
    

    function getCurUserInfo($isClient)
    {

        $isDev = false;
        $userInfo = array();

        if($isDev)
        {
            $userInfo = array('userId' => '2223', 
                            'userType' => array("Admin", "Teacher", "Grader"), 
                            'userSession' => '111111',
                            'userFname' => 'Tom',
                            'userLname' => 'Capaul',
                            'userEmail' => 'abc@xyz.com' );
        }
        else 
        {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                }

            if(isset($_SESSION['ewuid']))
            {
                $userInfo = array('userId' => $_SESSION['ewuid'], 
                'userType' => $_SESSION["UserType"], 
                'userSession' => session_id(),
                'userFname' => $_SESSION["FirstName"],
                'userLname' => $_SESSION["LastName"],
                'userEmail' => $_SESSION"Email"] );
            }

        }

        //user_auth($userInfo['userId'], $userInfo['userType'], array("Admin", "Teacher", "Student", "System"));
    
        
        if($isClient)
        {
            echo json_encode($userInfo);
        }
        else {
            return $userInfo;
        }
        
    }




?>