<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use Nelliel\Content\Thread;
use PDO;

class OPJSON
{

    function __construct()
    {}

    // Not yet implemented:
    // tag, unique_ips, archived, archived_on
    public function generate(Thread $thread): array
    {
        $post = $thread->firstPost();
        $raw_data = array();

        if ($thread->getData('sticky')) {
            $raw_data['sticky'] = 1;
        }

        if ($thread->getData('locked')) {
            $raw_data['closed'] = 1;
        }

        $raw_data['replies'] = $thread->getData('post_count') - 1;
        $prepared = $thread->domain()->database()->prepare(
            'SELECT COUNT(*) FROM "' . $thread->domain()->reference('uploads_table') .
            '" WHERE "parent_thread" = ? AND "category" = \'graphics\' AND "upload_order" = 1');
        $image_posts = $thread->domain()->database()->executePreparedFetch($prepared,
            [$thread->contentID()->threadID()], PDO::FETCH_COLUMN);
        $raw_data['images'] = intval($image_posts !== false ? $image_posts : 0);

        if ($thread->domain()->setting('limit_bump_count') &&
            $thread->getData('bump_count') >= $thread->domain()->setting('max_bumps')) {
            $raw_data['bumplimit'] = 1;
        }

        if ($thread->domain()->setting('limit_thread_uploads') &&
            ($thread->getData('total_uploads') >= $thread->domain()->setting('max_thread_uploads'))) {
            $raw_data['imagelimit'] = 1;
        }

        $raw_data['semantic_url'] = $thread->generateSlug($post);

        return $raw_data;
    }
}