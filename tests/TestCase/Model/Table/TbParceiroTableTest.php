<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TbParceiroTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TbParceiroTable Test Case
 */
class TbParceiroTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TbParceiroTable
     */
    protected $TbParceiro;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.TbParceiro',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TbParceiro') ? [] : ['className' => TbParceiroTable::class];
        $this->TbParceiro = TableRegistry::getTableLocator()->get('TbParceiro', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->TbParceiro);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
