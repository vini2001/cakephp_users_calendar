<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Invitation Entity
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_event
 * @property bool|null $accepted
 */
class Invitation extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id_user' => true,
        'id_event' => true,
        'accepted' => true
    ];
}
