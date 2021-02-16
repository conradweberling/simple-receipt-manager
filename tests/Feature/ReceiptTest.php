<?php

namespace Tests\Feature;

use App\Models\Receipt;
use Tests\TestCase;

class ReceiptTest extends TestCase
{

    protected $table;

    /**
     * ReceiptTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->table = with(new Receipt())->getTable();
    }

    /**
     * Test list view
     */
    public function testListView()
    {
        $this->actingAs($this->user)->get(route('receipts'))
            ->assertStatus(200)
            ->assertSee('receipt-list');
    }

    /**
     * Test list json
     */
    public function testListJson()
    {
        $this->actingAs($this->user)->get(route('receipts'), ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonFragment(['total' => 72]);
    }

    /**
     * Test create view
     */
    public function testCreateView()
    {
        $this->actingAs($this->user)->get(route('receipts.create'))
            ->assertStatus(200)
            ->assertSee('Create Receipt')
            ->assertSee('Save');
    }

    /**
     * Test store
     */
    public function testStore()
    {

        $data = [
            'thumbnail' => 'images/dummy500x850.png',
            'image' => 'images/dummy1000x1500.png',
            'amount' => 55,
            'date' => date("Y-m-d"),
        ];

        $this->actingAs($this->user)->post(route('receipts.store'), $data, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertDatabaseHas($this->table, $data);

    }

    /**
     * Test soft delete
     */
    public function testSoftDelete() {

        $receipt = Receipt::max('id');

        $this->actingAs($this->user)->post(route('receipts.delete', $receipt), [], ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertSoftDeleted($this->table, ['id' => $receipt]);

    }

}
