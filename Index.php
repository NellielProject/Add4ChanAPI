<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use Nelliel\Domains\DomainBoard;

class Index
{

    function __construct()
    {}

    public function addAttributes(array $raw_data, DomainBoard $board, int $page): array
    {
        if (isset($raw_data['threads'])) {
            $thread_count = count($raw_data['threads']);

            for ($i = 0; $i < $thread_count; $i ++) {
                if (isset($raw_data['threads'][$i]['posts'])) {
                    $raw_data['threads'][$i]['posts'][0]['omitted_images'] = 0; // TODO: Implement
                }
            }
        }

        return $raw_data;
    }
}