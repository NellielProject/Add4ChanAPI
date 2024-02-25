<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

class Info
{

    function __construct()
    {}

    public function addAttributes(array $raw_data): array
    {
        $raw_data['compatibility'][] = '4chan';
        return $raw_data;
    }
}