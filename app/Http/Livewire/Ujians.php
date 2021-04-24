<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ujian;

class Ujians extends Component
{
    public $ujians, $nama_mk, $dosen, $jumlah_soal, $keterangan, $ujian_id;
    public $isModal = 0;

    public function render()
    {
        $this->ujians = Ujian::orderBy('created_at', 'DESC')->get();
        return view('livewire.ujians');
    }
    public function create()
    {

        $this->resetFields();
        $this->openModal();
    }
    public function closeModal()
    {
        $this->isModal = false;
    }
    public function openModal()
    {
        $this->isModal = true;
    }
    public function resetFields()
    {
        $this->nama_mk = '';
        $this->dosen = '';
        $this->jumlah_soal = '';
        $this->keterangan = '';
        $this->ujian_id = '';
    }
    public function store()
    {
        $this->validate([
            'nama_mk' => 'required|string',
            'dosen' => 'required|string',
            'jumlah_soal' => 'required|numeric',
            'keterangan' => 'required'
        ]);

        Ujian::updateOrCreate(['id' => $this->ujian_id], [
            'nama_mk' => $this->nama_mk,
            'dosen' => $this->dosen,
            'jumlah_soal' => $this->jumlah_soal,
            'keterangan' => $this->keterangan,
        ]);

        //BUAT FLASH SESSION UNTUK MENAMPILKAN ALERT NOTIFIKASI
        session()->flash('message', $this->ujian_id ? $this->nama_mk . ' Diperbaharui': $this->nama_mk . ' Ditambahkan');
        $this->closeModal(); //TUTUP MODAL
        $this->resetFields(); //DAN BERSIHKAN FIELD
    }
    public function edit($id)
    {
        $ujian = Ujian::find($id); //BUAT QUERY UTK PENGAMBILAN DATA
        //LALU ASSIGN KE DALAM MASING-MASING PROPERTI DATANYA
        $this->ujian_id = $id;
        $this->nama_mk = $ujian->nama_mk;
        $this->dosen = $ujian->dosen;
        $this->jumlah_soal = $ujian->jumlah_soal;
        $this->keterangan = $ujian->keterangan;

        $this->openModal(); //LALU BUKA MODAL
    }
    public function delete($id)
    {
        $ujian = Ujian::find($id); //BUAT QUERY UNTUK MENGAMBIL DATA BERDASARKAN ID
        $ujian->delete(); //LALU HAPUS DATA
        session()->flash('message', $ujian->nama_mk . ' Dihapus'); //DAN BUAT FLASH MESSAGE UNTUK NOTIFIKASI
    }
}