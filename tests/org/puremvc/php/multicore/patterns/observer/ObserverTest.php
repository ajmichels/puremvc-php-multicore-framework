<?php
/**
 * PureMVC PHP Multicore Unit Tests
 *
 * @author Michel Chouinard <michel.chouinard@gmail.com>
 *
 * Created on Jully 24, 2009
 *
 * @version 1.0
 * @author Michel Chouinard <michel.chouinard@gmail.com>
 * @copyright PureMVC - Copyright(c) 2006-2008 Futurescale, Inc., Some rights reserved.
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons Attribution 3.0 Unported License
 * @package org.puremvc.php.multicore.unittest
 */
/**
 *
 */

require_once 'org/puremvc/php/multicore/interfaces/INotification.php';
require_once 'org/puremvc/php/multicore/patterns/observer/Notification.php';
require_once 'org/puremvc/php/multicore/patterns/observer/Observer.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Observer test case.
 * @package org.puremvc.php.multicore.unittest
 */
class ObserverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Observer
     */
    private $Observer;

    /**
     * A test variable that proves the notify method was
     * executed with 'this' as the execution context.
     *
     * @var int
     */
    public $observerTestVar;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown ();
    }

    /**
     * Constructs the test case.
     */
    public function __construct(){}

    /**
     * Tests observer class when initialized by accessor methods.
     */
    public function testObserverAccessors()
    {
        // Create observer with null args, then
        // use accessors to set notification method and context
        $observer = new Observer( '', '' );
        $observer->setNotifyContext( $this );
        $observer->setNotifyMethod( 'observerTestMethod' );

        // create a test event, setting a payload value and notify
        // the observer with it. since the observer is this class
        // and the notification method is observerTestMethod,
        // successful notification will result in our local
        // observerTestVar being set to the value we pass in
        // on the note body.
        $note = new Notification( 'ObserverTestNote', 10 );
        $observer->notifyObserver( $note );

        // test assertions
        $this->assertTrue( $this->observerTestVar == 10, "Expecting observerTestVar = 10" );
    }

    /**
     * Tests observer class when initialized by constructor.
     */
    public function testObserverConstructor()
    {
        // Create observer passing in notification method and context
        $observer = new Observer( 'observerTestMethod', $this );

        // create a test note, setting a body value and notify
        // the observer with it. since the observer is this class
        // and the notification method is observerTestMethod,
        // successful notification will result in our local
        // observerTestVar being set to the value we pass in
        // on the note body.
        $note = new Notification( 'ObserverTestNote', 5 );
        $observer->notifyObserver( $note );

        // test assertions
        $this->assertTrue( $this->observerTestVar == 5, "Expecting observerTestVar = 5" );
    }

    /**
     * Tests the compareNotifyContext method of the Observer class
     */
    public function testCompareNotifyContext()
    {
        // Create observer passing in notification method and context
        $observer = new Observer( 'observerTestMethod', $this );

        $negTestObj = new stdClass();

        // test assertions
        $this->assertTrue( $observer->compareNotifyContext($negTestObj) == false, "Expecting observer->compareNotifyContext(negTestObj) == false" );
        $this->assertTrue( $observer->compareNotifyContext($this) == true, "Expecting observer->compareNotifyContext(this) == true" );
    }

    /**
     * A function that is used as the observer notification
     * method. It multiplies the input number by the
     * observerTestVar value
     */
    public function observerTestMethod( INotification $note )
    {
        $this->observerTestVar = $note->getBody();
    }
}

