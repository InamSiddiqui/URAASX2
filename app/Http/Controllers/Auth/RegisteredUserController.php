<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // echo '<pre>'; print_r($request->all()); exit;
        $request->validate([
            //'name' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photo'=>'required|image|mimes:jpg,jpeg,png|max:1024',
            'documents.*'=>'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xlsx|max:2048',
        ],
        //custom message
        [
            'photo.max' => 'Photo size should be less than 1MB.',
            'documents.*.max' => 'Kindly make sure each document must be less than 2MB.'
        ]);

        try{
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->fname.' '.$request->lname,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // $user = User::find(1);

            // $imagePath = '/public/users';
            $imageName = time().'_'.$request->photo->getClientOriginalName();
            $imagePath = '/public/users/'.$user->id.'/'.$imageName;

            if(!empty($request->photo))
            if(Storage::disk('local')->put($imagePath, file_get_contents($request->photo))){
                $user->photo = $imagePath;
                $user->save();
            }

            if(!empty($request->documents))
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
               

            event(new Registered($user));
            Auth::login($user);

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('servererror', $e->getMessage());
        } 

        return redirect(RouteServiceProvider::HOME);
    }
}