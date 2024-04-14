<?php

use App\Enums\Permissions\ChatPermissionsEnum;
use App\Models\Chat;

$group = 'admin-chat';

/**
* Index
*/

it('admin chat index with permission', function () {
    $user = getUserWithPermissions(ChatPermissionsEnum::Read->slug());

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.chats.index'))
        ->assertOk();
})->group('feature', $group);

it('admin chat index without permission', function () {
    $user = getUserWithPermissions();

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.chats.index'))
        ->assertStatus(403);
})->group('feature', $group);

/**
* Show
*/

it('admin chat show with permission', function () {
    $user  = getUserWithPermissions(ChatPermissionsEnum::Read->slug());
    $model = Chat::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.chats.show', $model->id))
        ->assertOk();
})->group('feature', $group);

it('admin chat show without permission', function () {
    $user  = getUserWithPermissions();
    $model = Chat::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->get(route('admin.chats.show', $model->id))
        ->assertStatus(403);
})->group('feature', $group);

/**
* Store
*/

it('admin chat create with permission', function () {
    $user       = getUserWithPermissions(ChatPermissionsEnum::Create->slug());
    $attributes = Chat::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->postJson(route('admin.chats.store'), $attributes)
        ->assertStatus(201);
})->group('feature', $group);

it('admin chat create without permission', function () {
    $user       = getUserWithPermissions();
    $attributes = Chat::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->postJson(route('admin.chats.store'), $attributes)
        ->assertStatus(403);
})->group('feature', $group);

/**
* Update
*/

it('admin chat update with permission', function () {
    $user = getUserWithPermissions(ChatPermissionsEnum::Update->slug());

    $attributes        = Chat::factory()->create();
    $attributesUpdated = Chat::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->putJson(route('admin.chats.update', $attributes->id), $attributesUpdated)
        ->assertOk();
})->group('feature', $group);

it('admin chat update without permission', function () {
    $user = getUserWithPermissions();

    $attributes        = Chat::factory()->create();
    $attributesUpdated = Chat::factory()->raw();

    $this
        ->actingAs($user, 'sanctum')
        ->putJson(route('admin.chats.update', $attributes->id), $attributesUpdated)
        ->assertStatus(403);
})->group('feature', $group);

/**
* Destroy
*/

it('admin chat destroy with permission', function () {
    $user       = getUserWithPermissions(ChatPermissionsEnum::Delete->slug());
    $attributes = Chat::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->deleteJson(route('admin.chats.destroy', $attributes->id))
        ->assertStatus(204);
})->group('feature', $group);

it('admin chat destroy without permission', function () {
    $user       = getUserWithPermissions();
    $attributes = Chat::factory()->create();

    $this
        ->actingAs($user, 'sanctum')
        ->deleteJson(route('admin.chats.destroy', $attributes->id))
        ->assertStatus(403);
})->group('feature', $group);
