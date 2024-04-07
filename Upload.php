<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

defined('NELLIEL_VERSION') or die('NOPE.AVI');

use SplFileInfo;
use Nelliel\Content\Upload as NellielUpload;

class Upload
{

    function __construct()
    {}

    public function addAttributes(array $raw_data, NellielUpload $upload): array
    {
        $post = $upload->getParent();
        $raw_data['tim'] = (int) ($post->getData('post_time') .
            str_pad((string) $post->getData('post_time_milli'), 6, '0', STR_PAD_RIGHT));
        $file_info = new SplFileInfo($upload->getData('original_filename'));
        $raw_data['filename'] = $file_info->getBasename('.' . $file_info->getExtension());
        $raw_data['ext'] = '.' . $file_info->getExtension();
        $raw_data['fsize'] = $upload->getData('filesize');
        $raw_data['md5'] = base64_encode(hex2bin($upload->getData('md5')));
        $raw_data['w'] = $upload->getData('display_width');
        $raw_data['h'] = $upload->getData('display_height');
        $raw_data['tn_w'] = $upload->getData('preview_width');
        $raw_data['tn_h'] = $upload->getData('preview_height');

        if ($upload->getData('deleted')) {
            $raw_data['filedeleted'] = 1;
        }

        if ($upload->getData('spoiler')) {
            $raw_data['spoiler'] = 1;
        }

        return $raw_data;
    }
}