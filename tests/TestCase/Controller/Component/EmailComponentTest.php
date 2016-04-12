<?php
namespace Platform\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use Platform\Controller\Component\EmailComponent;

/**
 * Platform\Controller\Component\EmailComponent Test Case
 */
class EmailComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Platform\Controller\Component\EmailComponent
     */
    public $Email;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Email = new EmailComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Email);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
