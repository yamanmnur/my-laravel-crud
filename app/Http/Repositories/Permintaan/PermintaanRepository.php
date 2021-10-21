<?php

namespace App\Http\Repositories\Permintaan;

use App\Models\Permintaan\Permintaan;

class PermintaanRepository implements PermintaanInterface {

    /**
     * @var Post
     */
    protected $permintaan;

    /**
     * PostRepository constructor.
     *
     * @param Post $permintaan
     */
    public function __construct(Permintaan $permintaan)
    {
        $this->permintaan = $permintaan;
    }


    public function getAll() {
        $data = $this->permintaan->select(
            'dat_permintaan.id',
            'dat_permintaan.kode',
            'dat_permintaan.id_user',
            'dat_permintaan.tanggal_permintaan',
            'users.name as nama_user'
            )->join('users','users.id','dat_permintaan.id_user')
            ->orderBy('dat_permintaan.created_at','desc')->get();

        return $data;
    }

    public function getById($id) {
        return $this->permintaan->with('detailPermintaan')->where('id',$id)->first();
    }

    public function getByRelationDetail($id) {
        return $this->permintaan->select(
            'id',
            'kode',
            'nama',
            'kuantiti',
            'lokasi',
            'status',
        )->with('detailPermintaan')->where('id',$id)->first();
    }

    public function save($data) {
        return $this->permintaan->insert($data);
    }

    public function update($data, $id) {
        return $this->permintaan->where('id',$id)->update($data);
    }

    public function delete($id) {
        $data = $this->permintaan->find($id);

        $data->delete();

        return $data;
    }
}


