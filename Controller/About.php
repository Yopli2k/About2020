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
use FacturaScripts\Core\Kernel;
use FacturaScripts\Core\Plugins;
use FacturaScripts\Dinamic\Model\User;
use FacturaScripts\Dinamic\Model\Producto;
use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Dinamic\Model\FacturaCliente;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class About extends Controller
{
    /** @var array */
    public array $data = [];

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'admin';
        $data['title'] = 'about';
        $data['icon'] = 'fa-solid fa-circle-info';
        return $data;
    }

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

    private function getLimits(): array
    {
        // Contar usuarios
        $userModel = new User();
        $users = $userModel->count();

        // Contar productos
        $productoModel = new Producto();
        $products = $productoModel->count();

        // Contar clientes
        $clienteModel = new Cliente();
        $customers = $clienteModel->count();

        // Contar facturas de cliente
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
