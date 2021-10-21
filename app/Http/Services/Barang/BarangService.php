<?php

namespace App\Http\Services\Barang;

use App\Http\Repositories\Barang\BarangRepository;
use App\Models\Barang\Barang;
use App\Repositories\PostRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;


class BarangService
{
    /**
     * @var $barangRepository
     */
    protected $barangRepository;

    /**
     * PostService constructor.
     *
     * @param BarangRepository $barangRepository
     */
    public function __construct(BarangRepository $barangRepository)
    {
        $this->barangRepository = $barangRepository;
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
            $post = $this->barangRepository->delete($id);

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
        return $this->barangRepository->getAll();
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->barangRepository->getById($id);
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
            $barang =  $this->barangRepository->getById($id);
            $barang->nama = $data['nama'];
            $barang->kuantiti = $data['kuantiti'];
            $barang->lokasi = $data['lokasi'];
            $barang->satuan = $data['satuan'];
            $barang->status = $data['status'];
            $barang->updated_at = date('Y-m-d H:i:s');
            $barang->updated_by = Auth::user()->id;

            $barang->save();

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update barang data');
        }

        DB::commit();

        return $barang;

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

        $data_exist = Barang::selectRaw('max(right(kode, 5)) as kode')->first();

        if (! $data_exist->kode) {
            $kode = 'KD-00001';
        } else {
            $pertama = $data_exist->kode;
            $int = (int) $pertama;
            $jumlah = $int  + 100001;
            $hasil = substr($jumlah,1);
            $kode = 'KD-'.$hasil;
        }
        $barang = new Barang();
        $barang->id = Uuid::uuid4()->toString();
        $barang->kode = $kode;
        $barang->nama = $request->nama;
        $barang->kuantiti = $request->kuantiti;
        $barang->lokasi = $request->lokasi;
        $barang->satuan = $request->satuan;
        $barang->status = $request->status;
        $barang->created_at = date('Y-m-d H:i:s');
        $barang->updated_at = date('Y-m-d H:i:s');
        $barang->created_by = Auth::user()->id;
        $barang->updated_by = Auth::user()->id;

        $barang->save();

        return $barang;
    }

}
