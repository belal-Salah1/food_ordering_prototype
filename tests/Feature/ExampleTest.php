<?php

it('redirects the homepage to the menu', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('menu'));
});
