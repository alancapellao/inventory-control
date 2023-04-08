<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Managers\DatabaseManager;

class UsuarioController extends Controller
{
    protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function login(Request $request)
    {
        // try {
        $data = $request->only(['email', 'password']);

        $email = strtolower($data['email']);
        $password = $data['password'];

        if (isset($email) && !empty($email) && isset($password) && !empty($password)) {
            $login = $this->db->query("SELECT password FROM usuarios WHERE email = '$email'");

            if (count($login) > 0) {
                $user = $login[0];
                if (password_verify($password, $user->password)) {
                    return response()->json(array('erro' => false, 'mensagem' => 'Login successful!'));
                }
            }
        }

        return response()->json(array('erro' => true, 'mensagem' => 'Incorrect email or password.'));

        // } catch (\Throwable $th) {
        //     return response()->json(array('erro' => true, 'mensagem' => 'Erro ao fazer login: ' . $th->getMessage()));
        // }
    }

    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);

        $name = $data['name'];
        $email = strtolower($data['email']);
        $password = $data['password'];

        $options = [
            'cost' => 12,
        ];

        if (isset($name) && !empty($name) && isset($email) && !empty($email) && isset($password) && !empty($password)) {

            $select = $this->db->query("SELECT email FROM usuarios WHERE email = '$email'");

            if (count($select)) {
                return response()->json(array('erro' => true, 'mensagem' => 'E-mail already registered.'));
            } else {
                $password_criptd = password_hash($password, PASSWORD_BCRYPT, $options);

                $sql = "INSERT INTO usuarios(name, email, password) VALUES('$name', '$email', '$password_criptd')";
                $this->db->query($sql);

                return response()->json(array('erro' => true, 'mensagem' => 'Registered successfully!'));
            }
        }
    }
}
