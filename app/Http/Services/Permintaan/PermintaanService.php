<?php

namespace App\Http\Services\Permintaan;

use App\Http\Repositories\Permintaan\PermintaanRepository;
use App\Models\Barang\Barang;
use App\Models\Permintaan\DetailPermintaan;
use App\Models\Permintaan\Permintaan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class PermintaanService
{
    /**
     * @var $permintaanRepository
     */
    protected $permintaanRepository;

    /**
     * PostService constructor.
     *
     * @param PermintaanRepository $permintaanRepository
     */
    public function __construct(PermintaanRepository $permintaanRepository)
    {
        $this->permintaanRepository = $permintaanRepository;
    }

    /**
     * Delete post by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->permintaanRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    /**
     * Get all post.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->permintaanRepository->getAll();
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        $data = $this->permintaanRepository->getById($id);

        $data->modelPeminta = User::where('id',$data->id_user)->first();
        $data->nama = $data->modelPeminta ? $data->modelPeminta->name : '';
        $data->departemen = $data->modelPeminta ? $data->modelPeminta->departement : '';

        $data->items = DetailPermintaan::where('id_permintaan',$id)->get();
        foreach ($data->items as $key => $value) {
            # code...
            $value->modelBarang = Barang::where('id',$value->id_barang)->first();
            $value->lokasi = $value->modelBarang ? $value->modelBarang->lokasi : '';
            $value->qty = $value->modelBarang ? $value->modelBarang->kuantiti : '';
            $value->satuan = $value->modelBarang ? $value->modelBarang->satuan : '';
        }
        return $data;
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data, $id)
    {
        DB::beginTransaction();
        try {

            $permintaan =  $this->permintaanRepository->getById($id);

            $permintaan->id_user = $data['id_user'];
            $permintaan->tanggal_permintaan = $data['tanggal_permintaan'];
            $permintaan->created_at = date('Y-m-d H:i:s');
            $permintaan->updated_at = date('Y-m-d H:i:s');
            $permintaan->created_by = Auth::user()->id;
            $permintaan->updated_by = Auth::user()->id;

            $permintaan->save();

            $dataPermintaan = [];

            foreach ($data['dat_detail_permintaan'] as $value) {
                array_push($dataPermintaan,[
                    'id' => Uuid::uuid4()->toString(),
                    'id_permintaan' => $permintaan->id,
                    'id_barang' => $value['id_barang'],
                    'kuantiti' => $value['kuantiti'],
                    'status' => $value['status'],
                    'keterangan' => $value['keterangan'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);
            }

            DetailPermintaan::insert($dataPermintaan);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update barang data');
        }

        DB::commit();

        return $permintaan;

    }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($request)
    {

        $data_exist = Permintaan::selectRaw('max(right(kode, 5)) as kode')->first();

        if (! $data_exist->kode) {
            $kode = 'KD-00001';
        } else {
            $pertama = $data_exist->kode;
            $int = (int) $pertama;
            $jumlah = $int  + 100001;
            $hasil = substr($jumlah,1);
            $kode = 'KD-'.$hasil;
        }

        $permintaan = new Permintaan();

        $permintaan->id = Uuid::uuid4()->toString();
        $permintaan->kode = $kode;
        $permintaan->id_user = $request->id_user;
        $permintaan->tanggal_permintaan = $request->tanggal_permintaan;
        $permintaan->created_at = date('Y-m-d H:i:s');
        $permintaan->updated_at = date('Y-m-d H:i:s');
        $permintaan->created_by = Auth::user()->id;
        $permintaan->updated_by = Auth::user()->id;

        $permintaan->save();

        $dataPermintaan = [];

        foreach ($request->items as $value) {
            $barang = Barang::where('id',$value['id_barang'])->first();
            if($barang) {
                Barang::where('id',$value['id_barang'])->update([
                    'kuantiti' => $barang->kuantiti - $value['kuantiti']
                ]);
            }
            array_push($dataPermintaan,[
                'id' => Uuid::uuid4()->toString(),
                'id_permintaan' => $permintaan->id,
                'id_barang' => $value['id_barang'],
                'kuantiti' => $value['kuantiti'],
                'status' => $value['status'],
                'keterangan' => $value['keterangan'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id
            ]);
        }

        DetailPermintaan::insert($dataPermintaan);


        return $permintaan;
    }

}
