<?php
declare(strict_types = 1);

namespace Add4ChanAPI;

$plugin_id = 'nel-add-4chan-api';
nel_plugins()->registerNamespace($plugin_id, 'Add4ChanAPI', '');

$upload = new Upload();
nel_plugins()->addMethod('nel-in-after-upload-json', $upload, 'addAttributes', $plugin_id, 10);

$post = new Post();
nel_plugins()->addMethod('nel-in-after-post-json', $post, 'addAttributes', $plugin_id, 10);

$index = new Index();
nel_plugins()->addMethod('nel-in-after-index-json', $index, 'addAttributes', $plugin_id, 10);

$catalog = new Catalog();
nel_plugins()->addMethod('nel-in-after-catalog-json', $catalog, 'addAttributes', $plugin_id, 10);

$threadlist = new Threadlist();
nel_plugins()->addMethod('nel-in-after-threadlist-json', $threadlist, 'addAttributes', $plugin_id, 10);

$board = new Board();
nel_plugins()->addMethod('nel-in-after-board-json', $board, 'addAttributes', $plugin_id, 10);
