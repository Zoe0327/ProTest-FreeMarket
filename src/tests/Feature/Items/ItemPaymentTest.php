<?php

namespace Tests\Feature\Items;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Delivery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemPaymentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('このテストクラス全体をスキップします');
    }

}
