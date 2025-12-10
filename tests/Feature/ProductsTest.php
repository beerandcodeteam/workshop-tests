<?php

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});

test('products page contains empty table', function () {
    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertSee(__('No products found'));
})->group('basic');

test('products page contains a non empty table', function () {
    \App\Models\Product::factory()->create();

    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertDontSee(__('No products found'));
})->group('basic');;

test('products page contain specifc product', function () {
    $product = \App\Models\Product::factory()->create();

    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertSee($product->name)
        ->assertViewHas('products', function (\Illuminate\Pagination\LengthAwarePaginator $collection) use ($product) {
            return $collection->contains($product);
        });
})->group('basic');;

test('paginated products table doesnt contain 11th record', function () {
    $products = \App\Models\Product::factory(11)->create();
    $lastProduct = $products->last();

    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertViewHas('products', function (\Illuminate\Pagination\LengthAwarePaginator $collection) use ($lastProduct) {
            return $collection->doesntContain($lastProduct);
        });
});

test('non admin cannot see products create button', function () {
    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertDontSee('Criar novo produto');
});

test('non admin cannot access product create page', function () {
    $this->actingAs($this->user)
        ->get(route('products.create'))
        ->assertStatus(403)
        ->assertForbidden();
});

test('admin can see products create button', function () {
    asAdmin()
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertSee('Criar novo produto');
});

test('admin can access product create page', function () {
    asAdmin()
        ->get(route('products.create'))
        ->assertStatus(200);
});

test('create product successful', function ($name, $price) {

    asAdmin()
        ->post(route('products.store'), ['name' => $name, 'price' => $price])
        ->assertStatus(302)
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', ['name' => $name, 'price' => $price]);
    $lastProduct = \App\Models\Product::latest('id')->first();
    expect($name)->toBe($lastProduct->name)
        ->and($price)->toBe($lastProduct->price);
})->with([
    'test 1' => ['name' => 'Testao', 'price' => 100],
    'test 2' => ['name' => 'Testao 2', 'price' => 200],
]);

test('create product successful with dataset', function ($product) {
    asAdmin()
        ->post(route('products.store'), $product)
        ->assertStatus(302)
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', $product);
    $lastProduct = \App\Models\Product::latest('id')->first();
    expect($product['name'])->toBe($lastProduct->name)
        ->and($product['price'])->toBe($lastProduct->price);
})->with('products');
