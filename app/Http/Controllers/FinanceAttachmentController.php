<?php
namespace App\Http\Controllers;
use App\Models\FinanceAttachment;
use App\Models\Wedding;
use App\Models\WeddingFinanceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class FinanceAttachmentController extends Controller { private function allowed(Request $request, Wedding $wedding): bool { $member = $request->user()->weddingMemberships()->where('wedding_id', $wedding->id)->first(); return $request->user()->role === 'admin' || ($member && data_get($member->permissions, 'view_finance')); } public function store(Request $request, Wedding $wedding, WeddingFinanceItem $item) { abort_unless($this->allowed($request, $wedding) && in_array($request->user()->role, ['admin','finance'], true) && $item->wedding_id === $wedding->id, 403); $data = $request->validate(['file' => ['required','file','max:10240','mimes:pdf,jpg,jpeg,png']]); $file = $data['file']; $path = $file->store("finance/{$wedding->id}", 'local'); $item->attachments()->create(['original_name' => $file->getClientOriginalName(), 'path' => $path, 'mime_type' => $file->getMimeType(), 'size' => $file->getSize(), 'uploaded_by' => $request->user()->id]); return back()->with('success','Comprobante privado cargado.'); } public function download(Request $request, Wedding $wedding, FinanceAttachment $attachment) { abort_unless($this->allowed($request, $wedding) && $attachment->weddingFinanceItem->wedding_id === $wedding->id, 403); return Storage::disk('local')->download($attachment->path, $attachment->original_name); } }
