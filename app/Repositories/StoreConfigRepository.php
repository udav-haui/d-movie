<?php
/**
 *  BSS Commerce Co.
 *
 *  NOTICE OF LICENSE
 *
 *  This source file is subject to the EULA
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category    BSS
 * @package     BSS_
 * @author      Extension Team
 * @copyright   Copyright © 2020 BSS Commerce Co. ( http://bsscommerce.com )
 * @license     http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace App\Repositories;

use \App\Config;

/**
 * Class StoreConfigRepository
 *
 * @package App\Repositories
 */
class StoreConfigRepository extends Abstracts\CRUDModelAbstract implements Interfaces\StoreConfigRepositoryInterface
{
    protected $model = Config::class;
}
