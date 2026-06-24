<?php

test('the application redirects from root to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
