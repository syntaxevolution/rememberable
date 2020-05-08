<?php

namespace SyntaxEvolution\Rememberable;

use SyntaxEvolution\Rememberable\Query\Builder;

trait Rememberable
{
    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        $builder = new Builder($conn, $grammar, $conn->getPostProcessor());

        if (isset($this->rememberFor)) {
            $builder->remember($this->rememberFor);
        }

        if (isset($this->rememberCacheTag)) {
            $builder->cacheTags($this->rememberCacheTag);
        }

        //if (isset($this->rememberCachePrefix)) {
        //ToDo: see if using server_hostname or whatever speeds this up
            $tenant = app(\Hyn\Tenancy\Environment::class)->tenant();
            if(isset($tenant->id)){
                $set_tenant = $tenant->id;
            }else{
                $set_tenant = "default";
            }
            $builder->prefix($set_tenant);
        //}

        if (isset($this->rememberCacheDriver)) {
            $builder->cacheDriver($this->rememberCacheDriver);
        }

        return $builder;
    }
}
