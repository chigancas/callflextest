<?php  

namespace src\core;

use src\core\Database;

class Authentication extends Database{

    private $db;

    public function getUser(string $alias, string $password)
    {
        $this->db = new database;

        $result = $this->db->select("users",0,["*"],array("column" => array("alias"), "value" => array($alias)));

        $result = json_decode($result);

        if($result->total) {
            
            $hashed_password = $result->result[0]->password;

            if(password_verify($password, $hashed_password)) {

                $_SESSION["role"] = $result->result[0]->role;
                $_SESSION["user_id"] = $result->result[0]->id_user;
            
                return "OK";

            }else
            {

                return "Usuário ou senha não conferem!";

            }

        }else
        {

            return "Usuário não existe, faça seu cadastro!";

        }

    }

    public function resetLink(string $email)
    {   

        $this->db = new database;

        $result = $this->db->select("users",0,["*"],array("column" => array("email"), "value" => array($email)));

        if($result["total"]) {
            
            // $from = "";
            // $to = $email;

            // $subject = "Alteração de Senha";

            // $message = "";

            // $headers = "De:" . $from;

            // $mail = mail($to, $subject, $message, $headers);

            if(true)
            {
                return "Success";
            }

        }else
        {

            return "Usuário não existe, faça seu cadastro!";

        }

    }

    public function resetPassword(string $email, string $oldPass, string $newPass)
    {   

        $this->db = new database;

        $result = $this->db->select("users",0,["*"],array("column" => array("email"), "value" => array($email)));

        if($result["total"]) {
            
            $hashed_password = $result["result"][0]->password; 

            if(password_verify($oldPass, $hashed_password)) {
            
                $result = $this->db->update("users",array("column" => array("password"), "value" => array($this->cryptPassword($newPass))), array("column" => array("password"), "value" => array($hashed_password)),null);

                return "Senha atualizada com sucesso!";

            }else
            {

                return "Usuário ou senha atual não conferem!";

            }

        }else
        {

            return "Usuário não existe, faça seu cadastro!";

        }

    }

    private function cryptPassword(string $password)
    {

        return password_hash($password, PASSWORD_BCRYPT);

    }

    public function addUser(string $nome, string $sobrenome, string $email, string $alias, string $senha, int $role = 2)
    {

        $this->db = new database;

        $result = $this->db->select("users",0,["*"],array("column" => array("alias"), "value" => array($alias)));

        $result = json_decode($result);

        if($result->total) {

            return "Já existe um usuário cadastrado com este e-mail, tente fazer login!";

        }else
        {

            $result = $this->db->insert("Users",
            array(
                "column" => array(
                    "name",
                    "lastname",
                    "email",
                    "alias",
                    "password",
                    "role"
                ), 
                "value" => array(
                    $nome, 
                    $sobrenome, 
                    $email, 
                    $alias,
                    $this->cryptPassword($senha), 
                    $role
                )
            ), null);

            return "Cadastro realizado com sucesso";

        }

    }

}

?>