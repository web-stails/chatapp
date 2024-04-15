<?php

use App\Enums\Permissions\MessagePermissionsEnum;
use App\Models\Message;

$group = 'admin-message';

/**
* Index
*/

it('admin message index with permission', function () {
    $user = getUserWithPermissions(MessagePermissionsEnum::Read->slug());

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.messages.index'))
        ->assertOk();
})->group('feature', $group);

it('admin message index without permission', function () {
    $user = getUserWithPermissions();

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.messages.index'))
        ->assertStatus(403);
})->group('feature', $group);

/**
* Show
*/

it('admin message show with permission', function () {
    $user  = getUserWithPermissions(MessagePermissionsEnum::Read->slug());
    $model = Message::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.messages.show', $model->id))
        ->assertOk();
})->group('feature', $group);

it('admin message show without permission', function () {
    $user  = getUserWithPermissions();
    $model = Message::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.messages.show', $model->id))
        ->assertStatus(403);
})->group('feature', $group);

/**
* Store
*/

it('admin message create with permission', function () {
    $user       = getUserWithPermissions(MessagePermissionsEnum::Create->slug());
    $attributes = Message::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->postJson(route('admin.messages.store'), $attributes)
        ->assertStatus(201);
})->group('feature', $group);

it('admin message create without permission', function () {
    $user       = getUserWithPermissions();
    $attributes = Message::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->postJson(route('admin.messages.store'), $attributes)
        ->assertStatus(403);
})->group('feature', $group);

/**
* Update
*/

it('admin message update with permission', function () {
    $user = getUserWithPermissions(MessagePermissionsEnum::Update->slug());

    $attributes        = Message::factory()->create();
    $attributesUpdated = Message::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->putJson(route('admin.messages.update', $attributes->id), $attributesUpdated)
        ->assertOk();
})->group('feature', $group);

it('admin message update without permission', function () {
    $user = getUserWithPermissions();

    $attributes        = Message::factory()->create();
    $attributesUpdated = Message::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->putJson(route('admin.messages.update', $attributes->id), $attributesUpdated)
        ->assertStatus(403);
})->group('feature', $group);

/**
* Destroy
*/

it('admin message destroy with permission', function () {
    $user       = getUserWithPermissions(MessagePermissionsEnum::Delete->slug());
    $attributes = Message::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->deleteJson(route('admin.messages.destroy', $attributes->id))
        ->assertStatus(204);
})->group('feature', $group);

it('admin message destroy without permission', function () {
    $user       = getUserWithPermissions();
    $attributes = Message::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->deleteJson(route('admin.messages.destroy', $attributes->id))
        ->assertStatus(403);
})->group('feature', $group);
