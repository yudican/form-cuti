<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataFormPengajuan;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;

class DataFormPengajuanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    // public $hideable = 'select';
    public $table_name = 'tbl_data_form_pengajuan';


    public function builder()
    {
        if (auth()->user()->role->role_type === 'member') {
            return DataFormPengajuan::query()->where('user_id', auth()->user()->id);
        }
        return DataFormPengajuan::query()->where('status', '!=', 'draft');
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            // Column::name('nomor_sij')->label('SIJ Nomor')->searchable(),
            Column::name('user.name')->label('Nama')->searchable(),
            Column::name('tanggal_berangkat')->label('Tanggal Berangkat')->searchable(),
            Column::name('tanggal_kembali')->label('Tanggal Kembali')->searchable(),
            Column::name('tujuan')->label('Tujuan')->searchable(),
            Column::name('status')->label('Status')->searchable(),

            Column::callback(['id', 'status'], function ($id, $status) {
                $action = [];

                if ($status == 'draft') {
                    $action[] = [
                        'type' => 'button',
                        'route' => 'getDataById(' . $id . ')',
                        'label' => 'Edit',
                    ];
                    $action[] = [
                        'type' => 'button',
                        'route' => 'showDetail(' . $id . ')',
                        'label' => 'Detail',
                    ];
                    if (in_array(auth()->user()->role->role_type, ['admin', 'superadmin', 'member'])) {
                        $action[] = [
                            'type' => 'button',
                            'route' => 'updateStatus(' . $id . ',"diusulkan")',
                            'label' => 'Diusulkan',
                        ];
                    }
                }

                if (in_array($status, ['diusulkan', 'ditolak'])) {
                    $action[] = [
                        'type' => 'button',
                        'route' => 'showDetail(' . $id . ')',
                        'label' => 'Detail',
                    ];
                }

                if ($status == 'disetujui') {
                    $action[] = [
                        'type' => 'button',
                        'route' => 'showDetail(' . $id . ')',
                        'label' => 'Detail',
                    ];
                    $action[] = [
                        'type' => 'button',
                        'route' => "download($id,'pdf')",
                        'label' => 'Download PDF',
                    ];
                }

                // $action[] = [
                //     'type' => 'button',
                //     'route' => 'getId(' . $id . ')',
                //     'label' => 'Hapus',
                // ];



                return view('crud-generator-components::action-button', [
                    'id' => $id,
                    'actions' => $action
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataDataFormPengajuanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getDataFormPengajuanId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function showDetail($id)
    {
        $this->emit('getDataFormPengajuanId', $id);
        $this->emit('showModalDetail', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function download($id, $type)
    {
        $form = DataFormPengajuan::find($id);
        // Load the Word template
        $template = new TemplateProcessor(public_path('assets/sij.docx'));
        // Replace variables in the template with the values passed in

        $template->setValue('sij_nomor', 'SIJ/' . date('m') . '/' . date('Y'));
        $template->setValue('nama', $form->user_name);
        $template->setValue('pangkat', $form->pangkat);
        $template->setValue('asal', 'Sorong');
        $template->setValue('tujuan', $form->tujuan);
        $template->setValue('keperluan', $form->keperluan ?? '-');
        $template->setValue('tgl_berangkat', date('d M Y', strtotime($form->tanggal_berangkat)));
        $template->setValue('tgl_kembali', date('d M Y', strtotime($form->tanggal_kembali)));
        $template->setValue('tgl_disetujui', date(',.... M Y', strtotime($form->tanggal_disetujui)));
        $template->setValue('pengikut', $form->pengikut ?? '-');
        $template->setValue('transportasi', $form->transportasi ?? '-');

        // add days 1
        $tanggal_apel = date('d M Y', strtotime($form->tanggal_kembali . ' +1 day'));
        $template->setValue('keterangan', "- {$tanggal_apel} Sudah apel api di Mako Koarmada III \n Tiba ditempat segera laporan TNI Setempat");

        // Save the modified template to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'word_template');
        $template->saveAs($tempFile);

        if ($type == 'pdf') {
            // Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            // Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

            // // Convert the temporary file to PDF
            // $phpWord = IOFactory::load($tempFile);
            // $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            // $pdfFile = tempnam(sys_get_temp_dir(), 'pdf');
            // $pdfWriter->save($pdfFile);
            $client = new Client();

            $instructions = '{
                "parts": [
                    {
                    "file": "document"
                    }
                ]
            }';

            $response = $client->post('https://api.pspdfkit.com/build', [
                'headers' => [
                    'Authorization' => 'Bearer pdf_live_2nxm8Se1xwfHEl5xgoyrlxmWUi8scT4KMbmyARMWENE',
                ],
                'multipart' => [
                    [
                        'name' => 'instructions',
                        'contents' => $instructions,
                    ],
                    [
                        'name' => 'document',
                        'contents' => fopen($tempFile, 'r'),
                    ],
                ],
            ]);

            // If the request was successful, download the response content to a file
            if ($response->getStatusCode() === 200) {
                $pdfContent = $response->getBody();

                // Save the PDF file
                $pdfFilePath = str_replace('/', '-', $form->pangkat) . '.pdf';
                Storage::disk('public')->put($pdfFilePath, $pdfContent);
                return Storage::disk('public')->download($pdfFilePath);
            } else {
                return response()->json(['message' => 'File conversion failed.'], 500);
            }

            // $phpWord->save($pdfFile, 'PDF');


        }

        // Send the PDF file to the browser for download
        return response()->download($tempFile, 'file.' . $type)->deleteFileAfterSend(true);
    }

    public function updateStatus($id, $status)
    {
        $form = DataFormPengajuan::find($id);
        $form->status = $status;
        $form->save();
        $this->refreshTable();
        $this->emit('showAlert', ['msg' => 'Berhasil mengubah status pengajuan']);
    }
}
