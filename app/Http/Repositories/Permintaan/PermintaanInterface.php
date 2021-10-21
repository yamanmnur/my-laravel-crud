<?php

namespace App\Http\Repositories\Permintaan;

/**
* Interface BarangInterface
* @package App\Http\Repositories
*/
interface PermintaanInterface {

    public function getAll();

    public function getById($id);

    public function save($data);

    public function update($data, $id);

    public function delete($id);

    public function getByRelationDetail($id);

}
