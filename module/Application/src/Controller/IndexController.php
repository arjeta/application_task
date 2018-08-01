<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function submitAction()
    {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email_address = trim(strtolower($_POST['email_address']));

        $email_valid = filter_var($email_address, FILTER_VALIDATE_EMAIL);

        if ($email_valid != false)
            $message = "Hello $first_name!";
        else
            $message = "The email is invalid!";

        return new ViewModel(["message" => $message, "email_valid" => $email_valid]);
    }

}