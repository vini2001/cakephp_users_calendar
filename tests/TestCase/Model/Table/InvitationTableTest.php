<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InvitationTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InvitationTable Test Case
 */
class InvitationTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InvitationTable
     */
    public $Invitation;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Invitation'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Invitation') ? [] : ['className' => InvitationTable::class];
        $this->Invitation = TableRegistry::getTableLocator()->get('Invitation', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Invitation);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
