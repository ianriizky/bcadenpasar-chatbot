<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface Issuerable
{
    /**
     * Return collection of \App\Models\Order model relation value.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Contracts\Issuerable>
     */
    public function getIssuersRelationValue(): Collection;

    /**
     * Return fullname value of the issuer.
     *
     * @return string
     */
    public function getIssuerFullname(): string;

    /**
     * Return role name of the issuer.
     *
     * @return string
     */
    public function getIssuerRole(): string;

    /**
     * Return url to the issuer detail page.
     *
     * @return string
     */
    public function getIssuerUrl(): string;
}
