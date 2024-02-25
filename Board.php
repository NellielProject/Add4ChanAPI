<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use Nelliel\Domains\DomainBoard;
use Nelliel\FileTypes;
use Nelliel\Output\Markup;

class Board
{

    function __construct()
    {}

    // Not yet implemented:
    // board_flags, country_flags, oekaki, code_tags, math_tags, min_image_width, min_image_height
    public function addAttributes(array $raw_data, DomainBoard $board): array
    {
        $filetypes = new FileTypes($board->database());
        $raw_data['board'] = $board->reference('board_uri');
        $raw_data['title'] = $board->setting('name') ?? '';
        $raw_data['ws_board'] = intval($board->setting('safety_level') === 'SFW');
        $raw_data['per_page'] = $board->setting('threads_per_page');
        $raw_data['max_filesize'] = $board->setting('max_filesize');
        $raw_data['max_webm_filesize'] = intval($filetypes->categorySetting($board, 'video', 'max_size'));
        $raw_data['max_comment_chars'] = $board->setting('max_comment_length');
        $raw_data['max_webm_duration'] = OVER_9000; // TODO: Update if max video length implemented
        $raw_data['bump_limit'] = $board->setting('limit_bump_count') ? $board->setting('max_bumps') : $board->setting(
            'max_posts');
        $raw_data['image_limit'] = $board->setting('max_thread_uploads');
        $raw_data['cooldowns']['images'] = $board->setting('upload_renzoku'); // Close enough
        $raw_data['meta_description'] = $board->setting('description') ?? '';

        if ($board->setting('enable_spoilers')) {
            $raw_data['spoilers'] = 1;
            $raw_data['custom_spoilers'] = 1;
        }

        if ($board->setting('max_archive_threads') > 0) {
            $raw_data['is_archived'] = 1;
        }

        if ($board->setting('show_poster_id')) {
            $raw_data['user_ids'] = 1;
        }

        $markup = new Markup($board->database());
        $block_markup = $markup->getMarkupData('block', true);

        if ($block_markup['shift-jis-art']['enabled'] ?? false) {
            $raw_data['sjis_tags'] = 1;
        }

        if (!$board->setting('enable_uploads') ||
            (!$board->setting('allow_op_files') && !$board->setting('allow_reply_files') &&
            !$board->setting('allow_op_embeds') && !$board->setting('allow_reply_embeds'))) {
            $raw_data['text_only'] = 1;
        }

        if ($board->setting('forced_anonymous') ||
            (!$board->setting('enable_op_name_field') && !$board->setting('enable_reply_name_field'))) {
            $raw_data['forced_anon'] = 1;
        }

        if ($filetypes->formatIsEnabled($board, 'webm')) {
            $raw_data['webm_audio'] = 1;
        }

        if ($board->setting('require_op_subject') || $board->setting('require_reply_subject')) {
            $raw_data['require_subject'] = 1;
        }

        return $raw_data;
    }
}