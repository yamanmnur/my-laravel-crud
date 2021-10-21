<?php

namespace App\Http\Repositories\Barang;

use App\Models\Barang\Barang;

class BarangRepository implements BarangInterface {

    /**
     * @var Post
     */
    protected $barang;

    /**
     * PostRepository constructor.
     *
     * @param Post $barang
     */
    public function __construct(Barang $barang)
    {
        $this->barang = $barang;
    }


    public function getAll() {
        return $this->barang->select(
            'id',
            'kode',
            'nama',
            'kuantiti',
            'lokasi',
            'satuan',
            'status',
        )->orderBy('created_at','desc')->get();
    }

    public function getById($id) {
        return $this->barang->select(
            'id',
            'kode',
            'nama',
            'kuantiti',
            'lokasi',
            'satuan',
            'status',
        )->where('id',$id)->first();
    }

    public function save($data) {
        return $this->barang->insert($data);
    }

    public function update($data, $id) {
        return $this->barang->where('id',$id)->update($data);
    }

    public function delete($id) {
        $data = $this->barang->find($id);

        $data->delete();

        return $data;
    }
}


