<?php 

use App\Auth\Authenticator;
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase
{
    public function testAuthenticate()
    {
        $storage = \Mockery::mock('\App\Support\StorageInterface'); 
        $provider = \Mockery::mock('\App\Auth\AuthProviderInterface');
        $user = \Mockery::mock('\App\Auth\AuthInterface');

        $authenticator = new Authenticator($provider, $storage);

        $credentials = ['username' => 'walison', 'password' => 'password'];

        $user->shouldReceive('getAuthIdentifier')->andReturn(1);

        $provider->shouldReceive('retrieveByCredentials')->with($credentials)->andReturn($user);

        $storage->shouldReceive('set')->with('user_id', $user->getAuthIdentifier());
        $storage->shouldReceive('get')->with('user_id')->andReturn($user->getAuthIdentifier());
        
        $authenticator->authenticate($credentials);

        $this->assertEquals(1, $storage->get('user_id'));
        $this->assertNotEquals(99, $storage->get('user_id'));
    }

    public function testWhenUserIsAuthenticated()
    {
        $storage = \Mockery::mock('\App\Support\StorageInterface'); 
        $provider = \Mockery::mock('\App\Auth\AuthProviderInterface');
        $user = \Mockery::mock('\App\Auth\AuthInterface');

        $storage->shouldReceive('exists')->andReturnTrue();

        $authenticator = new Authenticator($provider, $storage);

        $this->assertTrue($authenticator->check());
    }

    public function testWhenUserIsNotAuthenticated()
    {
        $storage = \Mockery::mock('\App\Support\StorageInterface'); 
        $provider = \Mockery::mock('\App\Auth\AuthProviderInterface');
        $user = \Mockery::mock('\App\Auth\AuthInterface');

        $storage->shouldReceive('exists')->andReturnFalse();

        $authenticator = new Authenticator($provider, $storage);

        $this->assertFalse($authenticator->check());
    }
    
    public function testGetTheActiveUser()
    {
        $storage = \Mockery::mock('\App\Support\StorageInterface'); 
        $provider = \Mockery::mock('\App\Auth\AuthProviderInterface');
        $user = \Mockery::mock('\App\Auth\AuthInterface');

        $authenticator = new Authenticator($provider, $storage);

        $storage->shouldReceive('get')->andReturn(1);
        $storage->shouldReceive('exists')->andReturnTrue();

        $provider->shouldReceive('retrieveByIdentifier')->with(1)->andReturn($user);

        $this->assertEquals($user, $authenticator->user());
    }

    public function testLogoutUser()
    {
        /** @var \Mockery\MockInterface */
        $storage = \Mockery::spy('\App\Support\StorageInterface'); 

        /** @var \Mockery\MockInterface */
        $provider = \Mockery::mock('\App\Auth\AuthProviderInterface');

        $authenticator = new Authenticator($provider, $storage);

        $authenticator->logout();

        $storage->shouldHaveReceived('remove')->with('user_id');

        $this->assertTrue(true); //apenas para desmarcar o risco
    }

    public function tearDown() {
        \Mockery::close();
    }
}   