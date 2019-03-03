<?php
class Database {
    public function __construct() {
        $this->connect = require __DIR__ . '/connect.php';
    }
    public function check_login($login, $password) {
        try{
            $query = $this->connect->prepare("SELECT id, login, password, email, permissions, block FROM users WHERE login = :login OR email = :login2 AND login != ''");
            $query->bindValue(':login', $login, PDO::PARAM_STR);
            $query->bindValue(':login2', $login, PDO::PARAM_STR);
            $query->execute();


        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $return;
        if($query->rowCount() == 1) {
            $result = $query->fetch();
            if($result["block"] == 1) {
                $return["check"] = false;
                $return["err_message"] = "Twoje konto zostało zablokowane";
                return $return;
            }
            if(password_verify($password, $result["password"])) {
                $return["check"] = true;
                $return["login"] = $result["login"];
                $return["id"] = $result["id"];
                $return["email"] = $result["email"];

                try {
                    $query2 = $this->connect->prepare('SELECT * FROM permissions WHERE level = :level');
                    $query2->bindValue(':level', $result["permissions"], PDO::PARAM_INT);
                    $query2->execute();
                } catch(PDOException $error) {
                    add_log('[Error] Nieudane zapytanie. '. $error);
                    exit('Błąd łączenia z bazą danych');
                }
                if($query2->rowCount() == 1) {
                    $result2 = $query2->fetch();
                    $return["permissions"] = $result2;
                    return $return;
                } else {
                    add_log('[Error] Nie udało się pobrać informacji o uprawnieniach');
                    exit('Błąd pobierania danych');
                }
            } else {
                require_once __DIR__ . '/../utils.php';
                add_log('Nie udane logowanie na konto użytkownika: ' . $result["login"]);
                $return["check"] = false;
                $return["err_message"] = "Niewłaściwe hasło";
                return $return;
            }
        } else {
            $return["check"] = false;
            $return["err_message"] = "Niewłaściwy login";
            return $return;
        }
    }
    public function check_password($password, $user_id) {
        try{
            $query = $this->connect->prepare("SELECT password FROM users WHERE id = :id");
            $query->bindValue(':id', $user_id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            $result = $query->fetch();
            if(password_verify($password, $result["password"])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function get_priorities() {
        try{
            $query = $this->connect->prepare("SELECT name, level, color FROM priorities");
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $priorities = array();
        $prio = $query->fetchAll();
        foreach($prio as $p) {
            $priorities[$p["level"]] = array(
                "name" => $p["name"],
                "color" => $p["color"],
            );
        }
        return $priorities;
    }
    public function get_project_list($args) {
        $select = '
SELECT projects.id, projects.name, projects.intro, projects.start_date, projects.end_date, projects.priority';
        $from = '
FROM projects 
LEFT JOIN join_groups ON join_groups.user_id = ' . $args['user_id'] . ' AND projects.only_group = join_groups.group_id
LEFT JOIN allocations ON allocations.user_id = ' . $args['user_id'] . ' AND projects.id = allocations.project_id
LEFT JOIN users ON users.id = ' . $args['user_id'];
        
        $where = '
WHERE ' . $args["filter"] . ' AND (projects.only_group = 0 OR users.permissions = 99 OR (projects.only_group = join_groups.group_id AND join_groups.user_id = ' . $args['user_id'] . '))';

        $like = $args["phrase"] != '' ? '
AND (projects.name LIKE "%' .$args["phrase"] . '%" OR projects.client LIKE "%' .$args["phrase"] . '%")'
        : '';

        $direction = (int)$args["direction"] == 1 ? ' DESC' : ''; 

        $orderby = '
ORDER BY projects.' . $args["sort"] . $direction . ', projects.id';
        
        $offset = '
OFFSET ' . $args["offset"];
        $limit = '
LIMIT ' . $args["limit"];

        try{
            $query = $this->connect->prepare(
                $select .$from . $where . $like . $orderby . $limit . $offset
            );
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $return = array();
        if($query->rowCount() > 0) {
            $return["data"] = $query->fetchAll();
            if(isset($args["limit"])) {
                $return["end"] = ($query->rowCount() < $args["limit"]) ? true : false;
            } else {
                $return["end"] = true;
            }
            
        } else {
            $return["data"] = array();
            $return["end"] = true;
        }
        // $return["query"] = $query->queryString;
        return $return;
    }
    public function get_project($id, $user_id) {
        try{
            $query = $this->connect->prepare("SELECT * FROM projects LEFT JOIN join_groups ON join_groups.group_id = projects.only_group LEFT JOIN users ON users.id = :user_id WHERE projects.id = :id AND (projects.only_group = 0 OR users.permissions = 99 OR (projects.only_group = join_groups.group_id AND join_groups.user_id = :user_id2))");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $query->bindValue(':user_id2', $user_id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        
        if($query->rowCount() == 1) {
            $query2 = $this->connect->prepare("SELECT * FROM attachments WHERE `project_id` = :id");
            $query2->bindValue(':id', $id, PDO::PARAM_INT);
            $query2->execute();

            $return["search"] = true;
            $return["data"] = $query->fetch();
            $return["attachments"] = $query2->fetchAll();
            return $return;
        } else {
            $return["search"] = false;
            return $return;
        }
    }
    public function get_group_name($id) {
        try{
            $query = $this->connect->prepare("SELECT name FROM groups WHERE id = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            return $query->fetch()["name"];
        } else {
            return "Nie znaleziono grupy";
        }
    }
    public function get_user_groups($id) {
        try{
            $query = $this->connect->prepare("SELECT groups.id, groups.name FROM join_groups INNER JOIN groups ON join_groups.group_id = groups.id WHERE join_groups.user_id = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        return $query->fetchAll();
    }
    public function is_user_name($name) {
        try{
            $query = $this->connect->prepare("SELECT id FROM users WHERE login = :name");
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }
    public function is_user_email($email) {
        try{
            $query = $this->connect->prepare("SELECT id FROM users WHERE email = :email");
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }
    public function update_user_login($login, $id) {
        try{
            $query = $this->connect->prepare("UPDATE users SET login = :login WHERE id = :id");
            $query->bindValue(':login', $login, PDO::PARAM_STR);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function update_user_email($email, $id) {
        try{
            $query = $this->connect->prepare("UPDATE users SET email = :email WHERE id = :id");
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function update_user_password($password, $id) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try{
            $query = $this->connect->prepare("UPDATE users SET password = :password WHERE id = :id");
            $query->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function add_temp_code($type, $id, $data) {
        $code = generateRandomString(84);
        try {
            $query = $this->connect->prepare("SELECT id FROM temp_codes WHERE type = :type AND user_id = :id");
            $query->bindValue(':type', $type, PDO::PARAM_INT);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() === 0) {
            try {
                $query2 = $this->connect->prepare("INSERT INTO `temp_codes` (`id`, `type`, `user_id`, `code`, `date`, `data`) VALUES (NULL, :type, :id, :code, CURRENT_TIMESTAMP, :data)");
                $query2->bindValue(':type', $type, PDO::PARAM_INT);
                $query2->bindValue(':id', $id, PDO::PARAM_INT);
                $query2->bindValue(':code', $code, PDO::PARAM_STR);
                $query2->bindValue(':data', $data, PDO::PARAM_STR);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        } else {
            try {
                $query2 = $this->connect->prepare("UPDATE temp_codes SET code = :code WHERE type = :type AND user_id = :id");
                $query2->bindValue(':type', $type, PDO::PARAM_INT);
                $query2->bindValue(':id', $id, PDO::PARAM_INT);
                $query2->bindValue(':code', $code, PDO::PARAM_STR);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
        return $code;
    }
    public function check_temp_code($type, $id, $code) {
        try {
            $query = $this->connect->prepare("SELECT data FROM temp_codes WHERE type = :type AND user_id = :id AND code = :code");
            $query->bindValue(':type', $type, PDO::PARAM_INT);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':code', $code, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() === 1) {
            $return = $query->fetch()["data"];
            return $return;
        } else {
            return false;
        }
    }
    public function delete_temp_code($type, $id) {
        try {
            $query = $this->connect->prepare("DELETE FROM temp_codes WHERE user_id = :id AND type = :type");
            $query->bindValue(':type', $type, PDO::PARAM_INT);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function get_user($id) {
        try {
            $query = $this->connect->prepare("SELECT * FROM users WHERE id = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }

        if($query->rowCount() == 1) {
            $result = $query->fetch();
        } else {
            return false;
        }

        try {
            $query2 = $this->connect->prepare('SELECT * FROM permissions WHERE level = :level');
            $query2->bindValue(':level', $result["permissions"], PDO::PARAM_INT);
            $query2->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $result2 = $query2->fetch();
        $result["permissions"] = $result2;
        return $result;

    }
    public function get_all_users() {
        try {
            $query = $this->connect->prepare("SELECT id, login, email, permissions, block FROM users WHERE permissions != 99");
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $result = $query->fetchAll();
        return $result;
    }
    public function get_all_groups() {
        try {
            $query = $this->connect->prepare("SELECT id, name FROM groups");
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $groups = $query->fetchAll();
        for($i = 0; $i < sizeof($groups); $i++) {
            $group = $groups[$i];
            try {
                $query2 = $this->connect->prepare("SELECT users.login, users.id FROM join_groups RIGHT JOIN users ON join_groups.user_id = users.id WHERE join_groups.group_id = :id");
                $query2->bindValue(':id', $group["id"], PDO::PARAM_INT);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
            $users = $query2->fetchAll();
            $groups[$i]["users"] = $users;
        }
        return $groups;
    }
    public function get_project_members($project_id) {
        try {
            $query = $this->connect->prepare("SELECT users.id, users.login FROM allocations RIGHT JOIN users ON users.id = allocations.user_id WHERE allocations.project_id = :id");
            $query->bindValue(':id', $project_id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        return $query->fetchAll();
    }
    public function get_permissions() {
        try {
            $query = $this->connect->prepare("SELECT level, name FROM permissions WHERE level != 99");
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $result = $query->fetchAll();
        return $result;
    }
    public function delete_user($id) {
        try {
            $query = $this->connect->prepare("DELETE FROM users WHERE users.id = :id AND users.permissions != 99");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query2 = $this->connect->prepare("DELETE FROM temp_codes WHERE temp_codes.user_id = :id");
            $query2->bindValue(':id', $id, PDO::PARAM_INT);
            $query2->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query3 = $this->connect->prepare("DELETE FROM allocations WHERE user_id = :id");
            $query3->bindValue(':id', $id, PDO::PARAM_INT);
            $query3->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query4 = $this->connect->prepare("DELETE FROM join_groups WHERE user_id = :id");
            $query4->bindValue(':id', $id, PDO::PARAM_INT);
            $query4->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function init_user($email, $permissions) {
        try {
            $query = $this->connect->prepare("INSERT INTO `users` (`id`, `login`, `password`, `email`, `permissions`, `block`) VALUES (NULL, '', '', :email, :permissions, '0')");
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->bindValue(':permissions', $permissions, PDO::PARAM_INT);

            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function get_user_id_by_email($email) {
        try {
            $query = $this->connect->prepare("SELECT id FROM users WHERE email = :email");
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            return $query->fetch()["id"];
        } else {
            return false;
        }
    }
    public function check_permissions($user_id) {
        try {
            $query = $this->connect->prepare("SELECT permissions FROM users WHERE id = :id");
            $query->bindValue(':id', $user_id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            $permissions = $query->fetch()["permissions"];
            try {
                $query2 = $this->connect->prepare('SELECT * FROM permissions WHERE level = :level');
                $query2->bindValue(':level', $permissions, PDO::PARAM_INT);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
            $result = $query2->fetch();
        } else {
            add_log('[Error] Nie znaleziono użytkownika do aktualizacji uprawnień.');
            exit('Błąd łączenia z bazą danych');
        }
        return $result;
    }
    public function update_user_permissions($id, $permissions) {
        try {
            $query = $this->connect->prepare('UPDATE users SET permissions = :permissions WHERE id = :id AND permissions != 99');
            $query->bindValue(':permissions', $permissions, PDO::PARAM_INT);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function is_lock_account($id) {
        try {
            $query = $this->connect->prepare('SELECT block FROM users WHERE id = :id');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            $result = $query->fetch();
            $result = (bool)$result["block"];
            return $result;
        } else {
            add_log('[Error] Nie udało się wyszukać użytkownika w funkcji is_lock_account()');
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function lock_account($id, $value) {
        try {
            $query = $this->connect->prepare('UPDATE users SET block = :val WHERE id = :id AND permissions != 99');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':val', $value, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function get_group($id, $user_id, $permissions_level) {
        try {
            $query = $this->connect->prepare('SELECT name FROM groups WHERE id = :id');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query2 = $this->connect->prepare('SELECT users.login, users.id FROM join_groups RIGHT JOIN users ON join_groups.user_id = users.id WHERE join_groups.group_id = :id');
            $query2->bindValue(':id', $id, PDO::PARAM_INT);
            $query2->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query3 = $this->connect->prepare('SELECT name, id, priority FROM projects WHERE only_group = :id');
            $query3->bindValue(':id', $id, PDO::PARAM_INT);
            $query3->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            $users = $query2->fetchAll();
            $test = false;

            foreach($users as $user) {
                if($user["id"] == $user_id) {
                    $test = true;
                }
            }
            if($permissions_level == 99) {
                $test = true;
            }

            $result = array(
                "name" => $query->fetch()["name"],
                "users" => $users,
                "projects" => $query3->fetchAll()
            );

            if($test) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }    
    }
    public function delete_group($id) {
        try {
            $query = $this->connect->prepare('DELETE FROM groups WHERE id = :id');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query2 = $this->connect->prepare('DELETE FROM join_groups WHERE group_id = :id');
            $query2->bindValue(':id', $id, PDO::PARAM_INT);
            $query2->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        try {
            $query3 = $this->connect->prepare('UPDATE projects SET only_group = 0 WHERE only_group = :id');
            $query3->bindValue(':id', $id, PDO::PARAM_INT);
            $query3->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function update_group($id, $add, $delete, $name) {
        try {
            $query = $this->connect->prepare('SELECT user_id FROM join_groups WHERE group_id = :id');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }

        $group_current_users = $query->fetchAll();
        $group_add = array();

        for($i = 0; $i < sizeof($add); $i++) {
            $test = true;
            foreach($group_current_users as $user) {
                if($user["user_id"] == $add[$i]) {
                    $test = false;
                }
            }
            if($test) {
                array_push($group_add, $add[$i]);
            }
        }
        if($group_add) {
            $values = '';
            for($i = 0; $i < sizeof($group_add); $i++) {
                if($i != 0) {
                    $values .= ', ';
                }
                $values .= '(NULL, ' . $group_add[$i] . ', ' . $id . ')';
            }

            try {
                $query2 = $this->connect->prepare('INSERT INTO join_groups (id, user_id, group_id) VALUES ' . $values);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
        if($delete) {
            $values = '';
            for($i = 0; $i < sizeof($delete); $i++) {
                if($i != 0) {
                    $values .= ' OR ';
                }
                $values .= 'user_id = ' . $delete[$i];
            }
            
            try {
                $query3 = $this->connect->prepare('DELETE FROM join_groups WHERE group_id = :id AND (' . $values . ')');
                $query3->bindValue(':id', $id, PDO::PARAM_INT);
                $query3->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
        if($name) {
            try {
                $query4 = $this->connect->prepare('UPDATE groups SET name = :name WHERE id = :id');
                $query4->bindValue(':id', $id, PDO::PARAM_INT);
                $query4->bindValue(':name', $name, PDO::PARAM_STR);
                $query4->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
    }
    public function is_block($id) {
        try {
            $query = $this->connect->prepare('SELECT id FROM users WHERE id = :id AND block = 0');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            return false;
        } else {
            return true;
        }
    }
    public function is_group($name) {
        try {
            $query = $this->connect->prepare('SELECT id FROM groups WHERE name = :name');
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }
    public function add_group($name) {
        try {
            $query = $this->connect->prepare('INSERT INTO groups (id, name) VALUES (NULL, :name)');
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function is_project_name($name, $without = null) {
        try {
            $without_query = '';
            if($without) {
                $without_query = ' AND id != ' . $without;
            }
            $query = $this->connect->prepare('SELECT id FROM projects WHERE name = :name' . $without_query);
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }
    public function add_project($args) {
        try {
            $query = $this->connect->prepare('INSERT INTO `projects` (`id`, `name`, `client`, `start_date`, `end_date`, `type`, `settled`, `description`, `intro`, `priority`, `only_group`) VALUES (NULL, :name, :client, :startdate, NULL, :type, :settled, :description, :intro, :priority, :onlygroup)');
            $query->bindValue(':name', $args["name"], PDO::PARAM_STR);
            $query->bindValue(':client', $args["client"], PDO::PARAM_STR);
            $query->bindValue(':startdate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $query->bindValue(':type', $args["type"], PDO::PARAM_STR);
            $query->bindValue(':settled', $args["settled"], PDO::PARAM_INT);
            $query->bindValue(':description', $args["description"], PDO::PARAM_STR);
            $query->bindValue(':intro', $args["intro"], PDO::PARAM_STR);
            $query->bindValue(':priority', $args["priority"], PDO::PARAM_INT);
            $query->bindValue(':onlygroup', $args["onlygroup"], PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        $last_id = $this->connect->lastInsertId();

        $values = '';
        for($i = 0; $i < sizeof($args["allocate"]); $i++) {
            if($i > 0) {
                $values .= ',';
            }
            $values .= " (NULL, " . $last_id . ", " . $args["allocate"][$i] . ")";
        }
        if($values != '') {
            try {
                $query2 = $this->connect->prepare('INSERT INTO `allocations` (`id`, `project_id`, `user_id`) VALUES' . $values);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }

        $values2 = '';
        for($i = 0; $i < sizeof($args["attachments"]); $i++) {
            if($i > 0) {
                $values2 .= ',';
            }
            $attachment = $args["attachments"][$i];
            $values2 .= " (NULL, '" . $attachment["name"] . "', " . $last_id . ", '" . $attachment["url"] . "', '1')";
        }
        if($values2 != '') {
            try {
                $query3 = $this->connect->prepare('INSERT INTO `attachments` (`id`, `name`, `project_id`, `url`, `external`) VALUES' . $values2);
                $query3->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
        return $last_id;
    }
    public function edit_project($args) {
        try {
            $query = $this->connect->prepare('UPDATE `projects` SET name = :name, client = :client, type = :type, settled = :settled, description = :description, intro = :intro, priority = :priority, only_group = :onlygroup WHERE id = :id');
            $query->bindValue(':id', $args["id"], PDO::PARAM_INT);
            $query->bindValue(':name', $args["name"], PDO::PARAM_STR);
            $query->bindValue(':client', $args["client"], PDO::PARAM_STR);
            $query->bindValue(':type', $args["type"], PDO::PARAM_STR);
            $query->bindValue(':settled', $args["settled"], PDO::PARAM_INT);
            $query->bindValue(':description', $args["description"], PDO::PARAM_STR);
            $query->bindValue(':intro', $args["intro"], PDO::PARAM_STR);
            $query->bindValue(':priority', $args["priority"], PDO::PARAM_INT);
            $query->bindValue(':onlygroup', $args["onlygroup"], PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }

        try {
            $query2 = $this->connect->prepare('DELETE FROM allocations WHERE project_id = :id');
            $query2->bindValue(':id', $args["id"], PDO::PARAM_INT);
            $query2->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }

        $values = '';
        for($i = 0; $i < sizeof($args["allocate"]); $i++) {
            if($i > 0) {
                $values .= ',';
            }
            $values .= " (NULL, " . $args["id"] . ", " . $args["allocate"][$i] . ")";
        }
        if($values != '') {
            try {
                $query3 = $this->connect->prepare('INSERT INTO `allocations` (`id`, `project_id`, `user_id`) VALUES' . $values);
                $query3->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
        $values2 = '';
        for($i = 0; $i < sizeof($args["delete_old_attachments"]); $i++) {
            if($i > 0) {
                $values2 .= ' OR';
            }
            $values2 .= ' name = "' . $args["delete_old_attachments"][$i]["name"] . '" AND project_id = ' . $args["id"];

            if($args["delete_old_attachments"][$i]["external"] == 0) {
                $path = __DIR__ . '/..' . $args["delete_old_attachments"][$i]["url"];
                unlink($path);
            }
        } 
        if($values2 != '') {
            try {
                $query4 = $this->connect->prepare('DELETE FROM `attachments` WHERE' . $values2);
                $query4->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }

        $values3 = '';
        for($i = 0; $i < sizeof($args["attachments"]); $i++) {
            if($i > 0) {
                $values3 .= ',';
            }
            $attachment = $args["attachments"][$i];
            $values3 .= " (NULL, '" . $attachment["name"] . "', " . $args["id"] . ", '" . $attachment["url"] . "', '1')";
        }
        if($values3 != '') {
            try {
                $query5 = $this->connect->prepare('INSERT INTO `attachments` (`id`, `name`, `project_id`, `url`, `external`) VALUES' . $values3);
                $query5->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
        }
    }
    public function delete_project($project_id) {
        try {
            $query = $this->connect->prepare('SELECT id FROM projects WHERE id = :id');
            $query->bindValue(':id', $project_id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            try {
                $query2 = $this->connect->prepare('DELETE FROM projects WHERE id = :id');
                $query2->bindValue(':id', $project_id, PDO::PARAM_INT);
                $query2->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }

            try {
                $query3 = $this->connect->prepare('DELETE FROM allocations WHERE project_id = :id');
                $query3->bindValue(':id', $project_id, PDO::PARAM_INT);
                $query3->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }

            try {
                $query4 = $this->connect->prepare('SELECT url FROM attachments WHERE project_id = :id AND external = 0');
                $query4->bindValue(':id', $project_id, PDO::PARAM_INT);
                $query4->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }
            $delete_files = $query4->fetchAll();
            foreach($delete_files as $file) {
                $path = __DIR__ . '/..' . $file["url"];
                unlink($path);
            }

            try {
                $query5 = $this->connect->prepare('DELETE FROM attachments WHERE project_id = :id');
                $query5->bindValue(':id', $project_id, PDO::PARAM_INT);
                $query5->execute();
            } catch(PDOException $error) {
                add_log('[Error] Nieudane zapytanie. '. $error);
                exit('Błąd łączenia z bazą danych');
            }

            return true;
        } else {
            return false;
        }
    }
    public function add_upload_files($project_id, $files) {
        $values = '';
        for($i = 0; $i < sizeof($files); $i++) {
            if($i > 0) {
                $values .= ',';
            }
            $files[$i]["name"] = str_replace('_', ' ', $files[$i]["name"]);
            $values .= " (NULL, '" . $files[$i]["name"] . "', " . $project_id . ", '" . $files[$i]["url"] . "', '0')";
        }

        try {
            $query = $this->connect->prepare('INSERT INTO `attachments` (`id`, `name`, `project_id`, `url`, `external`) VALUES' . $values);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
    }
    public function end_project($project_id) {
        try {
            $query = $this->connect->prepare('SELECT end_date FROM projects WHERE id = :id');
            $query->bindValue(':id', $project_id, PDO::PARAM_INT);
            $query->execute();
        } catch(PDOException $error) {
            add_log('[Error] Nieudane zapytanie. '. $error);
            exit('Błąd łączenia z bazą danych');
        }
        if($query->rowCount() == 1) {
            if($query->fetch()["end_date"] == NULL) {
                try {
                    $query2 = $this->connect->prepare('UPDATE projects SET end_date = :date WHERE id = :id');
                    $query2->bindValue(':id', $project_id, PDO::PARAM_INT);
                    $query2->bindValue(':date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                    $query2->execute();
                } catch(PDOException $error) {
                    add_log('[Error] Nieudane zapytanie. '. $error);
                    exit('Błąd łączenia z bazą danych');
                }
            }
            return true;
        } else {
            return false;
        }
    }
}