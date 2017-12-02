<?php 

namespace App\Auth\Providers;

class EloquentProvider implements AuthProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * @param string $identifier
     * @return void
     */
    public function retrieveByIdentifier(string $identifier)
    {

    }

    /**
     * {@inheritDoc}
     *
     * @param array $credentials
     * @return void
     */
    public function retrieveByCredentials(array $credentials)
    {

    }
}
