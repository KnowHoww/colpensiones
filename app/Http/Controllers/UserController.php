<?php

namespace App\Http\Controllers;

use App\Mail\RecuperacionDeContrasena;
use App\Models\CentroCostos;
use App\Models\Municipio;
use App\Models\LogsAplicacion;
use App\Models\States;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:usuarios.view')->only('index');
        $this->middleware('can:usuarios.view.btn-create')->only('create', 'store');
        $this->middleware('can:usuarios.view.btn-edit')->only('edit', 'update');
    }

    public function editProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('usuarios.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $fechaActualizarPassword = Carbon::parse($user->fechaActualizarPassword);
        $fechaActualizarPassword = $fechaActualizarPassword->addDays(30)->equalTo(now());

        if ($request->old_password !== $request->new_password && preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9])[A-Za-z\d^a-zA-Z0-9].{6,}$/', $request->new_password)) {
            $user->password = Hash::make($request->new_password);
            $user->ActualizarPassword = 1;
            $user->fechaActualizarPassword = now()->timezone('America/Bogota');
            $resultado = $user->save();
            if ($user->save()) {
                return back()->with('info', 'Información actualizada correctamente.');
            } else {
                return back()->with('infoError', 'Verifique la información.');
            }
        } else {
            return back()->with('infoError', 'Verifique la información.');
        }
    }

    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $costos = CentroCostos::all();
        $municipios = Municipio::all();
        $estados = States::whereIn('id', [1, 2])->get();
        $tipoDocumento = TipoDocumento::all();
        $coordinador = User::role('Coordinador Colpensiones')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->get();
        return view('usuarios.create', compact('roles', 'estados', 'costos', 'tipoDocumento', 'coordinador', 'municipios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion =  DB::table('users')->where('numberDocument', '=', $request->numberDocument)->get()->count();
        if ($validacion == 0) {
            $user = User::create([
                'name' => ($request->name),
                'lastname' => ($request->lastname),
                'email' => strtolower($request->email),
                'phone' => $request->phone,
                'idTypeDocument' => $request->idTypeDocument,
                'numberDocument' => $request->numberDocument,
                'email_verified_at' => now(),
                'password' => Hash::make($request->numberDocument),
                'estado' => $request->estado,
                'centroCosto' => $request->centroCosto,
                'coordinador' => $request->coordinador,
                'municipio' => $request->municipio,
                'ActualizarPassword' => 0
            ]);
            $user->roles()->sync($request->roles);
            return redirect('/usuarios')->with('info', 'El registro de ' . $request->name . ' ' . $request->lastname . ' ha sido creado.');
        } else {
            return redirect('/usuarios')->with('info', '' . $request->name . ' ' . $request->lastname . ' ya se encuentra registrado.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        $user = User::find($user);
        $roles = Role::all();
        $costos = CentroCostos::all();
        $municipios = Municipio::all();
        $estados = States::whereIn('id', [1, 2])->get();
        $tipoDocumento = TipoDocumento::all();
        $coordinador = User::role('Coordinador Colpensiones')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->get();
        return view('usuarios.edit', compact('user', 'roles', 'costos', 'estados', 'tipoDocumento', 'coordinador', 'municipios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user)
    {
        $userRow = User::find($user);
        $userRow->update($request->all());
        $userRow->roles()->sync($request->roles);
        return redirect('/usuarios')->with('info', 'El registro de ' . $request->name . ' ' . $request->lastname . ' ha sido actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->estado != 1) {
                LogsAplicacion::create([
                    'idUsuario' => Auth::user()->id,
                    'Observacion' => 'Inicio de sesion realizado fallido para ' . $request->email,
                ]);
                Auth::logout();
                return redirect()->back()->with(['info' => 'Usuario inactivo.']);
            }

            if (Auth::user()->ActualizarPassword == 0) {
                return redirect('/edituser');
                LogsAplicacion::create([
                    'idUsuario' => Auth::user()->id,
                    'Observacion' => 'Solicitud de actualización de contraseña',
                ]);
            } else {
                LogsAplicacion::create([
                    'idUsuario' => Auth::user()->id,
                    'Observacion' => 'Inicio de sesion realizado correctamente',
                ]);

                switch (Auth::user()->roles->pluck('id')[0]) {
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 11:
                        return redirect('/dashboard');
                        break;
                    case 7:
                        return redirect('/migrupo');
                        break;
                    case 8:
                        return redirect('/investigacionesTodas');
                        break;
                    case 9:
                    case 12:
                        return redirect('/misinvestigaciones');
                        break;
                    default:
                        return redirect('/dashboard');
                        break;
                }
            }
        }
        return redirect()->back()->with('info', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Función de generación de contraseña segura
        function generateSecurePassword($length = 12) {
            $lowercase = 'abcdefghijklmnopqrstuvwxyz';
            $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $numbers = '0123456789';
            $special = '!@#$%^&*()_-=+;:,.?';
            
            // Asegurar al menos un carácter de cada tipo
            $password = [
                $lowercase[random_int(0, strlen($lowercase) - 1)],
                $uppercase[random_int(0, strlen($uppercase) - 1)],
                $numbers[random_int(0, strlen($numbers) - 1)],
                $special[random_int(0, strlen($special) - 1)]
            ];
            
            // Completar con caracteres aleatorios
            $allChars = $lowercase . $uppercase . $numbers . $special;
            for ($i = 4; $i < $length; $i++) {
                $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
            }
            
            // Mezclar todos los caracteres
            shuffle($password);
            
            return implode('', $password);
        }
        
        // Genera una contraseña segura en lugar de la anterior
        $new_pass = generateSecurePassword();
        
        // Genera firma (manteniendo tu lógica actual o mejorándola)
        $signature = bin2hex(random_bytes(32)); // Más seguro que str_shuffle para la firma
        
        $usuario = User::where('email', $request->email)->first();
        
        if (!$usuario) {
            return back()->with('info', 'El correo electrónico ingresado no se encuentra registrado.');
        }
        
        if ($usuario->estado != 1) {
            Auth::logout();
            return back()->with('info', 'Usuario inactivo');
        }
        
        $usuario->password = Hash::make($new_pass);
        $usuario->ActualizarPassword = 0;
        $usuario->update($request->all());

        $data = [
            'name' => $usuario->name,
            'password' => $new_pass, 
            'signature' => $signature
        ];
        
        try {
            Mail::to($request->email)->send(new RecuperacionDeContrasena($data));
            return redirect('/login')->with('Restablecimiento', 'Restablecimiento de contraseña correcto, revisa tu correo.');
        } catch (\Exception $e) {
            // Revertir los cambios en la contraseña
            $usuario->password = $usuario->getOriginal('password');
            $usuario->ActualizarPassword = 1;
            $usuario->save();
            
            // Registrar el error
            \Log::error('Error al enviar email de recuperación: ' . $e->getMessage());
            
            return back()->with('info', 'No se pudo enviar el correo de recuperación. Por favor, verifica que tu dirección de correo sea correcta o contacta al administrador.');
        }
    }


    public function sendResetLinkEmail2(Request $request)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Genera una cadena aleatoria de 8 caracteres
        $new_pass = substr(str_shuffle($caracteres), 0, 12);
        $usuario = User::where('email', $request->email)->first();
        //$user['password'] = Hash::make($request->numberDocument);

        if ($usuario) {
            $usuario->password = Hash::make($new_pass);
            $usuario->ActualizarPassword = 0;
            $usuario->update($request->all());

            $data = [
                'password' => $new_pass // Aquí puedes pasar el código generado aleatoriamente
            ];

            return $new_pass; // Devolver la nueva contraseña generada
        } else {
            return 'El correo electrónico ingresado no se encuentra registrado.';
        }
    }
}
