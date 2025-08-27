<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2024 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Plugins\About2020\Controller;

use FacturaScripts\Core\Base\Controller;
use FacturaScripts\Core\Base\ControllerPermissions;
use FacturaScripts\Core\Kernel;
use FacturaScripts\Core\KernelException;
use FacturaScripts\Core\Plugins;
use FacturaScripts\Dinamic\Model\User;
use FacturaScripts\Dinamic\Model\Producto;
use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Dinamic\Model\FacturaCliente;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class About extends Controller
{
    /** @var array */
    public array $data = [];

    /**
     * Return the basic data for this page.
     *
     * @return array
     */
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'admin';
        $data['title'] = 'about';
        $data['icon'] = 'fa-solid fa-circle-info';
        return $data;
    }

    /**
     * Runs the controller's private logic.
     *
     * @param Response $response
     * @param User $user
     * @param ControllerPermissions $permissions
     * @throws KernelException
     */
    public function privateCore(&$response, $user, $permissions)
    {
        parent::privateCore($response, $user, $permissions);
        $this->data = [
            'core_version' => Kernel::version(),
            'php_version' => phpversion(),
            'extensions' => get_loaded_extensions(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'os_info' => php_uname(),
            'database_version' => $this->dataBase->version(),
            'max_filesize' => UploadedFile::getMaxFilesize(),
            'plugins' => Plugins::list(),
            'limits' => $this->getLimits(),
        ];
    }

    /**
     * Get counts of various models for limits display.
     *
     * @return array
     */
    private function getLimits(): array
    {
        // Users
        $userModel = new User();
        $users = $userModel->count();

        // Products
        $productoModel = new Producto();
        $products = $productoModel->count();

        // Customers
        $clienteModel = new Cliente();
        $customers = $clienteModel->count();

        // Customer Invoices
        $facturaModel = new FacturaCliente();
        $invoices = $facturaModel->count();

        return [
            'users' => $users,
            'products' => $products,
            'customers' => $customers,
            'invoices' => $invoices
        ];
    }
}
