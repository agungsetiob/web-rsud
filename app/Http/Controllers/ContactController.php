<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Models\Complain;
use Auth;
use URL;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $messages = Contact::all();
            return view('admin.message', compact('messages'));
        }else{
            return back()->with('error', 'Aku pasrahkan hidupku padamu Tuhan');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = URL('/') . '#contact';
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'message'   => 'required|min:25'
        ]);

        //create post
        if ($validator->fails()) {
            return redirect($url)
            ->withErrors($validator)
            ->withInput();
        } else{
            Contact::create([
            'name'    => addslashes($request->name),
            'email'   => $request->email,
            'message' => $request->message
        ]);

        //redirect to index
            return redirect($url)->with('success', 'Message sent');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->role == 'admin') {
            $mes = Contact::findOrFail($id);
        //delete post
            $mes->delete();

        //redirect to index
            return redirect('messages')->with(['success' => 'Data deleted succesfully']);
        } else{
            return redirect()->back()->with('error', 'ingatlah dunia hanya sementara');
        }
    }




    /**
     * Pengaduan masyarakat.
     *
     * 
     * 
     */
    public function pengaduan()
    {
        return view('admin.kendala');
    }

    public function simpanPengaduan(Request $request)
    {
        $this->validate($request, [
            'unit'      => 'required',
            'complain'  => 'required|min:20',
            'name'      => 'required',
            'address'   => 'required',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11',
        ]);

        Complain::create([
            'unit'   => $request->unit,
            'complain'  => addslashes($request->complain),
            'name'      => addslashes($request->name),
            'address'   => addslashes($request->address),
            'phone'     => $request->phone,
        ]);

        return redirect('pengaduan-masyarakat')->with(['success' => 'Berhasil kirim pengaduan']);
    }

    public function daftarPengaduan ()
    {
        $kendalas = Complain::all();
        return view('admin.daftar-kendala', compact('kendalas'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapusPengaduan($id)
    {
        if (Auth::user()->role == 'admin') {
           //do something here man

        //redirect to index
            return redirect('messages')->with(['success' => 'Data deleted succesfully']);
        } else{
            return redirect()->back()->with('error', 'ingatlah dunia hanya sementara');
        }
    }



}
