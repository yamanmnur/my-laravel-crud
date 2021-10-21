<?php

namespace App\Models\Barang;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
class Barang extends Model
{
    
    use SoftDeletes;

    protected $table = 'ref_barang';
    
    protected $fillable = [ 
       'id',
       'kode',
       'nama',
       'kuantiti',
       'lokasi',
       'satuan',
       'status',
       'created_at',
       'updated_at',
       'deleted_at',
       'created_by',
       'updated_by',
       'deleted_by',
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    
    protected $keyType = 'string';
  
}
