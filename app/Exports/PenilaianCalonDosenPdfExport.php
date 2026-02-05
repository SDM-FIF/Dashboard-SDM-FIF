<?php

namespace App\Exports;

use App\Models\PenilaianDetail;
use App\Models\JadwalPengujian;
use App\Models\Dosen;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PenilaianCalonDosenPdfExport
{
    protected $penilaianDetail;
    protected $jadwal;
    protected $calonDosen;
    protected $dosenPenguji;
    protected $qrCodePath = null;
    protected $qrCodeBase64 = null;

    public function __construct($penilaianId)
    {
        $this->penilaianDetail = PenilaianDetail::with(['dosen', 'user'])->findOrFail($penilaianId);
        $this->jadwal = JadwalPengujian::with(['calonDosen.prodi', 'tahunAjar'])->findOrFail($this->penilaianDetail->jadwal_pengujian_id);
        $this->calonDosen = $this->jadwal->calonDosen;
        
        // Get dosen penguji from user relationship
        $dosenPenguji = Dosen::where('user_id', $this->penilaianDetail->user_id)->first();
        $this->dosenPenguji = $dosenPenguji ?? $this->penilaianDetail->dosen;
        
        // Generate QR Code immediately in constructor
        $this->generateQrCode();
    }
    
    protected function generateQrCode()
    {
        if (!$this->dosenPenguji || !$this->dosenPenguji->nip) {
            return;
        }
        
        try {
            // JSON data for QR Code
            $qrData = json_encode([
                'nip' => $this->dosenPenguji->nip,
                'nama' => ($this->dosenPenguji->front_title ?? '') . ' ' . 
                          ($this->dosenPenguji->nama_lengkap ?? '') . ', ' . 
                          ($this->dosenPenguji->back_title ?? '')
            ], JSON_UNESCAPED_UNICODE);
            
            // Generate QR Code with smaller size for PDF
            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_M, // Change from H to M to reduce size
                'scale' => 5, // Reduce from 10 to 5
                'imageBase64' => true,
            ]);
            
            $qrcode = new QRCode($options);
            $this->qrCodeBase64 = $qrcode->render($qrData);
            
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR Code for PDF: ' . $e->getMessage());
        }
    }

    public function getData()
    {
        return [
            'penilaian' => $this->penilaianDetail,
            'jadwal' => $this->jadwal,
            'calonDosen' => $this->calonDosen,
            'dosenPenguji' => $this->dosenPenguji,
            'qrCodeBase64' => $this->qrCodeBase64,
            'logoPath' => public_path('images/LogoTelkom.png'),
        ];
    }
}
