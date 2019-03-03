<?php
$id = 0;
if(isset($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
}
return [
    "project_name" => "Your Project Name",
    "footer_text" => date('Y') . ' &copy; <a href="your-link.pl" target="_blank">Link Name</a>',
    "footer2_text" => 'Development by Marcin Kalinowski',

    "logs_limit" => 100,
    "projects_per_query" => 20,

    "database" => [
        "db_host" => "localhost",
        "db_name" => "database_name",
        "db_user" => "database_user",
        "db_password" => "database_password"
    ],

    // direct admin config
    "email" => [
        "email_host" => "mail.email_host.pl",
        "email_user" => "work@email_host.pl",
        "email_password" => "email_password",
        "email_port" => 587,
        "email_secure" => "none",
        "email_auth" => true
    ],

    // gmail config
    // "email" => [
    //     "email_host" => "smtp.gmail.com",
    //     "email_user" => "user",
    //     "email_password" => "pass",
    //     "email_port" => 465,
    //     "email_secure" => 'ssl',
    //     "email_auth" => true
    // ],

    "filter" => [
        "1",
        "projects.end_date IS NULL",
        "projects.end_date IS NOT NULL",
        "projects.priority != 0 AND projects.end_date IS NULL",
        "projects.settled = 0 AND projects.end_date IS NULL",
        "projects.end_date IS NULL AND projects.id = allocations.project_id AND allocations.user_id = "
    ],
    "sort" => [
        "start_date",
        "end_date",
        "name",
        "priority",
        "type",
        "client"
    ],
    "permission_names" => [
        "manage_project" => "Zarządzanie projektami",
        "add_reader" => "Dodanie czytelnika",
        "manage_users" => "Zarządzanie użytkownikami",
        "manage_roles" => "Zarządzanie rolami",
        "manage_groups" => "Zarządzanie grupami",
        "allocation" => "Przydzielanie do projektów"
    ]
];