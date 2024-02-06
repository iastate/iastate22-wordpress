<?php

use Timber\PostQuery;
use Timber\Timber;

$context = Timber::get_context();
$context['posts'] = new PostQuery();

Timber::render( 'loops/loop-faculty--tabular.twig', $context );
