<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $usuario = '';
    public string $password = '';

    public function authenticate()
    {
        $data = [
            'usuario' => $this->usuario,
            'password' => $this->password,
        ];

        $login = $this->LoginIntranet($data);

        if ($login['sucesso']) {
            $usuario = $login['usuario'];

            if (!$data['password']) {
                throw new \Exception('A senha do usuário está ausente.');
            }

            $usuario_local = User::where('matricula', $usuario->matricula)->first();

            if (!$usuario_local) {
                $usuario_local = User::create([
                    'name' => $usuario->nome,
                    'email' => $usuario->email,
                    'password' => Hash::make($usuario->password),
                    'matricula' => $usuario->matricula,
                    'status' => $usuario->status,
                ]);
            }

            if ($usuario_local) {
                Auth::login($usuario_local);
                return redirect('/gestao-espelho');
            }
        }

        throw new \Exception('Falha na autenticação.');
    }

    public function LoginIntranet($data)
    {
        try {
            $usuarioIntranet = UserAuth::where('login_intranet', $data['usuario'])
                ->where('status', 1)
                ->first();

            if (!$usuarioIntranet) {
                throw new \Exception('Usuário não encontrado ou inativo.');
            }

            $senhaAutenticada = crypt($data['password'], $usuarioIntranet->senha_intranet);

            $autenticacao = UserAuth::where([
                ['login_intranet', $data['usuario']],
                ['senha_intranet', $senhaAutenticada],
                ['status', '=', '1', $data['status']]
            ])->where('status', 1)->first();

            if (!$autenticacao) {
                throw new \Exception('Senha inválida.');
            }

            return ['sucesso' => true, 'usuario' => $usuarioIntranet];
        } catch (\Exception $e) {
            throw new \Exception('Erro: ' . $e->getMessage());
        }
    }

    public function LogoutIntranet()
    {
        auth()->logout();
        session()->flush();
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
