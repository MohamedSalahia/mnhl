<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhatsappTemplateRequest;
use App\Models\WhatsappTemplate;
use Yajra\DataTables\DataTables;

class WhatsappTemplateController extends Controller
{
    public function index()
    {
        return view('admin.whatsapp_templates.index');

    }// end of index

    public function data()
    {
        $templates = WhatsappTemplate::query();

        return DataTables::of($templates)
            ->addColumn('record_select', 'admin.whatsapp_templates.data_table.record_select')
            ->addColumn('status', function (WhatsappTemplate $template) {
                $badge = $template->is_active ? 'success' : 'danger';
                $label = $template->is_active ? __('site.active') : __('site.inactive');
                return '<span class="badge badge-' . $badge . '">' . $label . '</span>';
            })
            ->editColumn('created_at', function (WhatsappTemplate $template) {
                return $template->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.whatsapp_templates.data_table.actions')
            ->rawColumns(['record_select', 'status', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('admin.whatsapp_templates.create');

    }// end of create

    public function store(WhatsappTemplateRequest $request)
    {
        WhatsappTemplate::create([
            'type'        => $request->type,
            'title'       => $request->title,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.whatsapp_templates.index')
        ]);

    }// end of store

    public function edit(WhatsappTemplate $whatsapp_template)
    {
        return view('admin.whatsapp_templates.edit', compact('whatsapp_template'));

    }// end of edit

    public function update(WhatsappTemplateRequest $request, WhatsappTemplate $whatsapp_template)
    {
        $whatsapp_template->update([
            'type'        => $request->type,
            'title'       => $request->title,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.whatsapp_templates.index')
        ]);

    }// end of update

    public function destroy(WhatsappTemplate $whatsapp_template)
    {
        $whatsapp_template->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {
            WhatsappTemplate::findOrFail($recordId)->delete();
        }

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('admin.whatsapp_templates.index');

    }// end of bulkDelete

}// end of controller
