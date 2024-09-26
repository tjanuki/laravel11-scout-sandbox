<?php

use Illuminate\Support\Facades\Route;
use Meilisearch\Client;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug', function () {
    $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

    // Update index settings
    $client->index('posts')->updateSettings([
        'filterableAttributes' => ['category'],
        'sortableAttributes' => ['created_at'],
        'searchableAttributes' => ['title', 'content', 'category']
    ]);

    // Perform the search
    $search = \App\Models\Post::search('iure');

    $category = 'Technology';
    if ($category) {
        $search->where('category', $category);
    }

    // Get raw results
    $results = $search->get();

    return $results;
});
