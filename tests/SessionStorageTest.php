<?php

use PHPUnit\Framework\TestCase;
use App\Support\SessionStorage;

class SessionStorageTest extends TestCase
{
    /** @var SessionStorage */
    private $session;

    public function setUp()
    {
        $this->session = new SessionStorage('test_bucket');
    }

    public function testShouldCreateBucketWithDefaultName()
    {
        $storage = new SessionStorage();

        $this->assertTrue(isset($_SESSION['default']));
    }

    public function testInsertItemIntoTheBucket()
    {
        $this->session->set('test_key', 'test_value');
        $this->assertEquals($_SESSION['test_bucket']['test_key'], 'test_value');
    }

    /**
     * @depends testInsertItemIntoTheBucket
    */
    public function testGetItemFromBucket()
    {
        $this->assertEquals($this->session->get('test_key'), 'test_value');
        $this->assertCount(1, $this->session);
    }

    /**
     * @depends testInsertItemIntoTheBucket
     */
    public function testIfSessionHasItem()
    {
        $this->assertTrue($this->session->exists('test_key'));
        $this->assertFalse($this->session->exists('test_key_not_used'));
    }

    /**
     * @depends testInsertItemIntoTheBucket
     */
    public function testRemoveItemFromBucket()
    {
        $this->session->remove('test_key');

        $this->assertFalse($this->session->exists('test_key'));
    }
  
    public function testClearBucket()
    {
        $this->session->set('test_key', 'test_value');
        $this->session->set('test_key_2', 'test_value_2');

        $this->assertCount(2, $this->session);
        
        $this->session->clear();

        $this->assertFalse(isset($_SESSION['test_bucket']));
    }

    public function testRetrieveAllItemsFromBucket()
    {
        $this->session->set('test_key', 'test_value');
        $this->session->set('test_key_2', 'test_value_2');

        $this->assertArrayHasKey('test_key', $this->session->all());
        $this->assertArrayHasKey('test_key_2', $this->session->all());
        $this->assertContains('test_value', $this->session->all());
        $this->assertContains('test_value_2', $this->session->all());
    }

    public static function tearDownAfterClass()
    {
        unset($_SESSION['test_key']);
    }
}   