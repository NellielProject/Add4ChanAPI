<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use Nelliel\Domains\DomainBoard;

class Catalog
{

    function __construct()
    {}

    public function addAttributes(array $raw_data, DomainBoard $board): array
    {
        $index_replies = $board->setting('index_thread_replies');
        $index_sticky_replies = $board->setting('index_sticky_replies');
        $page_count = count($raw_data);
        $post_add = new Post();

        for ($page = 0; $page < $page_count; $page ++) {
            $thread_count = count($raw_data[$page]['threads']);

            for ($thread = 0; $thread < $thread_count; $thread ++) {
                $thread_id = (int) $raw_data[$page]['threads'][$thread]['thread_id'];
                $thread_instance = $board->getThread($thread_id);
                $limit = $thread_instance->getData('sticky') ? $index_sticky_replies : $index_replies;
                $last_replies = $thread_instance->lastReplies($limit);

                foreach ($last_replies as $reply) {
                    $raw_data[$page]['threads'][$thread]['last_replies'][] = $post_add->addAttributes(array(), $reply);
                }
            }
        }

        return $raw_data;
    }
}