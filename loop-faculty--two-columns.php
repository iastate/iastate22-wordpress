<?php

$context = Timber::get_context();

$context['posts'] = new Timber\PostQuery();
Timber::render('loops/loop-faculty--two-columns.twig', $context);

