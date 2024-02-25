<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use Nelliel\Domains\DomainBoard;

class Threadlist
{

    function __construct()
    {}

    public function addAttributes(array $raw_data, DomainBoard $board): array
    {
        foreach ($raw_data as $index => $thread_set) {
            foreach ($thread_set['threads'] as $set_index => $thread) {
                $raw_data[$index]['threads'][$set_index]['no'] = $thread['thread_id'];
                $raw_data[$index]['threads'][$set_index]['last_modified'] = $thread['last_update'];
            }
        }

        return $raw_data;
    }
}