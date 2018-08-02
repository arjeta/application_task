<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    /**
     * @throws \Exception
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    /**
     * @throws \Exception
     */
    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container form');
        $this->assertQueryContentRegex('.container form label', '/First name/i');
    }

    /**
     * @throws \Exception
     */
    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    /**
     * @throws \Exception
     */
    public function testFormInputs()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container form input[name="first_name"]');
        $this->assertQuery('.container form input[name="last_name"]');
        $this->assertQuery('.container form input[name="email_address"]');
        $this->assertQuery('.container form input[id="first_name"]');
        $this->assertQuery('.container form input[id="last_name"]');
        $this->assertQuery('.container form input[id="email_address"]');
        $this->assertQuery('.container form input[class*="form-control"][id="first_name"]');
        $this->assertQuery('.container form input[class*="form-control"][id="last_name"]');
        $this->assertQuery('.container form input[class*="form-control"][id="email_address"]');
        $this->assertQueryContentRegex('.container form label', '/First name/i');
        $this->assertQueryContentRegex('.container form label', '/Last name/i');
        $this->assertQueryContentRegex('.container form label', '/Email Address/i');
    }

    /**
     * @throws \Exception
     * * @var $p $this
     */
    public function testFormSubmit()
    {
        $p = new Parameters();
        $p->set('email_address','asd@asd.asd');
        $p->set('first_name','Arjeta');
        $p->set('first_name','Avllaj');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/submit');
        $this->assertResponseStatusCode(200);
    }

    /**
     * @throws \Exception
     */
    public function testFormSubmitSuccess()
    {
        $this->dispatch('/submit', 'POST', [
            'email_address' => "asd@asd.asd",
        ]);
        $this->assertResponseStatusCode(200);
        $this->assertQuery('.container .alert');
    }

    /**
     * @throws \Exception
     */
    public function testFormSetup()
    {
        $this->dispatch('/', 'GET', [
            'email_address' => 'asd@asd',
            'first_name' =>'Arjeta',
        ]);
        $this->assertQuery('.container .text-danger');
    }

    /**
     * @throws \Exception
     */
    public function testEmptyInputFields() {

        $this->dispatch('/submit', 'POST', [
            'fist_name' => '',
            'last_name' => '',
            'email_address' => '',
        ] );
         $this->assertResponseStatusCode(200);
    }

    /**
     * @throws \Exception
     */
    public function testValidInputFields() {

        $this->dispatch('/submit', 'POST', [
            'fist_name' => 'arjeta',
            'last_name' => 'avllaj',
            'email_address' => 'arjeta@test.com',
        ] );
        $this->assertResponseStatusCode(200);
    }

    /**
     * @throws \Exception
     */
    public function testInvalidInputFields() {

        $this->dispatch('/submit', 'POST', [
            'fist_name' => 'arjeta',
            'last_name' => '',
            'email_address' => 'a@',
        ] );
        $this->assertResponseStatusCode(200);
    }
}
