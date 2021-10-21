<?php

namespace App\Http\Repositories\Barang;

/**
* Interface BarangInterface
* @package App\Http\Repositories
*/
interface BarangInterface {

    public function getAll();

    public function getById($id);

    public function save($data);

    public function update($data, $id);

    public function delete($id);

}
