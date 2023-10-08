<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Inertia\Inertia;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // echo '<pre>'; print_r($request->documents); exit;
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'documents.*'=>'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xlsx|max:1024',
            ]);

            if($validator->fails())
                return redirect()->route('profile.edit')->with('error', $validator->errors());


            $user = $request->user();

            foreach ($request->documents as $document) {
                $docOriginalName = $document->getClientOriginalName();
                $docName = time().'_'.$docOriginalName;

                $docPath = '/public/users/'.$user->id.'/documents/'.$docName;

                $isUploaded = Storage::disk('local')->put($docPath, file_get_contents($document));

                if($isUploaded){
                    Document::create([
                        'name'=> $docName,
                        'original_name'=>$docOriginalName,
                        'path'=> $docPath,
                        'user_id' => $user->id
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('profile.edit')->with('success', 'Document(s) uploaded');

        }catch(\Throwable $e){
            DB::rollBack();
            return redirect()->route('profile.edit')->with('servererror', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        try{
            $docPath = $document->path;
            if($document->delete()){
                $docPath = str_replace('/storage/', '/public/', $docPath);
                Storage::disk('local')->delete($docPath);

                return redirect()->route('profile.edit')->with('success', 'Document deleted!');
            }

            return redirect()->route('profile.edit')->with('error', 'Document not deleted!');
        }catch(\Throwable $e){
            return redirect()->route('profile.edit')->with('servererror', $e->getMessage());
        }
    }
}