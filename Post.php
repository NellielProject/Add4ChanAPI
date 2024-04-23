<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use Nelliel\Content\Post as NellielPost;

class Post
{

    function __construct()
    {}

    //4Chan exclusive:
    // since4pass

    // Not yet implemented:
    // custom_spoiler, m_img, country, counrty_name, board_flag, flag_name
    public function addAttributes(array $raw_data, NellielPost $post): array
    {
        $uploads = $post->getUploads();
        $upload_count = count($uploads);
        $thread = $post->getParent();
        $board = $post->domain();

        if ($post->getData('op')) {
            $op_json = new OPJSON();
            $raw_data = $raw_data + $op_json->generate($post->getParent());
        }

        $raw_data['no'] = $post->getData('post_number');
        $raw_data['now'] = $post->domain()->domainDateTime(intval($post->getData('post_time')))->format(
            $post->domain()->setting('post_time_format'));
        $raw_data['resto'] = $post->getData('reply_to');
        $raw_data['name'] = $post->getData('name');

        if (!nel_true_empty($post->getData('tripcode'))) {
            $raw_data['trip'] = $post->getData('tripcode');
        }

        if (!nel_true_empty($post->getData('secure_tripcode'))) {
            $raw_data['trip'] = $post->getData('secure_tripcode');
        }

        if (!nel_true_empty($post->getData('capcode'))) {
            $raw_data['capcode'] = $post->getData('capcode');
        }

        if ($board->setting('show_poster_id')) {
            // From the Nelliel output code since ID is not pre-generated
            $id = hash_hmac('sha256', $thread->getData('salt') . $post->getData('hashed_ip_address'),
                NEL_POSTER_ID_PEPPER . $board->id() . $thread->contentID()->threadID());
            $raw_data['id'] = utf8_substr($id, 0, 8);
        }

        if ($post->getData('op') && !nel_true_empty($post->getData('subject'))) {
            $raw_data['sub'] = $post->getData('subject');
        }

        $raw_data['com'] = $post->getCache()['comment_markup'] ?? $post->getData('comment');
        $raw_data['time'] = $post->getData('post_time');

        if ($upload_count > 0) {
            $upload = new Upload();
            $raw_data = $raw_data + $upload->addAttributes(array(), $uploads[0]);
        }

        return $raw_data;
    }
}