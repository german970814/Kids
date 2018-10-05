<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Usuario;
use App\Libraries\Form;

class UsuarioController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function __construct()
    {
        $this->middleware('guest', ['only' => ['create', 'store']]);
        $this->middleware('auth', ['except' => ['create', 'store', 'get_user_profile_photo']]);
    }

    public function index() {
        $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    public function profile() {
        $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    public function create() {
        return view('usuarios.create');
    }

    public function store(Request $request) {
        $validated_data = $request->validate([
            'sexo' => 'required',
            'nombres' => 'required',
            'password' => 'required',
            'apellidos' => 'required',
            'tipo_usuario' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'email' => 'required|string|email|max:255|unique:users'
        ], $this->messages);

        try {
            DB::beginTransaction();
            $user = Usuario::create_user($validated_data);
            // echo var_dump(array_merge($validated_data, ['user_id' => $user->id]));
            $usuario = Usuario::create(
                array_merge($validated_data, ['user_id' => $user->id])
            );
            DB::commit();
            \Auth::login($user);

            return redirect()->route('usuario.show', $usuario->id);
        } catch(\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id) {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    public function edit($id) {
        $usuario = Usuario::findOrFail($id);
        $form_fields = ['nombres', 'apellidos', 'sexo', 'tipo_documento', 'numero_documento', 'grupo_etnico'];
        // $form = new Form('\App\Models\Usuario', $form);
        $form = new Form($usuario, $form_fields);
        return view('usuarios.edit', ['usuario' => $usuario, 'form' => $form]);
    }

    public function update(Request $request, $id) {
        $validated_data = $request->validate([
            'sexo' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
        ], $this->messages);

        try {
            DB::beginTransaction();
            $usuario = Usuario::find($id);
            $usuario->update(array_merge($validated_data));
            $user = $usuario->user->update($validated_data);
            DB::commit();

            return redirect()->route('usuario.edit', $usuario->id);
        } catch(\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function change_profile_photo(Request $request) {
        $validation = $request->validate([
            'photo' => 'required|file|image|mimes:jpeg,png,gif,webp|max:2048'
        ]);

        $usuario = \Auth::guard()->user()->usuario;
        $file = $validation['photo'];
        $filename = sprintf(
            'profiles/usuario-%s.%s', $usuario->id, $file->getClientOriginalExtension()
        );

        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
            $usuario->update(['profile_photo' => $filename]);
        }

        return redirect()->route('usuario.edit', $usuario->id);
    }

    public function get_user_profile_photo($id) {
        $usuario = Usuario::findOrFail($id);

        if (Storage::disk('local')->has($usuario->profile_photo)) {
            $file = Storage::disk('local')->get($usuario->profile_photo);
            return new Response($file, 200);
        }
    }

    public function amigos($id=null) {
        if ($id) {
            $usuario = Usuario::find($id);
        } else {
            $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        }
        return view('usuarios.amigos', ['usuario' => $usuario]);
    }
}
